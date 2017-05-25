<?php

namespace App;

use Carbon\Carbon;
use App\Workingreport;
use Illuminate\Support\Facades\DB;

class WorkingreportRepository
{
    /**
     * Atributos por los que se puede filtar.
     *
     * @var array
     */
    protected $filters = [
        'name','validation','date'
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

    public function usersByProject($project)
    {
        return DB::table('projects')
            ->join('groups','projects.id','=','groups.project_id')
            ->join('group_user','groups.id','=','group_user.group_id')
            ->join('users','group_user.user_id','=','users.id')
            ->select(
                'users.id'
            ) 
            ->where('projects.name',$project)
            ->groupBy('users.id')
            ->get();
    }

    public function search(array $data = array(), $user_id, $admin, $pm, $pm_projects)
    {
        $data = array_only($data, $this->filters);
        $data = array_filter($data, 'strlen');

        $q = $this->getModel()
            ->join('users','working_report.user_id','=','users.id')
            ->select(
                DB::raw("CONCAT(users.name, ' ', users.lastname_1 ) as fullname"),
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

        if(! $admin && ! $pm) {
            $q = $q->where('user_id',$user_id);
        }
        if(! $admin && $pm){
            //$users = [1,2,3,$user_id];
            foreach ($pm_projects as $project) {
                $users = $this->usersByProject($project);  
                $users = array_pluck($users, 'id');   
            }
                        
            $q = $q->whereIn('user_id',$users);
        }
       
        return $q->get();
    }

    /**
     * Filtro por nombre
     * 
     * @param  $q
     * @param  $value
     */
    public function filterByName($q, $value)
    {
        $q->where(DB::raw("CONCAT(users.name, ' ', users.lastname_1 )"), 'LIKE', "%{$value}%");
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
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $day = Carbon::now()->day;
        $weekOfYear = Carbon::now()->weekOfYear;
        $dayOfWeek = Carbon::now()->dayOfWeek;

        if ($value == config('options.periods')[0]){
            $startDate = Carbon::now()->addDays(-1);// Today
        } 
        if ($value == config('options.periods')[1]){
            $startDate = Carbon::now()->startOfWeek();// This week
            $endDate = Carbon::now()->endOfWeek();
        } 
        elseif ($value == config('options.periods')[2]){
            $startDate = Carbon::now()->subWeeks(1)->startOfWeek();// Last week
            $endDate = Carbon::now()->subWeeks(1)->endOfWeek();
        }    
        elseif ($value == config('options.periods')[3]) {
            $startDate = Carbon::create($year, $month, 1, 0, 0, 0);// This month
            $endDate = Carbon::now();
        }
        elseif ($value == config('options.periods')[4]) {
            $startDate = Carbon::now()->subMonths(1)->startOfMonth();// Last month
            $startDate = Carbon::now()->subMonths(1)->endOfMonth();
        }
        elseif ($value == config('options.periods')[5]) {
            $startDate = Carbon::create($year, 1, 1, 0, 0, 0);//This year
            $endDate = Carbon::now();
        }
        else {
            return;
        }

        $q->whereBetween('working_report.created_at', [$startDate,$endDate]);
    }
}