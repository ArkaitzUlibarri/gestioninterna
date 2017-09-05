<?php

namespace App;

use App\Workingreport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkingreportRepository
{
    /**
     * Atributos por los que se puede filtar.
     *
     * @var array
     */
    protected $filters = [
        'name', 'validation', 'date', 'project'
    ];

	/**
	 * Devuelve una instancia del modelo del repositorio.
	 * 
	 * @return User
	 */
	public function getModel()
	{
		return new Workingreport;
	}

    public function search(array $data = array(), $user_id, $projects , $paginate = false)
    {
        $data = array_only($data, $this->filters);
        $data = array_filter($data, 'strlen');

        $q = $this->getModel()
            ->join('users','working_report.user_id','=','users.id')
            ->select(
                DB::raw("CONCAT(users.name, ' ', users.lastname ) as fullname"),
                'working_report.created_at',
                'working_report.user_id',
                DB::raw("sum(time_slots)*0.25 as horas_reportadas"),
                DB::raw("SUM(case when pm_validation = 0 then time_slots else 0 end)*0.25 as horas_validadas_pm"),
                DB::raw("SUM(case when admin_validation = 0 then time_slots else 0 end)*0.25 as horas_validadas_admin")
            )
            ->where('working_report.created_at','<',Carbon::tomorrow('Europe/London'))//Fecha menor que mañana
            ->groupBy('working_report.user_id','working_report.created_at')
            ->orderBy('working_report.created_at','desc')
            ->orderBy('fullname','asc');

        foreach ($data as $field => $value) {
            $filterMethod = 'filterBy' . studly_case($field);
            if(method_exists(get_called_class(), $filterMethod)) {
                $this->$filterMethod($q, $value);
            }
        }

        // Admin or Project Manager
        if (Auth::user()->primaryRole() == 'manager' || Auth::user()->primaryRole() == 'admin') {
            if ($data != []) {

                if (isset($data['project'])) {
                    if($data['project'] =="user"){
                        return $paginate ? $q->where('user_id', $user_id)->paginate(20)->appends($data) : $q->where('user_id', $user_id)->get();
                    }
                    else{
                        if (Auth::user()->primaryRole() == 'manager') {
                            //Project Manager
                            $users = $data['project'] == "all"
                                ? $this->usersByProjects(array_keys($projects))
                                : $this->usersByProjects([$data['project']]);
                        }
                        else {
                            //Admin
                            $users = $data['project'] == "all"
                                ? $this->usersByProjects(array_pluck($projects, ['id']))
                                : $this->usersByProjects([$data['project']]);
                        }

                        return $paginate ? $q->whereIn('user_id', $users)->paginate(20)->appends($data) : $q->whereIn('user_id', $users)->get();
                    }
                }

            }
            else{
                //Por defecto todos los reportes
                $users = Auth::user()->primaryRole() == 'manager'
                    ? $this->usersByProjects(array_keys($projects))
                    : $this->usersByProjects(array_pluck($projects, ['id']));

                return $paginate ? $q->whereIn('user_id', $users)->paginate(20)->appends($data) : $q->whereIn('user_id', $users)->get();
            }
        }
        
        return $paginate ? $q->where('user_id', $user_id)->paginate(20)->appends($data) : $q->where('user_id', $user_id)->get();
    }

    /**
     * Get the users with active contracts which have to report to the given projects.
     * 
     * @param  array  $projects
     * @return array
     */
    protected function usersByProjects(array $projects = [])
    {
        $users = DB::table('group_user as gu')
            ->join('groups', 'groups.id','=','gu.group_id')
            ->join('users', 'gu.user_id','=','users.id')
            ->join('contracts', 'users.id','=','contracts.user_id')
            ->whereIn('groups.project_id', $projects)
            ->where('groups.enabled', true)
            //->where('projects.end_date', null)
            ->whereNull('contracts.end_date')
            ->groupBy('groups.project_id', 'users.id')
            ->select('users.id')
            ->get()
            ->toArray();

        return array_pluck($users, 'id'); 
    }

    /**
     * Get the users with active contracts to a requested group and/or project
     * 
     * @param  array  $projects
     * @return array
     */
    protected function usersByGroupProject( $project , $group = "")
    {
        $type = $group == "" ? '<>' : '=';

        $users = DB::table('group_user as gu')
                    ->join('groups as g','gu.group_id','g.id')
                    ->join('projects as p','g.project_id','p.id')
                    ->join('users as u', 'gu.user_id','=','u.id')
                    ->join('contracts as c', 'u.id','=','c.user_id')
                    ->select('gu.user_id')
                    ->where('g.name',$type ,$group)//Nombre del grupo
                    ->where('p.name',$project)//Nombre del proyecto
                    ->where('g.enabled', true)//Grupos habilitadps
                    ->whereNull('c.end_date')//Contrato de los usuarios activo
                    ->get();

        return array_pluck($users, 'user_id'); 
    }

    /**
     * Filtro por User
     * 
     * @param  $q
     * @param  $value
     */
    public function filterByUser($q, $value)
    {
        $q = $q->where('user_id',$value);
    }

    /**
     * Filtro por nombre
     * 
     * @param  $q
     * @param  $value
     */
    public function filterByName($q, $value)
    {
        $q->where(DB::raw("CONCAT(users.name, ' ', users.lastname )"), 'LIKE', "%{$value}%");
    }

    /**
     * Filtro por validacion
     * 
     * @param  $q
     * @param  $value
     */
    public function filterByValidation($q, $value)
    {    
        if ($value == config('options.validations')[0]){
            $q->having('horas_validadas_admin','=', 0);// validated
        } 
        elseif ($value == config('options.validations')[1]){
            $q->having('horas_validadas_admin','<>', 0);// not validated
        }   
    }

    /**
     * Filtro por fecha
     * 
     * @param  $q
     * @param  $value
     */
    public function filterByDate($q, $value)
    {
        if ($value == config('options.periods')[0]){
            // Today
            $Date = Carbon::today();
            $q->whereDate('working_report.created_at', $Date);
            return;
        } 
        if ($value == config('options.periods')[1]){
            // Yesterday
            $Date = Carbon::yesterday();
            $q->whereDate('working_report.created_at', $Date);
            return;
        } 
        if ($value == config('options.periods')[2]){
            // This week
            $startDate = Carbon::today()->startOfWeek();
            $endDate   = Carbon::today()->endOfWeek();
        } 
        elseif ($value == config('options.periods')[3]){
            // Last week
            $startDate = Carbon::today()->subWeeks(1)->startOfWeek();
            $endDate   = Carbon::today()->subWeeks(1)->endOfWeek();
        }    
        elseif ($value == config('options.periods')[4]) {
            // This month
            $startDate = Carbon::today()->startOfMonth();
            $endDate   = Carbon::today()->endOfMonth();
        }
        elseif ($value == config('options.periods')[5]) {
            // Last month
            $startDate = Carbon::now()->subMonths(1)->startOfMonth();
            $endDate   = Carbon::now()->subMonths(1)->endOfMonth();
        }
        elseif ($value == config('options.periods')[6]) {
            //This year
            $startDate = Carbon::today()->startOfYear();
            $endDate   = Carbon::today()->endOfYear();
        }
        elseif ($value == config('options.periods')[7]) {
            //Last year
            $startDate = Carbon::today()->subYears(1)->startOfYear();
            $endDate   = Carbon::today()->subYears(1)->endOfYear();
        }
        else {
            return;
        }

        $q->whereBetween('working_report.created_at', [$startDate,$endDate]);
    }

    /**
     * Fetch data for validation view.
     * 
     * @param  array
     * @return array
     */
    public function fetchData($data = [])
    {
        $data = array_only($data, ['year', 'week', 'name','group','project']);
        $data = array_filter($data, 'strlen');//Removes null,false and empty strings

        $q = DB::table('working_report as wr')
            ->join('users as users', 'wr.user_id','=','users.id')
            ->leftJoin('users as manager', 'wr.manager_id','=','manager.id')
            ->leftJoin('projects', 'wr.project_id','=','projects.id')
            ->leftJoin('groups', 'wr.group_id','=','groups.id')
            ->leftJoin('absences', 'wr.absence_id','=','absences.id')
            ->select(
                'wr.user_id',
                DB::raw("CONCAT(users.name, ' ', users.lastname ) as user_name"),
                DB::raw("manager.name as manager"),
                'wr.created_at',
                DB::raw('projects.name as project'),
                DB::raw('wr.training_type as training_type'),
                DB::raw('absences.name as absence'),
                DB::raw('SUM(wr.time_slots* 0.25) as time_slot'),
                'wr.pm_validation',
                'wr.admin_validation'
            )
            ->groupBy(['user_id', 'created_at', 'wr.project_id', 'training_type', 'absence_id', 'wr.pm_validation', 'wr.admin_validation', 'manager.name'])
            ->orderBy('wr.created_at', 'ASC')
            ->orderBy('wr.user_id', 'ASC');

        //Group-Project
        if(isset($data['group']) && isset($data['project'])) {
            $users = $this->usersByGroupProject($data['project'],$data['group']);
            $q->whereIn('wr.user_id',$users);
        }
        else if (isset($data['project'])) {
            $users = $this->usersByGroupProject($data['project']);
            $q->whereIn('wr.user_id',$users);
        }
        else{
            if (Auth::user()->primaryRole() == 'manager') {
                $q->whereIn('wr.user_id', $this->usersByProjects(
                    array_keys(Auth::user()->activeProjects())
                ));
            }
            else if (Auth::user()->primaryRole() == 'user' || 
                     Auth::user()->primaryRole() == 'tools') {
                $q->where('wr.user_id', Auth::user()->id);
            }
        }

        //Year - Week
        if (isset($data['year'])) {
             $q->whereRaw("YEAR(wr.created_at) = {$data['year']}");
             $q->whereRaw("WEEK(wr.created_at, 1) = {$data['week']}");
        }
        else {
            $q->where('wr.created_at', '>=', Carbon::now('Europe/Madrid')->subMonth(2));//Ultimos dos meses
            $q->where('wr.created_at', '<', Carbon::tomorrow('Europe/Madrid'));//Menor que mañana
        }

        //Name
        if (isset($data['name'])) {
            $q->whereRaw("CONCAT(users.name, ' ', users.lastname ) LIKE '%{$data['name']}%'");
        }

        return $q->get();
    }

    /**
     * Format data.
     * 
     * @param  array
     * @return array
     */
    public function formatOutput($data)
    {
        if (count($data)==0) {
            return null;
        }

        $response = array();

        foreach ($data as $value) {
            $key = $value->user_id . '|' . $value->created_at;

            if(! isset($response[$key])) {
                $response[$key] = [
                    'user_id' => $value->user_id,
                    'user_name' =>ucwords($value->user_name),
                    'manager' => ucwords($value->manager),
                    'created_at' => $value->created_at,
                    'total' => 0,
                    'pm_validation' => $value->pm_validation,
                    'admin_validation' => $value->admin_validation,
                    'items' => []
                ];
            }

            $response[$key]['total'] += $value->time_slot;

            $response[$key]['items'][] = [
                'name' => strtoupper($this->activity($value)),
                'time_slot' => $value->time_slot
            ];
        }

        return $response;
    }

    /**
     * Get activity.
     * 
     * @param  $data
     * @return string
     */
    public function activity($data)
    {
        foreach (['project', 'absence', 'training_type'] as $activity) {
            if($data->$activity != null) {
                return $data->$activity;
            }
        }
    }

    /**
     * Fetch data for validation view.
     * 
     * @param  array
     * @return array
     */
    public function fetchMonthlyData()
    {
         $q = DB::table('working_report as wr')
            ->join('users as users', 'wr.user_id','=','users.id')
            ->leftJoin('projects', 'wr.project_id','=','projects.id')
            ->leftJoin('groups', 'wr.group_id','=','groups.id')
            ->leftJoin('absences', 'wr.absence_id','=','absences.id')
            ->select(
                'wr.user_id',
                DB::raw("CONCAT(users.name, ' ', users.lastname ) as user_name"),
                DB::raw('MONTH(wr.created_at) as month'),
                DB::raw('projects.name as project'),
                DB::raw('wr.training_type as training_type'),
                DB::raw('absences.name as absence'),
                DB::raw('SUM(wr.time_slots* 0.25) as time_slot')
            )
            ->whereYear('wr.created_at', Carbon::today()->year)
            ->whereDate('wr.created_at', '<=', Carbon::today())
            ->groupBy(['user_id', 'month', 'wr.project_id', 'training_type', 'absence_id'])
            ->orderBy('users.name', 'ASC')
            ->orderBy('month', 'ASC');

        if (Auth::user()->primaryRole() == 'manager') {
            $q->whereIn('wr.user_id', $this->usersByProjects(
                array_keys(Auth::user()->activeProjects())
            ));
        }
        else if (Auth::user()->primaryRole() == 'user' || Auth::user()->primaryRole() == 'tools') {
            $q->where('wr.user_id', Auth::user()->id);
        }

        return $q->get();
    }

    /**
     * Format data.
     * 
     * @param  array
     * @return array
     */
    public function formatMonthlyOutput($data)
    {
        if (count($data)==0) {
            return null;
        }

        $response = array();

        foreach ($data as $value) {
            $response [] = [
                'user_id' => $value->user_id,
                'user_name' =>ucwords($value->user_name),
                'month' => ucfirst($value->month),
                'name' => strtoupper($this->activity($value)),
                'time_slot' => $value->time_slot
            ];
        }

        return $response;
    }

}