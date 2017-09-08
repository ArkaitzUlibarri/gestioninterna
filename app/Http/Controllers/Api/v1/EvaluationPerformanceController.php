<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;

class EvaluationPerformanceController extends ApiController
{

	/**
	 * Get projects reported for a user and a manager
	 * 
	 * @return json
	 */
	public function loadMonthReports(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'     => 'required|numeric|between:2017,2030',
			'month'    => 'required|numeric',
			'employee' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}
		
		//Groups reported by the user in this month
		$data = DB::table('working_report as wr')
			->Join('projects as p','wr.project_id','p.id')
			->Join('groups as g','wr.group_id','g.id')
			->select(
				'wr.project_id as project_id',
				'p.name as project',
				'wr.group_id as group_id',
				'g.name as group',
				DB::raw("sum(time_slots)*0.25 as hours")
			)
			->whereYear('wr.created_at',$request->get('year'))
			->whereMonth('wr.created_at',$request->get('month'))
			->where('wr.user_id', $request->get('employee'))
			->whereNotNull('wr.group_id')
			->groupBy('wr.project_id','wr.group_id')
			->get()
			->toArray();
				
		return $this->respond($data);
	}

	public function loadEmployees(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'     => 'required|numeric|between:2017,2030',
			'month'    => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		$projects = array_keys(Auth::user()->managerProjects());

		//Groups reported by the user in this month
		$data = DB::table('working_report as wr')
			->Join('users as u','wr.user_id','u.id')
			->Join('categories as c','wr.category_id','c.id')
			->select(
				'wr.user_id as id',
				'u.name as name',
				'u.lastname as lastname',
				DB::raw("CONCAT(u.name, ' ', u.lastname ) as full_name")
			)
			->whereYear('wr.created_at',$request->get('year'))
			->whereMonth('wr.created_at',$request->get('month'))
			->whereIn('wr.project_id',$projects)
			->whereNotNull('wr.group_id')
			->where('c.name','<>','RP')
			->where('c.name','<>','DI')
			->orderBy('name','ASC')
			->distinct()
			->get()
			->toArray();
				
		return $this->respond($data);
	}

}
