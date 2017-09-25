<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserRepository;
use Carbon\Carbon;
use App\User;
use App\Project;
use Illuminate\Support\Facades\Auth;

class PerformancesController extends Controller
{
	protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth');
        //$this->middleware('checkrole');
        $this->userRepository = $userRepository;
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
        /*
        $data = $this->reportRepository->formatOutput(
            $this->reportRepository->fetchData()
        );
        */

        $fileName =  Carbon::now('Europe/Madrid')->format('Ymd_Hi') . '_performance_evaluation';
        dd($fileName);
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
        if(count($data) > 0) {
            $excel->sheet($name, function($sheet) use ($data) {
                //Estilos
                $sheet->freezeFirstRow();
                $sheet->setHeight(1, 20);
                $sheet->cells('A1:F1', function($row) {
                    $row->setBackground('#C6EFD8');
                    $row->setFontColor('#006100');
                    $row->setAlignment('center');
                    $row->setValignment('center');
                });

                //Cabecera
                $sheet->appendRow(1, array('User', 'Day', 'Task', 'Time', 'Total', 'Validated By'));

                //Filas de datos
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
            });

            return;
        }

        $excel->sheet($name, function($sheet) {
            $sheet->row(1, array('No data available'));
        });
    }

}