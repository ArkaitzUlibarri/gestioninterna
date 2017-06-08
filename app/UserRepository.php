<?php

namespace App;

use App\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * Atributos por los que se puede filtar.
     *
     * @var array
     */
    protected $filters = [
        'name','type',
    ];

	/**
	 * Devuelve una instancia del modelo del repositorio.
	 * 
	 * @return User
	 */
	public function getModel()
	{
		return new User;
	}

    public function search(array $data = array(), $paginate = false)
    {
        $data = array_only($data, $this->filters);
        $data = array_filter($data, 'strlen');


        $q = $this->getModel()
            ->select(
                DB::raw("users.id, users.name, users.lastname_1, users.lastname_2,users.email,t.contract_type_id,t.start_date,t.estimated_end_date,t.end_date")
            )
            ->LeftJoin(DB::raw(
                "(SELECT u.user_id,u.contract_type_id,r.start_date, u.estimated_end_date, u.end_date
                FROM (
                    SELECT contracts.user_id, MAX(contracts.start_date) as start_date 
                    FROM contracts 
                    GROUP BY user_id
                ) r
                INNER JOIN contracts u
                ON u.user_id = r.user_id AND u.start_date = r.start_date) as t"
            )
            ,'users.id','t.user_id') 
            ->orderBy('users.name','asc');

        foreach ($data as $field => $value) {
            $filterMethod = 'filterBy' . studly_case($field);
            if(method_exists(get_called_class(), $filterMethod)) {
                $this->$filterMethod($q, $value);
            }
        }
       
        return $paginate
            ? $q->paginate(10)->appends($data)
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
        $q  ->where('name', 'LIKE', "%{$value}%")
            ->orWhere('lastname_1', 'LIKE', "%{$value}%")
            ->orWhere('lastname_2', 'LIKE', "%{$value}%");
    }

    /**
     * Filtro por tipo
     * 
     * @param  $q
     * @param  $value
     */
    public function filterByType($q, $value)
    {
        //Active
        if ($value == config('options.dates')[0]){
            $q->whereNull('t.end_date');//No terminado
            $q->whereNotNull('t.contract_type_id');//Con contrato
        }  
        //Inactive  
        elseif ($value == config('options.dates')[1]) {
            $q->whereNotNull('t.end_date');//Terminado
            $q->orWhereNull('t.contract_type_id');//Sin contrato
        }
    }
    /* 
    SELECT users.id, users.name, users.lastname_1, users.lastname_2,users.email,t.contract_type_id,t.start_date,t.estimated_end_date,t.end_date
    FROM users 
    LEFT JOIN (
           SELECT u.user_id,u.contract_type_id,r.start_date, u.estimated_end_date, u.end_date
            FROM (
                SELECT contracts.user_id, MAX(contracts.start_date) as start_date 
                FROM contracts 
                GROUP BY user_id
            ) r
            INNER JOIN contracts u
            ON u.user_id = r.user_id AND u.start_date = r.start_date
    ) as t ON users.id=t.user_id

    where users.name like "%k%"
    where t.end_date is null and t.contract_type_id is not null //Active
    where t.end_date is not null OR t.contract_type_id is null //Inactive
     */
}