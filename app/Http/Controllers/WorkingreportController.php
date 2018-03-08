<?php

namespace App\Http\Controllers;

use App\Absence;
use Carbon\Carbon;
use App\Category;
use App\Project;
use App\User;
use App\WorkingreportRepository;
use App\Teleworking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class WorkingReportController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(WorkingreportRepository $workingreportRepository)
	{
		$this->workingreportRepository = $workingreportRepository;
	}

	public function index(Request $request)
	{
		$users = User::all()->sortBy('name');
		$users = $users->filter(function($user)	{
			$output = false;
			foreach ($user->contracts as $contract) {	
				if($contract->isActive()){
					$output = true;
					return $output;
				}
			}
			if ($output) {
				return $user;
			}			
		});

		$projects = Auth::user()->primaryRole() == 'admin'
			? Project::whereNull('end_date')->get()
			: Auth::user()->activeProjects();

		$workingreports = $this->workingreportRepository->search(
			$request->all(),
			Auth::user()->id,
			$projects,
			true
		);
		
		$filter = array(	
			'project'    => $request['project'],
			'name'       => $request['name'],
			'date'       => $request['date'],
			'validation' => $request['validation'],
		);
		
		return view('workingreports.index', compact('workingreports', 'users', 'projects', 'filter'));
	}

	public function edit($userId, $date)
	{
		$reportUser = User::find($userId);
		$absences = Absence::all();
		$workingreports = $this->getReportsPerDay($userId, $date);
		$groupProjects = $this->getGroupsProjectsByUser($userId);
		$categories = $this->getCategories($userId);
		$contract = $reportUser->contracts->where('end_date', null)->first();

		//Teletrabajo
		$teleworking = null;
		if($contract != null ){
            $teleworking = $contract->teleworking->where('end_date', null)->first();
        }
        if($teleworking == null){
			$teleworking = new Teleworking;
		}  

		return view('workingreports.edit',compact('date','reportUser','workingreports','absences','groupProjects','categories','contract','teleworking'));
	}

	private function getReportsPerUserPerDay($userId , $admin)
	{
		$query = DB::table('working_report')
			->join('users','working_report.userId','=','users.id') 
			->select(
				DB::raw("CONCAT(users.name, ' ', users.lastname) as fullname"),
				'working_report.created_at',
				'working_report.userId',
				DB::raw("sum(time_slots)*0.25 as horas_reportadas"),
				DB::raw("SUM(case when pm_validation = 0 then time_slots else 0 end)*0.25 as horas_validadas_pm"),
				DB::raw("SUM(case when admin_validation = 0 then time_slots else 0 end)*0.25 as horas_validadas_admin")
			)
			->groupBy('userId','created_at')
			->orderBy('created_at','desc');
				
		if(! $admin) {
			$query = $query->where('user_id',$userId);
		}

		return $query->get();		
	}

	private function getReportsPerDay($userId,$created)
	{
		return DB::table('working_report')
			->select(
				DB::raw("CONCAT(users.name, ' ', users.lastname) as fullname"),
				'working_report.created_at',
				'working_report.user_id',
				DB::raw("sum(time_slots)*0.25 as horas_reportadas")
			)
			->join('users','working_report.user_id','=','users.id')
			->where('working_report.user_id',$userId)
			->where('working_report.created_at',$created)
			->groupBy('user_id','created_at')
			->orderBy('created_at','asc')
			->get();
	}

	private function getGroupsProjectsByUser($userId)
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
			->where('user_id',$userId)
			->where('groups.enabled',1)
			->where('projects.end_date',null)
			->orderBy('group_id','asc')
			->get();
	}

	private function getCategories($userId)
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
			->where('user_id',$userId)
			->get();
	}

	private function categoryUsers()
	{
		return DB::table('category_user')
			->select(
				'category_user.user_id',
				'users.name as username',
				'users.lastname',
				DB::raw("CONCAT(users.name, ' ', users.lastname) as fullname"),
				'category_user.category_id',
				'categories.code as code',
				'categories.name as category',
				'categories.description as description'
			)
			->LeftJoin('users','category_user.user_id','=','users.id')
			->LeftJoin('categories','category_user.category_id','=','categories.id')
			->get();
	}
}
