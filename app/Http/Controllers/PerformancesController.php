<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Project;
use App\PerformanceRepository;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerformancesController extends Controller
{
	protected $performanceRepository;

    public function __construct(PerformanceRepository $performanceRepository)
    {
        $this->middleware('auth');
        //$this->middleware('checkrole');
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

    public function download($year,$employee)
    {   
        $data = $this->performanceRepository->performanceByUserYear($year,$employee);
        $fileName =  Carbon::now('Europe/Madrid')->format('Ymd_Hi') . '_performance_evaluation';

        Excel::create($fileName, function($excel) use ($data) {
            $this->addSheet('performance', $excel, $data);
        })->export('xlsx');
    }

     /**
     * Creo una pestaña nueva en el excel.
     * 
     * @param $name     Nombre de la pestaña
     * @param $excel    Excel sobre el que crear la pestaña
     * @param $data     Datos a insertar en la pestaña
     */
    private function addSheet($name, $excel, $data)
    {
        //Con datos
        if(count($data) > 0) {
            $excel->sheet($name, function($sheet) use ($data) {
                //Estilos
                $sheet->freezeFirstRow();
                $sheet->setHeight(1, 20);
                $sheet->setBorder('A1:N1', 'thin');
                $sheet->cells('A1:N1', function($row) {
                    $row->setBackground('#C6EFD8');
                    $row->setFontColor('#006100');
                    $row->setAlignment('center');
                    $row->setValignment('center');
                });

                //Cabecera
                $header = ['Criteria','January','February','March','April','May','June','July','August','September','October','November','December','Total'];
                $sheet->appendRow(1, $header);

               //Formateo de datos
               $result = array();
               
                foreach ($data as $index => $array) {
                    $project = $array->project_id;
                    $key = $array->type . '|' . $array->month;

                    if(! isset($result[$project][$key])) {
                        $result[$project][$key] = [
                            'id'      => $array->id,
                            'mark'    => $array->mark,
                            'comment' => ucfirst($array->comment),
                            'weight'  => $array->weight
                        ];
                    }
                }

                $writer = array();

                //Proyecto-Criterio-Mes
                foreach ($result as $project => $value) {
                    foreach (config('options.criterion') as $criteria) {
                        
                        //Guardar por cada mes un valor
                        $counter = 0;
                        $acum = 0;
                        for ($month = 1; $month < 13 ; $month++) { 
                            $key = $criteria . '|' . $month;    
                            if(isset($result[$project][$key])){
                                $counter++;
                                $acum += intval($result[$project][$key]['mark']);
                                $writer[$month] = $result[$project][$key]['mark'];
                            }
                            else{
                                $writer[$month] = "-";
                            }                               
                        }

                        //Escribir Filas
                        $sheet->appendRow(array(
                            $criteria,
                            $writer[1],
                            $writer[2],
                            $writer[3],
                            $writer[4],
                            $writer[5],
                            $writer[6],
                            $writer[7],
                            $writer[8],
                            $writer[9],
                            $writer[10],
                            $writer[11],
                            $writer[12],
                            $acum/$counter
                        ));

                    }
                    $sheet->appendRow(array(""));//Linea en Blanco
                }

            });

            return;
        }

        //Sin datos
        $excel->sheet($name, function($sheet) {
            $sheet->row(1, array('No data available'));
        });
    }

    private function getEvaluations($user_id,$year)
    {
        $p = DB::table('working_report')
            ->select('project_id')
            ->whereYear('created_at',$year)
            ->where('user_id',$user_id)
            ->whereNotNull('project_id')
            ->get();

        $q = DB::table('performances')
            ->select('project_id','type','mark','weight')
            ->where('year',$year)
            ->where('user_id',$user_id)
            ->get();

        return $p;
    }

}