<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WorkingReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $today=Carbon::now();

    	$user_id = Auth::user()->id;
        $workingreports = $this->getReportsPerUserPerDay($user_id);
    
    	return view('workingreports.index', compact('workingreports','today'));
    }

    public function edit($id,$date)
    {
        $workingreports=$this->getReportsPerDay($id,$date);
        return view('workingreports.edit',compact('workingreports','date','id'));
    }

    private function getReportsPerUserPerDay($user_id)
    {
    	return DB::table('working_report')
			->select(
				DB::raw("CONCAT(users.name, ' ', users.lastname_1 ) as fullname"),
				'working_report.created_at',
                'working_report.user_id',
				DB::raw("sum(time_slots)*0.25 as horas_reportadas"),
				DB::raw("SUM(case when pm_validation = 0 then time_slots else 0 end)*0.25 as horas_validadas_pm"),
				DB::raw("SUM(case when admin_validation = 0 then time_slots else 0 end)*0.25 as horas_validadas_admin")
            )
            ->join('users','working_report.user_id','=','users.id')
            ->where('user_id',$user_id)
			->groupBy('user_id','created_at')
            ->orderBy('created_at','asc')
	        ->get();
    }

    private function getReportsPerDay($user_id,$created)
    {
        return DB::table('working_report')
            ->select(
                DB::raw("CONCAT(users.name, ' ', users.lastname_1 ) as fullname"),
                'working_report.created_at',
                'working_report.user_id',
                DB::raw("sum(time_slots)*0.25 as horas_reportadas")
            )
            ->join('users','working_report.user_id','=','users.id')
            ->where('working_report.user_id',$user_id)
            ->where('working_report.created_at',$created)
            ->groupBy('user_id','created_at')
            ->orderBy('created_at','asc')
            ->get();
    }
}
