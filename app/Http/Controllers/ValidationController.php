<?php

namespace App\Http\Controllers;

use App\WorkingreportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
    	return view('validation.index');
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
                $sheet->freezeFirstRow();
                $sheet->setHeight(1, 20);
                $sheet->cells('A1:F1', function($row) {
                    $row->setBackground('#C6EFD8');
                    $row->setFontColor('#006100');
                    $row->setAlignment('center');
                    $row->setValignment('center');
                });

                $sheet->appendRow(1, array('user', 'day', 'task', 'time', 'total', 'validated_by'));

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
