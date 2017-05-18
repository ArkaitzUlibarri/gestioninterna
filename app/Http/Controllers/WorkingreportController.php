<?php

namespace App\Http\Controllers;

use App\Absence;
use App\WorkingreportRepository;
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
	public function __construct(WorkingreportRepository $workingreportRepository)
	{
		$this->middleware('auth');
		$this->workingreportRepository = $workingreportRepository;
	}

	public function index(Request $request)
	{
		$today   = Carbon::now();
		$user_id = Auth::user()->id;
		$role    = Auth::user()->role;

		if($role == config('options.roles')[0]) {		
			$admin = true;
		}
		else {
			$admin = false;
		}

		//$workingreports = $this->getReportsPerUserPerDay($user_id,$admin);
		$workingreports = $this->workingreportRepository->search($request->all(), $user_id,$admin);

		return view('workingreports.index', compact('workingreports','user_id','today','admin'));
	}

	public function edit($user_id,$date)
	{
		$absences       = Absence::all();
		$auth_user      = Auth::user();
		$workingreports = $this->getReportsPerDay($user_id,$date);
		$groupProjects  = $this->getGroupsProjectsByUser($user_id);
		$categories     = $this->getCategories($user_id);

		return view('workingreports.edit',compact('date','auth_user','user_id','workingreports','absences','groupProjects','categories'));
	}

	private function getReportsPerUserPerDay($user_id , $admin)
	{
		$query = DB::table('working_report')
			->join('users','working_report.user_id','=','users.id') 
			->select(
				DB::raw("CONCAT(users.name, ' ', users.lastname_1 ) as fullname"),
				'working_report.created_at',
				'working_report.user_id',
				DB::raw("sum(time_slots)*0.25 as horas_reportadas"),
				DB::raw("SUM(case when pm_validation = 0 then time_slots else 0 end)*0.25 as horas_validadas_pm"),
				DB::raw("SUM(case when admin_validation = 0 then time_slots else 0 end)*0.25 as horas_validadas_admin")
			)
			->groupBy('user_id','created_at')
			->orderBy('created_at','desc');
				
		if(! $admin) {
			$query = $query->where('user_id',$user_id);
		}

		return $query->get();		
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
			->where('groups.enabled',1)
			->orderBy('group_id','asc')
			->get();
	}

	private function getCategories($user_id)
	{
		return DB::table('category_user')
			->select(
				'category_user.user_id',
				'category_user.category_id',
				'categories.code',
				'categories.name',
				'categories.description'
			)
			->LeftJoin('users','category_user.user_id','=','users.id')
			->LeftJoin('categories','category_user.category_id','=','categories.id')
			->where('user_id',$user_id)
			->get();
	}
}
