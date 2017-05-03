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
        'name',
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
                'id',
                'name',
                'lastname_1',
                'lastname_2',    
                'email'       
            )
            ->orderBy('name','asc');



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
}