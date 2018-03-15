<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Project;
use App\User;
use App\Performance;
use App\PerformanceRepository;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerformancesController extends Controller
{
	protected $performanceRepository;

    public function __construct(PerformanceRepository $performanceRepository)
    {
        $this->performanceRepository = $performanceRepository;
    }

    public function index()
    {
        if(Auth::user()->primaryRole() == 'admin'){
            $projects = Project::all();
            $projects = array_pluck($projects, 'name', 'id');
        }
        elseif (Auth::user()->primaryRole() == 'manager'){
            $projects = Auth::user()->managerProjects();
        }
        else{
             $projects = Auth::user()->reportableProjects();
        }
		
    	return view('evaluations.performance',compact('projects'));
    }

    public function download($year,$user_id)
    {   
        $data = $this->performanceRepository->formatOutputByProject(
            $this->performanceRepository->performanceByUserYear($year,$user_id)
        );
        
        $fileName =  Carbon::now('Europe/Madrid')->format('Ymd_Hi') . '_performance_evaluation_' . str_replace(" ", "_", User::find($user_id)->fullname) . "_" . $year;

        Excel::create($fileName, function($excel) use ($data) {
    
            if(! is_null($data)){
                //Hojas por proyecto
                foreach ($data as $project_id => $array) {
                    if($project_id != ""){
                        $this->addSheet(Project::find($project_id)->name,$excel,$data,'Average',$project_id);
                    }                     
                }

                //Hoja Total
                $this->addSheet('Total',$excel,$data,'Total');
            }
            else{
                 //Sin datos
                $excel->sheet('Performance', function($sheet) {
                    $sheet->row(1, array('No data available'));
                });
            }

        })->export('xlsx');
    }

     /**
     * Creo una pestaña nueva en el excel.
     * 
     * @param $name     Nombre de la pestaña
     * @param $excel    Excel sobre el que crear la pestaña
     * @param $data     Datos a insertar en la pestaña
     * @param $column   Nombre de la columna de la tabla
     * @param $project_id   Indice del proyecto
     */
    private function addSheet($name, $excel, $data, $column, $project_id = "")
    {
        //Con datos
        if(count($data) > 0) {
            $excel->sheet($name, function($sheet) use ($data,$column,$project_id) {

                //Estilos Cabecera
                $this->sheetHeaderStyle($sheet, 'A1:N1');

                //Cabecera
                $header = ['Criteria','January','February','March','April','May','June','July','August','September','October','November','December',$column];
                $sheet->appendRow(1, $header);

                //Crear tablas
                $writer = ($column == 'Average') 
                    ? $this->projectTableWriter($data,$project_id) 
                    : $this->totalTableWriter($data);

                //Escribir filas
                foreach ($writer as $key => $value) {              
                    $sheet->appendRow(array(
                        $key,$value[1],$value[2],$value[3],$value[4],$value[5],$value[6],$value[7],$value[8],$value[9],$value[10],$value[11],$value[12],$value[$column]
                    ));                   
                }

            });

            return;
        }

        //Sin datos
        $excel->sheet($name, function($sheet) {
            $sheet->row(1, array('No data available'));
        });
    }

    /**
     * Cambiar estilo del rango de la hoja
     * @param  [type] $sheet [description]
     * @param  [type] $range [description]
     * @return [type]        [description]
     */
    private function sheetHeaderStyle($sheet, $range)
    {
        $sheet->freezeFirstRow();
        $sheet->setHeight(1, 20);
        $sheet->setBorder($range, 'thin');
        $sheet->cells($range, function($row) {
            $row->setBackground('#C6EFD8');
            $row->setFontColor('#006100');
            $row->setAlignment('center');
            $row->setValignment('center');
        });
    }

    /**
     * Parsea los datos por criterio,mes y proyecto
     * @param  [type] $data       [description]
     * @param  [type] $project_id [description]
     * @return [type]             [description]
     */
    private function projectTableWriter($data,$project_id)
    {
        $writer = array();
        
        //Criteria
        foreach (Performance::CRITERION as $criteria) {
            if($criteria != 'knowledge'){
                //Guardar por cada mes un valor
                $counter = 0;
                $acum = 0;

                for ($month = 1; $month < 13 ; $month++) { 
                    $key2 = $criteria . '|' . $month;    
                    if(isset($data[$project_id][$key2])){
                        $counter++;
                        $acum += intval($data[$project_id][$key2]['mark']);
                        $writer[$criteria][$month] = $data[$project_id][$key2]['mark'];
                    }
                    else{   
                        $writer[$criteria][$month] = "-";
                    }                               
                }

                //Escribir Filas                             
                $writer[$criteria]['Average'] = ($acum != 0 || $counter != 0) ? round($acum/$counter,2) : '';                            
            }
        }

        //Weight
        for ($month = 1; $month < 13 ; $month++) {  
            $key = 'quality' . '|' . $month;//Todos los criterios excepto el knowledge (100%)  tienen el mismo peso
            if(isset($data[$project_id][$key])){
                $writer['Weight'][$month] = $data[$project_id][$key]['weight']; 
            }  
            else{
                $writer['Weight'][$month] = "-";
            }                                                         
        }
        $writer['Weight']['Average'] = ""; 

        return $writer;
        
    }

    /**
     * Parsea los datos para generar la tabla total
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function totalTableWriter($data)
    {
        $writer = array();
            
        //Knowledge
        $project_id = "";
        $criteria = 'knowledge';
        for ($month = 1; $month < 13 ; $month++) {                  
            $key = $criteria . '|' . $month;  
            $writer[$criteria][$month] = "-";  
            if(isset($data[$project_id][$key])){
                $writer[$criteria][$month] = $data[$project_id][$key]['mark'];
            }                                                
        }
                 
        //Calculo acumulado meses
        foreach (Performance::CRITERION as $criteria) {
            if($criteria != 'knowledge'){
                for ($month = 1; $month < 13 ; $month++) {   
                    $key = $criteria . '|' . $month; 
                    $writer[$criteria][$month] = "-";  
                    foreach ($data as $project_id => $value) {
                        if($project_id != ""){
                            //---------------------------------------------------                         
                            if(isset($data[$project_id][$key])){
                                $mark = $data[$project_id][$key]['mark'];
                                $weight = floatval($data[$project_id][$key]['weight'])/100;  
                                //Reinicializar                          
                                if(isset($writer[$criteria][$month])){
                                    if($writer[$criteria][$month] == "-"){
                                        $writer[$criteria][$month] = 0;
                                    }
                                }
                                $writer[$criteria][$month] += round($mark * $weight,2);//Acumular nota*peso
                            }                
                            //---------------------------------------------------
                        }
                    }
                }
            }
        }

        //Generar columna totales
        foreach ($writer as $criteria => $value) {
            $counter = 0;
            $acum = 0;
            $value['zeros'] = 0;
            for ($month = 1; $month < 13 ; $month++) {
                 if(is_numeric($value[$month])){
                    $acum += $value[$month];
                    $counter++;
                    $value['zeros'] = $value[$month] == 0 ? $value['zeros'] + 1 : $value['zeros'];
                 } 
            }
            $writer[$criteria]['Total'] = ($acum != 0 || $counter != 0) ? round($acum/$counter* pow((2/3),$value['zeros']),2) : '';
        }     
        
        //Total
        for ($month = 1; $month < 13 ; $month++) {                  
            $writer['Total'][$month] = "";                                               
        }
        $writer['Total']['Total'] = 0;

        foreach (config('options.performance_evaluation') as $criteria) {  
            $maxValue = count($criteria["points"])-1;
            $mark = $writer[$criteria['code']]['Total'];
            $percentage = $criteria['percentage'];
            $writer['Total']['Total'] += $mark/$maxValue * $percentage;
        }
                                               
        return $writer;
    }

}