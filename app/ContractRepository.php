<?php

namespace App;

use App\Contract;
use Illuminate\Support\Facades\DB;

class ContractRepository
{
    /**
     * Atributos por los que se puede filtar.
     *
     * @var array
     */
    protected $filters = [
        'name', 'status','contract',
    ];
	/**
	 * Devuelve una instancia del modelo del repositorio.
	 * 
	 * @return User
	 */
	public function getModel()
	{
		return new Contract;
	}

    public function search(array $data = array(), $paginate = false)
    {
        $data = array_only($data, $this->filters);
        $data = array_filter($data, 'strlen');

        $q = $this->getModel()
            ->join('contract_types','contracts.contract_type_id','=','contract_types.id')
            ->join('users','contracts.user_id','=','users.id')
            ->select(
                //'users.name as name',
                //'users.lastname as lastname',
                DB::raw("concat(users.name, ' ', users.lastname) as full_name"),
                'contracts.id',
                'contract_types.name as contract_types',    
                'contracts.start_date',
                'contracts.end_date',
                'contracts.estimated_end_date',
                'contracts.week_hours'
            )
            ->orderBy('full_name', 'asc');

        foreach ($data as $field => $value) {
            $filterMethod = 'filterBy' . studly_case($field);
            if(method_exists(get_called_class(), $filterMethod)) {
                $this->$filterMethod($q, $value);
            }
        }
       
        return $paginate
            ? $q->paginate(15)->appends($data)
            : $q->get();
    }

    /**
     * Filtro por nombre
     * 
     * @param  $q
     * @param  $value
     */
    public function filterByName($q, $value)
    {
    	$q->where('users.name', 'LIKE', "%{$value}%");
    }

    /**
     * Filtro por fecha de fin
     * 
     * @param  [type] $q     [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function filterByStatus($q, $value)
    {
        if ($value == config('options.status')[0]){
            $q->where('contracts.end_date', null);
        }    
        elseif ($value == config('options.status')[1]) {
            $q->where('contracts.end_date', '<>', null);
        }
    }

    /**
     * Filtro por tipo de contrato
     * 
     * @param  [type] $q     [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function filterByContract($q, $value)
    {
        $q->where('contracts.contract_type_id',$value);
    }
}