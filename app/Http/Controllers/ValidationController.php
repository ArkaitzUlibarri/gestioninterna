<?php

namespace App\Http\Controllers;

use App\WorkingreportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Auth;

class ValidationController extends Controller
{
    protected $reportRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WorkingreportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
     * Show the main validation view.
     * 
     * @return view
     */
    public function index()
    {
        $groupsProjects = $this->getGroupsProjects();

    	return view('validation.index',compact('groupsProjects'));
    }

    private function getGroupsProjects()
    {        
        $q =  DB::table('groups as g')
            ->join('projects as p','g.project_id','p.id')
            ->LeftJoin('group_user as gu','g.id','gu.group_id')
            ->distinct()
            ->select(
                'g.id as group_id',
                'g.name as group',
                'p.id as project_id',
                'p.name as project'
            );

        if (Auth::user()->primaryRole() == 'manager'){
            $q = $q->whereIn('p.id',array_keys(Auth::user()->activeProjects()));
        }

        return $q = $q->get();             
    }

    /**
     * Download the last two months report.
     * 
     * @return Excel
     */
    public function download()
    {
        $data = $this->reportRepository->formatOutput(
            $this->reportRepository->fetchData()
        );

        $fileName =  Carbon::now('Europe/Madrid')->format('Ymd_Hi') . '_working_report';

        Excel::create($fileName, function($excel) use ($data) {
            $this->addSheet('report', $excel, $data);
        })->export('xlsx');
    }

    /**
     * Download yearly report.
     * 
     * @return Excel
     */
    public function yearReport()
    {
        $data = $this->reportRepository->formatMonthlyOutput(
            $this->reportRepository->fetchMonthlyData()
        );
        
        $fileName =  Carbon::now('Europe/Madrid')->format('Ymd_Hi') . '_year_report';
        $name = strval(Carbon::now('Europe/Madrid')->year);
        
        Excel::create($fileName, function($excel) use ($data, $name) {
            $this->addUserSheet($name, $excel, $data);
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

                //Estilos Cabecera
                $this->sheetHeaderStyle($sheet,'A1:F1');

                //Cabecera
                $header = ['User', 'Day', 'Task', 'Time', 'Total', 'Validated By'];
                $sheet->appendRow(1, $header);

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

        $this->outputMessage($excel,$name,'No data available');
    }



    /**
     * Creo una pestaña nueva en el excel.
     * 
     * @param $name     Nombre de la pestaña
     * @param $excel    Excel sobre el que crear la pestaña
     * @param $data     Datos a insertar en la pestaña
     */
    private function addUserSheet($name, $excel, $data)
    {
        if(count($data) > 0) {
            $excel->sheet($name, function($sheet) use ($data) {
                
                //Estilos Cabecera
                $this->sheetHeaderStyle($sheet,'A1:D1');

                //Cabecera
                $header = ['User', 'Month', 'Task', 'Hours'];
                $sheet->appendRow(1, $header);

                //Escribir filas
                foreach ($data as $entry) {
                    $array = [$entry['user_name'],$entry['month'],$entry['name'],$entry['time_slot']];
                    $sheet->appendRow($array);
                }    

            });
            return;
        }

        $this->outputMessage($excel,$name,'No data available');
    }

    private function outputMessage($excel,$name, $message)
    {
        $excel->sheet($name, function($sheet) use ($message) {
            $sheet->row(1, array($message));
        });
    }

    private function sheetHeaderStyle($sheet, $range)
    {
        $sheet->freezeFirstRow();
        $sheet->setHeight(1, 20);
        $sheet->cells($range, function($row) {
            $row->setBackground('#C6EFD8');
            $row->setFontColor('#006100');
            $row->setAlignment('center');
            $row->setValignment('center');
        });
    }
}