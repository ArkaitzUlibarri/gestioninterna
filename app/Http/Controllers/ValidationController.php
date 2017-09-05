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
        $this->middleware('auth');
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
    private function addUserSheet($name, $excel, $data)
    {
        if(count($data) > 0) {
            $excel->sheet($name, function($sheet) use ($data) {
                //Estilos
                $sheet->freezeFirstRow();
                $sheet->setHeight(1, 20);
                $sheet->cells('A1:D1', function($row) {
                    $row->setBackground('#C6EFD8');
                    $row->setFontColor('#006100');
                    $row->setAlignment('center');
                    $row->setValignment('center');
                });

                //Cabecera
                $sheet->appendRow(1, array('User', 'Month', 'Task', 'Hours'));

                //Filas de datos
                foreach ($data as $entry) {
                    $sheet->appendRow(array(
                        $entry['user_name'],
                        $entry['month'],
                        $entry['name'],
                        $entry['time_slot']
                    ));
                }            
            });

            return;
        }

        $excel->sheet($name, function($sheet) {
            $sheet->row(1, array('No data available'));
        });
    }
}