<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Project;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class PerformancesController extends Controller
{
	protected $userRepository;

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('checkrole');
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

    public function download()
    {   
        $data = Project::all();

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

                $header = ['Criteria','January','February','March','April','May','June','July','August','September','October','November','December','Total'];
                
                //Cabecera
                $sheet->appendRow(1, $header);

                //Filas de datos
                /*
                foreach ($data as $entry) {
                    foreach ($entry['items'] as $item) {
                        $sheet->appendRow(array(
                            $entry['user_name'],
                            $entry['created_at'],
                            $item['name'],
                            $item['time_slot'],
                            $entry['total'],
                            $entry['manager']
                        ));
                    }
                }
                */

            });

            return;
        }

        //Sin datos
        $excel->sheet($name, function($sheet) {
            $sheet->row(1, array('No data available'));
        });
    }

    private function getEvaluations($user_id,$month,$year)
    {
        $q = DB::table('performances')
                ->select('project_id','type','mark','weight')
                ->where('year',$year)
                ->where('month',$month)
                ->where('user_id',$user_id)
                ->get();
    }

}