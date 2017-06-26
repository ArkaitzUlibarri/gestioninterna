<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Project;
use Illuminate\Support\Facades\Validator;
use Auth;

class ValidationController extends ApiController
{
	/**
	 * Get reports by users role and projects associated
	 * 
	 * @param  Request $request
	 * @return json
	 */
	public function index(Request $request)
	{
		$data = $this->fetchData(
			$request->all()
		);

		$data = $this->formatOutput($data);

		//PM
		if(Auth::user()->isPM() && ! Auth::user()->isAdmin() ){
			return $this->respond(
				$this->filterByUsersInProject($data, array_values(Auth::user()->PMProjects()))
			);
		}

		return $this->respond($data);			
	}

	/**
	 * Update user reports by week.
	 * 
	 * @param  Request $request
	 * @return json
	 */
	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'required|numeric',
			'year'    => 'required|numeric',
			'week'    => 'required|numeric',
			'value'   => 'required|in:true,false',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		$validationField = Auth::user()->isAdmin() ? 'admin_validation' : 'pm_validation';

		$value = $request->get('value') == 'false' ? true : false;

		DB::table('working_report')
            ->where('user_id', $request->get('user_id'))
            ->whereRaw ("YEAR(created_at) = {$request->get('year')}")
            ->whereRaw ("WEEK(created_at) = {$request->get('week')}")
            ->update([$validationField => $value]);

 		return $this->respond();
	}

	/**
	 * Fetch data from database.
	 * 
	 * @return array
	 */
	private function fetchData($data)
	{
		$date = Carbon::today()->subMonths(3);

		$validationField = Auth::user()->isAdmin()
			? 'working_report.admin_validation'
			: 'working_report.pm_validation';

		$q = DB::table('working_report')	
			->whereDate('working_report.created_at','>=',$date)//Filtro de fecha
			->leftJoin('projects', 'working_report.project_id','=','projects.id')
			->leftJoin('absences', 'working_report.absence_id','=','absences.id')
			->leftJoin('users', 'working_report.user_id','=','users.id')
			->select(
				'working_report.user_id',
				DB::raw("concat(users.name, ' ', users.lastname) as user_name"),
				DB::raw('YEAR(working_report.created_at) as date_year'),
				DB::raw('WEEK(working_report.created_at) as date_week'),
				DB::raw('UPPER(projects.name) as project'),
				DB::raw('UPPER(working_report.training_type) as training_type'),
				DB::raw('UPPER(absences.name) as absence'),
				DB::raw('SUM(working_report.time_slots* 0.25) as time_slot')
			)
			->groupBy(['user_id', 'date_year', 'date_week', 'working_report.project_id', 'training_type', 'absence_id'])
			->orderBy('user_id', 'ASC');

			//User
			if(! Auth::user()->isAdmin() && ! Auth::user()->isPM()){
				$q = $q->where('working_report.user_id', Auth::id());//Filtro de usuario
				$q = $q->where('working_report.admin_validation', $data['validated'] == "false" ? false : true);//Filtro de validacion usuario
			}		
			else{
				$q = $q->where($validationField, $data['validated'] == 'false' ? false : true);//Filtro de validacion PM y Admin

				if(Auth::user()->isAdmin()){
					 $q = $q->where('working_report.pm_validation',true);//Si admin validado por pm
				}
				elseif(Auth::user()->isPM()){
				 $q = $q->where('working_report.admin_validation',false);//Si admin ha validado,pm no puede desvalidar
				}	
			}	

			return $q->get();
	}

	/**
	 * Format data.
	 * 
	 * @param  array
	 * @return array
	 */
	private function formatOutput($data)
	{
		$response = array();

		foreach ($data as $value) {
			$key = $value->user_id . '|' . $value->date_year . '|' . $value->date_week;

			if(! isset($response[$key])) {
				$response[$key] = [
					'user_id' => $value->user_id,
					'user_name' => $value->user_name,
					'date_year' => $value->date_year,
					'date_week' => $value->date_week,
					'items' => [],
					'total' => 0
				];
    		}

    		$response[$key]['total'] += $value->time_slot;

    		$response[$key]['items'][] = [
    			'name' => $this->activity($value),
    			'time_slot' => $value->time_slot
    		];
    	}

    	return $response;
	}

	private function filterByProject($reports, $projects)
	{
		$response = array();
		foreach ($reports as $key => $report) {
			foreach ($report['items'] as $item) {
				if(in_array($item['name'], $projects)) {
					$response[$key] = $report;
					break;
				}
			}
		}
		return $response;
	}

	private function filterByUsersInProject($reports, $projects)
	{
		$ids = array();
		foreach ($projects as $value) {
			$project = Project::where('name',$value)->first();
			$groups = $project->groups;
			foreach ($groups as $group) {
				$users = $group->users;
				foreach ($users as $user) {
					if(! in_array($user->id,$ids)){
						array_push($ids, $user->id);
					}
				}
			}
		}

		$response = array();
		foreach ($reports as $key => $report) {
			if(in_array($report['user_id'], $ids)) {
				$response[$key] = $report;
			}	
		}
		
		return $response;
	}

	/**
	 * Get activity.
	 * 
	 * @param  $data
	 * @return string
	 */
	private function activity($data)
	{
		foreach (['project', 'absence', 'training_type'] as $activity) {
			if($data->$activity != null) {
				return $data->$activity;
			}
		}
	}
}
