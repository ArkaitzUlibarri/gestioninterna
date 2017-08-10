<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Project;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HolidaysValidationController extends ApiController
{
	/**
	 * [index description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function index(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'    => 'required|numeric|between:2017,2030',
			'week'    => 'required|numeric|between:1,53',
			'user_id' => 'required|numeric'
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->respond(
			$this->getDaysPerUser($request['user_id'],$request['year'],$request['week'])//Holidays
			//$this->getConflicts($this->getUsersInGroups($this->getGroups($request['user_id'] , true)))//Reports and planification for users in the same project
		);
	}

	/**
	 * [index description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function loadholidays(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'    => 'required|numeric|between:2017,2030',
			//'week'    => 'required|numeric|between:1,53',
			//'user_id' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->respond(
			//$this->bankHolidays($request['year'], $request['week'], $request['user_id'])
			$this->bankHolidays($request['year'])
		);
	}

	/**
	 * [index description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function loadweeks(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year' => 'required|numeric|between:2017,2030',
			'user_id' => 'required|numeric'
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->respond(
			$this->getHolidaysWeeks($request['user_id'],$request['year'])
		);
	}

	public function loadfilters(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'required|numeric'
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->respond(
			$this->getGroups($request['user_id'],false)
		);
	}

	/**
	 * Get requested holidays in a concrete week and year
	 * @param  [int] $user_id [description]
	 * @param  [int] $year    [description]
	 * @param  [int] $week    [description]
	 * @return [array]          [description]
	 */
	private function getDaysPerUser($user_id, $year, $week)
    {

    	$users = $this->getUsersInGroups(
    		$this->getGroups($user_id , true)
    	);

    	return DB::table('calendar_holidays as c')
    		->join('users as u','u.id','c.user_id')
    		->select(
    			'c.user_id',
    			DB::raw("CONCAT(u.name, ' ', u.lastname ) as user_name"),
    			'c.date',
    			DB::raw('week(c.date) as weekdate'),
    			'c.type as name',
    			DB::raw("'holidays' as type"),
    			'c.validated'
    		)
    		//->where('user_id',$user_id)//Días relativos al usuario
    		->whereIn('c.user_id',$users)
    		->where(DB::raw("YEAR(c.date)"),$year)
    		->where(DB::raw("WEEK(c.date)"),$week)
    		//->where('validated', 0)//Sin validar
    		->get();		 	
    }

    /**
     * Get the bankHolidays for a year
     * @param  [int] $year [description]
     * @return [array]       [description]
     */
    private function bankHolidays($year, $week = 0, $user_id = 0 )
    {	
    	if ($user_id != 0){

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

    		$codes = array_pluck($codes,'codes');
    	}

    	return DB::table('bank_holidays as bh')
    		->join('bank_holidays_codes as bhc','bh.code_id','bhc.id')
    		->select(
    			'bh.date',
    			DB::raw('week(date) as weekdate'),
    			//'bh.code_id',
    			'bhc.name',
    			'bhc.type'
    			//DB::raw("null as validated")			
    		)
    		->where(DB::raw("YEAR(bh.date)"),$year)//Año de los festivos
    		//->where(DB::raw("WEEK(date)"),$week)//Semana de los festivos
    		//->whereIn('bh.code_id', $codes)
    		->orderby('bh.date','asc')
    		->get();
    }

    /**
     * Get conflictive reports for users in the same project as the solicitor
     * @param  [array] $users [description]
     * @return [array]        [description]
     */
    private function getConflicts($users)
    {
    	return DB::table('working_report as wr')
    		->join('users as u','u.id','wr.user_id')
    		->LeftJoin('users as um','um.id','wr.manager_id')
    		->select(
    			'wr.user_id',
    			DB::raw("CONCAT(u.name, ' ', u.lastname ) as user_name"),
    			'wr.created_at',
    			'wr.activity',
    			DB::raw("(wr.time_slots)*0.25 as hours"),
    			'wr.pm_validation',
    			'wr.admin_validation',
    			'wr.manager_id',
    			DB::raw("CONCAT(um.name, ' ', um.lastname ) as manager_name")
    		)
    		->whereIn('wr.user_id',$users)//Usuarios del mismo grupo/proyecto
    		->where('wr.activity','<>','project')//Ausencias o Cursos
    		->get();
    }

    /**
     * Get the user holidays weeks
     * @param  [int] $year [description]
     * @return [array]       [description]
     */
    private function getHolidaysWeeks($user_id, $year)
    {
    	return DB::table('calendar_holidays')
    		->select(
    			DB::raw('week(date) as weekdate')
    		)
    		->where('user_id',$user_id)//Días relativos al usuario
    		->where(DB::raw("YEAR(date)"),$year)
    		->where('validated', 0)//Sin validar
    		->distinct()
    		->get();	
    }

	/**
	 * Get groups and projects for a specific user
	 *
	 * @param  array $user_id
	 * @param  boolean $groups
	 * @return array / object
	 */
    private function getGroups($user_id , $group)
    {
    	$q = DB::table('group_user as gu')
    		->join('groups as g','g.id','gu.group_id')
    		->join('projects as p','p.id','g.project_id')
    		->select(
    			'gu.group_id as group_id',
    			'g.name as group_name',
    			'p.id as project_id',
    			'p.name as project_name'
    		)
    		->where('user_id',$user_id)//Grupos-proyectos del usuario
    		->where('g.enabled',1)//Grupo habilitado
    		->where('p.end_date',null)//Proyecto abierto
    		->get();

    		if($group == true){
    			return array_pluck($q, 'group_id');
    		}

    		return $q;
    }

	/**
	 * Get users in an array of groups
	 * 
	 * @param  array $groups
	 * @return array
	 */
    private function getUsersInGroups($groups)
    {
    	$array =  DB::table('group_user as gu')
    		->select('user_id')
    		->whereIn('group_id',$groups)
    		->distinct()
    		->get();

    	return array_pluck($array,'user_id');
    }

    /**
	 * Get users holidays information
	 * 
	 * @param  array $users
	 * @return array
	 */
	/*
    private function getUsersCards($users)
    {
    	return DB::table('user_holidays as uh')
    		->join('contracts as c','c.id','uh.contract_id')
    		->select(
    			'current_year',
    			'used_current_year',
    			'last_year',
    			'used_last_year',
    			'extras',
    			'used_extras',
    			'used_next_year'
    		)
    		->whereIn('c.user_id',$users)
    		->where('c.end_date',null)
    		->get();
    }
    */
}