<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Project;
use App\User;
use App\WorkingreportRepository;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HolidaysValidationController extends ApiController
{
    protected $reportRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WorkingreportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
     * [loadconflicts description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function loadconflicts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year'    => 'required|numeric|between:2017,2030',
            'week'    => 'required|numeric|between:1,53',
            'user_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->respondNotAcceptable($validator->errors()->all());
        }

        if ($request['project'] != "") {
            if ($request['group'] != "") {
                //Proyectos y Grupos
                return $this->respond(
                    $this->reportRepository->formatOutput(
                        $this->getConflicts(
                            $request['year'],
                            $request['week'],
                            $this->getUsersByProjectName($request['project'],$request['group'])
                        )
                    )
                );
            }

            //Proyectos
            return $this->respond(
                $this->reportRepository->formatOutput(
                    $this->getConflicts(
                        $request['year'],
                        $request['week'],
                        $this->getUsersByProjectName($request['project'])
                    )
                )
            );
        }
       
       //Sin Proyectos o Grupos
        return $this->respond(
            $this->reportRepository->formatOutput(
                $this->getConflicts(
                    $request['year'],
                    $request['week'],
                    $this->getUsersInGroups($this->getGroups($request['user_id'] , true))
                )
            )
        );
    }

    /**
     * [loadusers description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
	public function loadusers(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'    => 'required|numeric|between:2017,2030',
			//'week'    => 'required|numeric|between:1,53',
			//'user_id' => 'required|numeric'
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->respond(
			//$this->getDaysPerUser($request['year'], $request['user_id'], $request['week'])//Holidays
            $this->getDaysPerUser($request['year'])//Holidays
		);
	}

    /**
     * [loadgroups description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function loadgroups(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->respondNotAcceptable($validator->errors()->all());
        }

        return $this->respond(
            $this->getGroups($request['user'])
        );
    }

    /**
     * [loadholidays description]
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
	 * Get requested holidays in a concrete week and year
	 * @param  [int] $user_id [description]
	 * @param  [int] $year    [description]
	 * @param  [int] $week    [description]
	 * @return [array]          [description]
	 */
	private function getDaysPerUser($year, $user_id = 0, $week = 0)
    {  
        $q = DB::table('calendar_holidays as c')
            ->select(
                'u.id as user_id',
                DB::raw("CONCAT(u.name, ' ', u.lastname ) as user_name"),
                DB::raw('GROUP_CONCAT(DISTINCT week(date) SEPARATOR "-") as weekdate'),
                DB::raw('count(validated) as count')
            )
            ->join('users as u','u.id','c.user_id')
            ->where('validated', 0)//Sin validar
            ->where(DB::raw("YEAR(c.date)"),$year)//Año seleccionado
            ->groupby('user_id')
            ->orderby('u.name');

        if (Auth::user()->primaryRole() == 'manager'){
            $users = $this->getUsersInGroups(
                $this->getGroups(Auth::user()->id , true)
            );
            $q = $q->whereIn('user_id',$users);
        }

        return $q = $q->get();   	
    }

    /**
     * Get the bankHolidays for a year
     * @param  [int] $year [description]
     * @return [array]       [description]
     */
    private function bankHolidays($year, $week = 0, $user_id = 0 )
    {	
    	if ($user_id != 0){
            $codes = $this->getCodes($user_id);
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
    private function getConflicts($year, $week, $users)
    {
    	return DB::table('working_report as wr')
    		->join('users as u','u.id','wr.user_id')
            ->LeftJoin('users as manager', 'wr.manager_id','=','manager.id')
            ->leftJoin('absences', 'wr.absence_id','=','absences.id')
    		->select(
    			'wr.user_id',
    			DB::raw("CONCAT(u.name, ' ', u.lastname ) as user_name"),
                DB::raw("manager.name as manager"),
    			'wr.created_at',
                DB::raw("null as project"),
                DB::raw('wr.training_type as training_type'),
                DB::raw('absences.name as absence'),
    			DB::raw('SUM(wr.time_slots* 0.25) as time_slot'),
    			'wr.pm_validation',
    			'wr.admin_validation'          
    		)
    		->whereIn('wr.user_id',$users)//Usuarios del mismo grupo/proyecto
    		->where('wr.activity','<>','project')//Ausencias o Cursos
            ->where(DB::raw("YEAR(wr.created_at)"),$year)//Año
            ->where(DB::raw("WEEK(wr.created_at)"),$week)//Semana
            ->groupBy(['user_id', 'created_at', 'training_type', 'absence_id', 'wr.pm_validation', 'wr.admin_validation', 'manager.name'])
            ->orderBy('wr.created_at', 'ASC')
            ->orderBy('wr.user_id', 'ASC')
    		->get();
    }

	/**
	 * Get groups and projects for a specific user
	 *
	 * @param  array $user_id
	 * @param  boolean $groups
	 * @return array / object
	 */
    private function getGroups($user_id = 0 , $ids = false)
    {
    	$q = DB::table('group_user as gu')
    		->join('groups as g','g.id','gu.group_id')
    		->join('projects as p','p.id','g.project_id')
    		->select(
    			'gu.group_id as group_id',
    			'g.name as group',
    			'p.id as project_id',
    			'p.name as project'
    		)
    		->where('g.enabled',1)//Grupo habilitado
    		->where('p.end_date',null);//Proyecto abierto
    		
            if($user_id != 0){
               $q = $q->where('user_id',$user_id);//Grupos-proyectos del usuario
            }

    		$q = $q->get();

            if($ids == true){
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

    private function getUsersByProjectName($project_name, $group_name = "")
    {
        $q = DB::table('group_user as gu')
            ->select('user_id')
            ->LeftJoin('groups as g','gu.group_id','g.id')
            ->Join('projects as p','g.project_id','p.id')
            ->where('p.name',$project_name);

        if($group_name != ""){
            $q = $q->where('g.name',$group_name);
        }   
        
        $array = $q->get();

        return array_pluck($array,'user_id');
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