<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\ReportFormApiRequest;
use App\Workingreport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportController extends ApiController
{

	/**
	 * 
	 * 
	 * @return json
	 */
	public function index(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id'    => 'required|numeric',
			'created_at' => 'required|date',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		$data=DB::table('working_report')
			->where('user_id', $request->get('user_id'))
			->where('created_at', $request->get('created_at'))
			->leftJoin('projects', 'working_report.project_id','=','projects.id')
			->leftJoin('absences', 'working_report.absence_id','=','absences.id')
			->leftJoin('groups', 'working_report.group_id','=','groups.id')
			->select(
				'working_report.id',
				'working_report.user_id',
				'working_report.created_at',
				'working_report.activity',
				'working_report.project_id',
				'projects.name as project',
				'working_report.group_id',
				'groups.name as group',
				'working_report.training_type',
				'working_report.course_group_id',
				'working_report.absence_id',
				'absences.name as absence',
				'working_report.time_slots',
				'working_report.job_type',
				'working_report.comments',
				'working_report.pm_validation',
				'working_report.admin_validation'
			)
			->orderBy('created_at','id','asc')
			->get()
			->toArray();
				
		return $this->respond($data);

	}

	/**
	 * Creo un reporte de trabajo
	 * 
	 * @param  ReportFormApiRequest $request
	 * @return json
	 */
	public function store(ReportFormApiRequest $request)
	{

		$array = $request->all();

		unset($array['id']);
		unset($array['absence']);
		unset($array['category']);
		unset($array['group']);
		unset($array['project']);
		unset($array['time']);

		$id = DB::table('working_report')
			->insertGetId($array);

		return $this->respond($id);

	}

	/**
	 * Actualizo un reporte de trabajo
	 * 
	 * @param  ReportFormApiRequest $request
	 * @param  $id
	 * @return json
	 */
	public function update(ReportFormApiRequest $request, $id)
	{

		$array = $request->all();

		unset($array['absence']);
		unset($array['category']);
		unset($array['group']);
		unset($array['project']);
		unset($array['time']);

		$report = Workingreport::find($id);

		if($report == null) {
			return $this->respondNotFound();
		}

		$confirmation = DB::table('working_report')
			->where('id',$id)
			->update($array);

		return $this->respond($confirmation);

	}

	/**
	 * Elimino un reporte de trabajo mediante el id.
	 * 
	 * @param  $id
	 */
	public function destroy($id)
	{
		$report = Workingreport::find($id);

		if($report == null) {
			return $this->respondNotFound();
		}

		$report = DB::table('working_report')
			->where('id',$id)
			->delete();

		return $this->respond();
	}


}
