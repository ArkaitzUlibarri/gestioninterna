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
	 * Get reports by related users
	 * 
	 * @param  Request $request
	 * @return json
	 */
	public function index(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year' => 'required|numeric|between:2017,2030',
			'week' => 'required|numeric|between:1,53',
			'user_id' => 'required|numeric'
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->respond(
			//$this->getDaysPerUser($request['user_id'],$request['year'],$request['week'])
			//$this->getGroups($request['user_id'])
			//$this->getConflicts(202)
			$this->bankHolidays($request['year'])
		);
	}

	private function getDaysPerUser($user_id, $year, $week)
    {
    	return DB::table('calendar_holidays')
    		->select(
    			'date',
    			DB::raw('week(date) as weekdate')
    		)
    		->where('user_id',$user_id)//Días relativos al usuario
    		->where(DB::raw("YEAR(date)"),$year)
    		->where(DB::raw("WEEK(date)"),$week)
    		->where('validated', 0)//Sin validar
    		->get();		 	
    }

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

    private function bankHolidays($year)
    {
    	return DB::table('bank_holidays as bh')
    		->join('bank_holidays_codes as bhc','bh.code_id','bhc.id')
    		->select(
    			'bh.date',
    			DB::raw('week(date) as weekdate'),
    			'bh.code_id',
    			'bhc.type'
    		)
    		->where(DB::raw("YEAR(bh.date)"),$year)//Año de los festivos
    		->orderby('bh.date','asc')
    		->get();
    }

    private function getGroups($user_id)
    {
    	return DB::table('group_user as gu')
    		->join('groups as g','g.id','gu.group_id')
    		->join('projects as p','p.id','g.project_id')
    		->select(
    			'gu.group_id as group_id',
    			'g.name as group_name',
    			'p.id as project_id',
    			'p.name as proyect_name'
    		)
    		->where('user_id',$user_id)//Grupos-proyectos del usuario
    		->where('g.enabled',1)//Grupo habilitado
    		->where('p.end_date',null)//Proyecto abierto
    		->get();
    }

}