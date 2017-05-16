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

    public function search(array $data = array(), $user_id, $admin)
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

        if(! $admin) {
            $q = $q->where('user_id',$user_id);
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
        if ($value == config('options.periods')[0]){
            $startDate=Carbon::now()->addDays(-1);// -1 day
        } 
        elseif ($value == config('options.periods')[1]){
            $startDate=Carbon::now()->addWeeks(-1);// -1 week
        }    
        elseif ($value == config('options.periods')[2]) {
            $startDate=Carbon::now()->addWeeks(-2);// -2 week
        }
        elseif ($value == config('options.periods')[3]) {
            $startDate=Carbon::now()->addWeeks(-3);// -3 week
        }
        elseif ($value == config('options.periods')[4]) {
            $startDate=Carbon::now()->addMonths(-1);// -1 month
        }
        elseif ($value == config('options.periods')[5]) {
            $startDate=Carbon::now()->addMonths(-2);// -2 month
        }
        elseif ($value == config('options.periods')[6]) {
            $startDate=Carbon::now()->addYears(-1);// -1 year
        }
        else {
            return;
        }

        $endDate = Carbon::now();//now
        $q->whereBetween('working_report.created_at', [$startDate,$endDate]);
    }
}