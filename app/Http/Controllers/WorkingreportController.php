<?php

namespace App\Http\Controllers;

use App\Absence;
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

        $role=Auth::user()->role;

        if($role == config('options.roles')[0]){
            $workingreports = $this->getReportsPerUserPerDay($user_id,true);
        }
        else{
            $workingreports = $this->getReportsPerUserPerDay($user_id,false);
        }
        
    	return view('workingreports.index', compact('workingreports','user_id','today'));
    }

    public function edit($user_id,$date)
    {
        $workingreports=$this->getReportsPerDay($user_id,$date);
        $absences=Absence::all();
        $groupProjects=$this->getGroupsProjectsByUser(7);

        return view('workingreports.edit',compact('workingreports','date','user_id','absences','groupProjects'));
    }

    private function getReportsPerUserPerDay($user_id , $auth)
    {
        if($auth){
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
			->groupBy('user_id','created_at')
            //->where('user_id',$user_id)  
            ->orderBy('created_at','asc')
            ->get();
        }

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
            ->groupBy('user_id','created_at')
            ->where('user_id',$user_id)  
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

    private function getGroupsProjectsByUser($user_id)
    {
        return DB::table('group_user')
            ->select(
                'group_user.group_id',
                'groups.name as group',
                'groups.project_id',
                'projects.name as project'
            )
            ->join('users','group_user.user_id','=','users.id')
            ->join('groups','group_user.group_id','=','groups.id')
            ->join('projects','groups.project_id','=','projects.id')
            ->where('user_id',$user_id)
            ->orderBy('group_id','asc')
            ->get();
    }

}
