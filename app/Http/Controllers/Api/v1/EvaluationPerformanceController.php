<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Performance;
use App\User;
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
			'year'     => 'required|numeric|between:2015,2030',
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

		$result = array();
		
		foreach ($data as $index => $array) {
			$key = $array->project_id;

			$group = [
				'group'    => $array->group,
				'group_id' => $array->group_id,
				'hours'    => $array->hours
			];

			if(! isset($result[$key])) {			
                $result[$key] = [
					'project_id' => $array->project_id,
					'project'    => $array->project,
					'hours'      => $array->hours
                ];
            }
            else{
            	$result[$key]['hours'] = $result[$key]['hours'] + $array->hours;
            }

            $result[$key]['groups'][] = $group;
		}
				
		return $this->respond($result);
	}

	public function loadEmployees(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'     => 'required|numeric|between:2015,2030',
			'month'    => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		//Reports by employees in an specific time
		$q = DB::table('working_report as wr')
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
			->whereNotNull('wr.group_id')
			->where('c.name','<>','RP')
			->where('c.name','<>','DI')
			->orderBy('name','ASC')
			->distinct();

		if(Auth::user()->primaryRole() == 'manager'){
			$projects = array_keys(Auth::user()->managerProjects());
			$users = $q->whereIn('wr.project_id',$projects)->get()->toArray();	
		}
		elseif(Auth::user()->primaryRole() == 'admin'){
			$users = $q->get()->toArray();
		}

		return $this->respond($users);
	}

	public function store(Request $request)
	{
		//Validacion
		$validator = Validator::make($request->all(), [
			'*.user_id'    => 'required|numeric',
			'*.project_id' => 'required|numeric',
			'*.code'       => 'required|string',
			'*.year'       => 'required|numeric|between:2015,2030',
			'*.month'      => 'required|numeric|between:1,12',
			'*.comment'    => 'nullable|string',
			'*.mark'       => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		//Array a Insertar
		for ($i = 0; $i <= count($request->all()) - 1; $i++) { 	
			$data[$i] = [
				'year'          => $request[$i]['year'],
				'month'         => $request[$i]['month'],
				'user_id'       => $request[$i]['user_id'],
				'project_id'    => $request[$i]['project_id'],
				'type'          => $request[$i]['code'],
				//'efficiency_id' => 9,
				'mark'          => $request[$i]['mark'],
				'comment'       => $request[$i]['comment'],
				'weight'		=> $request[$i]['weight'],
				'pm_id'         => Auth::user()->id
			];
		}

		//Transacción de inserción de datos
		DB::beginTransaction();

		try{
			foreach($data as $row)
			{
				//Insert
				$ids[] = DB::table('performances')->insertGetId($row);
			}
		
        	DB::commit();
		}
		catch(\Exception $e){
    		DB::rollback();
			return $this->respondInternalError($e);
		}

		return $this->respond($ids);	
	}

	public function destroy($ids)
	{
		$ids = explode(',', $ids);

		$performances = Performance::find($ids);

		if($performances == null) {
			return $this->respondNotFound();
		}

		Performance::destroy($ids);

		return $this->respond("DELETED");
	}

	/*
	public function store(Request $request)
	{

		//Validacion
		$validator = Validator::make($request->all(), [
			'*.user_id'    => 'required|numeric',
			'*.project_id' => 'required|numeric',
			'*.code'       => 'required|string',
			'*.year'       => 'required|numeric|between:2015,2030',
			'*.month'      => 'required|numeric|between:1,12',
			'*.comment'    => 'nullable|string',
			'*.mark'       => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		
		for ($i = count($request->all()) - 1; $i >= 0; $i--) { 	
			$request['pm_id'] = Auth::user()->id;

			if ($request[$i]['id'] == null) {
				// Insert
				Performances::create($request[$i]);
			}
			else {
				// Editar
				Performances::where('id', $request[$i]['id'])->update($request[$i]);
			}
		}
		
		//Array a Insertar
		for ($i = count($request->all()) - 1; $i >= 0; $i--) { 	
			$data[$i] = [
				'year'          => $request[$i]['year'],
				'month'         => $request[$i]['month'],
				'user_id'       => $request[$i]['user_id'],
				'project_id'    => $request[$i]['project_id'],
				'type'          => $request[$i]['code'],
				//'efficiency_id' => 9,
				'mark'          => $request[$i]['mark'],
				'comment'       => $request[$i]['comment'],
				'pm_id'         => Auth::user()->id
			];
		}

		//Mirar si ya hay datos insertados
		$evaluations = DB::table('performances')
			->select('id')
			->where('user_id',$request[0]['user_id'])
			->where('project_id',$request[0]['project_id'])
			->where('year',$request[0]['year'])
			->where('month',$request[0]['month'])
			->get()->toArray();

		if(count($evaluations) == count($request->all())){

			//Update
			DB::beginTransaction();

			try{

				for ($i = count($request->all()) - 1; $i >= 0; $i--) { 	
					$answer = DB::table('performances')
						->where('id', $evaluations[$i])
						->update([
							'mark'    => $request[$i]['mark'],
							'comment' => $request[$i]['comment'],
							'pm_id'   => Auth::user()->id
						]);
				}
			
	        	DB::commit();
			}
			catch(\Exception $e){
	    		DB::rollback();
    			return $this->respondInternalError($e->xdebug_message);
			}

	        return $this->respond("UPDATED");
		}
		else{
			//Insert
			$answer = DB::table('performances')->insert($data);

			if(! $answer){
				return $this->respondInternalError("ERROR SAVING");
			}

			return $this->respond("SAVED");
		}

	}
	*/

	public function loadProjectTable(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'     => 'required|numeric|between:2017,2030',
			'employee' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		$data = DB::table('performances')
			->select('id','project_id','type','month','mark','comment','weight')
			->where('user_id', $request->get('employee'))//Usuario
			->where('year',$request->get('year'))//Año
			->orderBy('type','ASC')
			->orderBy('month','ASC')
			->get();
		
		$result = array();
	
		foreach ($data as $index => $array) {
			$key = $array->project_id . '|' . $array->type . '|' . $array->month;

			if(! isset($result[$key])) {
                $result[$key] = [
					'id'      => $array->id,
					'mark'    => $array->mark,
					'comment' => ucfirst($array->comment),
					'weight'  => $array->weight
                ];
            }
		}
		
		return $this->respond($result);
	}
}