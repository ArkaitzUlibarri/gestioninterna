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
            ->groupBy('user_id','created_at')
            ->orderBy('created_at','desc')
            ->orderBy('fullname','asc');

        foreach ($data as $field => $value) {
            $filterMethod = 'filterBy' . studly_case($field);
            if(method_exists(get_called_class(), $filterMethod)) {
                $this->$filterMethod($q, $value);
            }
        }

        if ($data != []) {
            // Admin or Project Manager
            if (Auth::user()->primaryRole() == 'manager' || Auth::user()->primaryRole() == 'admin') {

                if (isset($data['project'])) {

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

                    return $q->whereIn('user_id', $users)->get();
                }
            }
        }

        return $q->where('user_id', $user_id)->get();
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
        $data = array_only($data, ['year', 'week', 'name']);
        $data = array_filter($data, 'strlen');

        $q = DB::table('working_report as wr')
            ->join('users as users', 'wr.user_id','=','users.id')
            ->leftJoin('users as manager', 'wr.manager_id','=','manager.id')
            ->leftJoin('projects', 'wr.project_id','=','projects.id')
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

        if (Auth::user()->primaryRole() == 'manager') {
            $q->whereIn('wr.user_id', $this->usersByProjects(
                array_keys(Auth::user()->activeProjects())
            ));
        }
        else if (Auth::user()->primaryRole() == 'user' || 
                 Auth::user()->primaryRole() == 'tools') {
            $q->where('wr.user_id', Auth::user()->id);
        }

        if (isset($data['year'])) {
             $q->whereRaw("YEAR(wr.created_at) = {$data['year']}");
             $q->whereRaw("WEEK(wr.created_at, 1) = {$data['week']}");
        } else {
            $q->where('wr.created_at', '>=', Carbon::now('Europe/Madrid')->subMonth(2));
        }

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
                    'user_name' => $value->user_name,
                    'manager' => $value->manager,
                    'created_at' => $value->created_at,
                    'total' => 0,
                    'pm_validation' => $value->pm_validation,
                    'admin_validation' => $value->admin_validation,
                    'items' => []
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

}