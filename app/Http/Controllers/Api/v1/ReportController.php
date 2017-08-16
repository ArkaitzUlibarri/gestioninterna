<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\ReportApiRequest;
use App\Workingreport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\User;

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

		$data = DB::table('working_report')
			->where('user_id', $request->get('user_id'))
			->where('created_at', $request->get('created_at'))
			->leftJoin('projects', 'working_report.project_id','=','projects.id')
			->leftJoin('absences', 'working_report.absence_id','=','absences.id')
			->leftJoin('groups', 'working_report.group_id','=','groups.id')
			->leftJoin('categories', 'working_report.category_id','=','categories.id')
			->select(
				'working_report.id',
				'working_report.user_id',
				'working_report.created_at',
				'working_report.activity',
				'working_report.project_id',
				'projects.name as project',
				'working_report.group_id',
				'groups.name as group',
				'working_report.category_id',
				'categories.description as category',
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
	 * @param  ReportApiRequest $request
	 * @return json
	 */
	public function store(ReportApiRequest $request)
	{

		$array = $request->all();

		unset($array['id']);
		unset($array['absence']);
		unset($array['category']);
		unset($array['group']);
		unset($array['project']);
		unset($array['time']);

		//No poder reportar ausencia en días festivos
		if(count($this->getBankHolidays($request['user_id'],$request['created_at'])) && $request['activity'] !='project'){
			return $this->respondNotAcceptable("You cannot report absence on a Bank Holiday");
		}

		$id = DB::table('working_report')
			->insertGetId($array);

		return $this->respond($id);

	}

	/**
	 * Actualizo un reporte de trabajo
	 * 
	 * @param  ReportApiRequest $request
	 * @param  $id
	 * @return json
	 */
	public function update(ReportApiRequest $request, $id)
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

		//No poder reportar ausencia en días festivos
		if(count($this->getBankHolidays($request['user_id'],$request['created_at'])) && $request['activity'] !='project'){
			return $this->respondNotAcceptable("You cannot report absence on a Bank Holiday");
		}

		$confirmation = DB::table('working_report')
			->where('id',$id)
			->update($array);

		return $this->respond("Updated");

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

		return $this->respond("Deleted");
	}

	/**
	 * Extraigo el ultimo reporte
	 * 
	 * @param  $id
	 */
	public function last(Request $request)
	{
		//Validacion
		$validator = Validator::make($request->all(), [
			'user_id'    => 'required|numeric',
			'created_at' => 'required|date',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		$teleworking = $this->teleworking( $request['created_at'],$request['user_id']);
		$reportableGroups = $this->reportingGroups($request['user_id']);
		$date = $this-> lastReportDate($request['created_at'], $request['user_id']);

		//Seleccion de los datos
		$results = DB::table('working_report')
			->select(
				DB::raw(
					"user_id,
					'" . $date . "' as created_at, 
					activity, 
					project_id, 
					group_id, 
					category_id,
					training_type, 
					course_group_id, 
					absence_id, 
					time_slots,
					CASE activity  
					  when 'project' then '". $teleworking ."' 
					  when 'absence' then null  
					  when 'training' then 'on site work'  
					end as job_type"
				))
			->where('user_id',$request['user_id'])
			->where('created_at',$date);

		$results = $results->get()->toArray();

		$dataset = [];
		foreach ($results as $row) {
			$dataset[] = [
				'user_id'         => $row->user_id,
				'created_at'      => $request['created_at'],
				'activity'        => $row->activity,
				'project_id'      => $row->project_id,
				'group_id'        => $row->group_id,
				'category_id'     => $row->category_id,
				'training_type'   => $row->training_type,
				'course_group_id' => $row->course_group_id,
				'absence_id'      => $row->absence_id,
				'time_slots'      => $row->time_slots,
				'job_type'        => $row->job_type
			];
		}
	
		//Filtrar reportes por aquellos que estan dentro de mi grupo actual
		$filtered_once = array_filter($dataset, function($item) use ($reportableGroups){
			if( (in_array($item['group_id'], $reportableGroups) || ($item['activity'] == 'training') || ($item['activity'] == 'absence'))){
				return true;
			}
		});

		//Filtrar para no poder reportar ausencia o curso en festivos
		$filtered = array_filter($filtered_once, function($item) use ($request){
			if(count($this->getBankHolidays($request['user_id'],$request['created_at'])) == 0 || 
				count($this->getBankHolidays($request['user_id'],$request['created_at'])) > 0 && $item['activity'] =='project'){
				return true;
			}
		});
		
		Workingreport::insert($filtered);

		return  $this->respond("COPIED");
	}

	private function teleworking($date,$user_id)
	{
		//Obtener dia
		$dayOfWeek = Carbon::createFromFormat('Y-m-d', $date)->dayOfWeek;
		$dayOfWeek = $dayOfWeek == 0 ? 7 : $dayOfWeek;//Convertir Domingo
		$day = config('options.daysWeek')[$dayOfWeek - 1];

		$user = User::find($user_id);
		
		if ($user->hasTeleworking()) {
			$data = $user->contracts->where('end_date',null)->first()->teleworking->where('end_date',null)->first();
			return $data[$day] ? "teleworking": "on site work";
		}

		return "on site work";
	}

	private function reportingGroups($user_id)
	{
		$ids = array();

		$user = User::find($user_id);
		$groups = $user->groups;

	    foreach ($groups as $group) {
	    	if( $group->enabled){
	    		$ids [] = $group->id;  
	    	}       
        }

		return $ids;
	}

	/**
	 * Fecha último reporte
	 * 
	 * @param  $user_id
	 * @param  $created-at
	 * @return string
	 */
	private function lastReportDate ($created_at,$user_id)
	{
		$q = DB::table('working_report')
			->select('created_at')
			->where('created_at','<',$created_at)
			->where('user_id',$user_id)
			->orderBy('created_at','desc');

		if(is_null($q->first())){
			return null;
		}
		
		return $q->first()->created_at;
	}

	/**
	 * Get the bankholidays for a user and filter by the report date
	 * @param  [type] $year    [description]
	 * @param  [type] $user_id [description]
	 * @return [type]          [description]
	 */
	private function getBankHolidays($user_id, $date)
	{
		$codes = $this->getCodes($user_id);

		return DB::table('bank_holidays as bh')
    		->join('bank_holidays_codes as bhc','bh.code_id','bhc.id')
    		->select(
    			'bh.date',
    			DB::raw('week(date) as weekdate'),
    			'bhc.name',
    			'bhc.type'		
    		)
    		->whereDate('bh.date',$date)
    		->whereIn('bh.code_id',$codes)
    		->orderby('bh.date','asc')
    		->get();
	}

	/**
	 * BankHolidays Codes asociated to a user
	 * @param  [type] $user_id [description]
	 * @return [type]          [description]
	 */
	private function getCodes($user_id)
	{
		$codes =  DB::select(	    		
				"select national_days_id as codes
				from contracts
				WHERE end_date is null and user_id= :a1

				union all

			    select regional_days_id as codes
			    from contracts
			    WHERE end_date is null and user_id= :a2

			    union all

			    select local_days_id as codes
			    from contracts
			    WHERE end_date is null and user_id= :a3"
				
				,array(
					'a1' => $user_id,
					'a2' => $user_id,
					'a3' => $user_id
				)
			);

    	return array_pluck($codes,'codes');
	}
}
