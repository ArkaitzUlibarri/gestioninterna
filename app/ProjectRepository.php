<?php

namespace App;

use App\Project;
use Illuminate\Support\Facades\Auth;

class ProjectRepository
{
    /**
     * Atributos por los que se puede filtar.
     *
     * @var array
     */
    protected $filters = [
        'name', 'customer','type',
    ];

	/**
	 * Devuelve una instancia del modelo del repositorio.
	 * 
	 * @return User
	 */
	public function getModel()
	{
		return new Project;
	}

    public function search(array $data = array(), $paginate = false)
    {
        $ids = array_keys(Auth::user()->PMProjects());//Proyectos PM
        $data = array_only($data, $this->filters);
        $data = array_filter($data, 'strlen');

        $q = $this->getModel()
            ->join('customers','projects.customer_id','=','customers.id')
            ->select(
                'projects.id',
                'projects.name',
                'projects.description',
                'customers.name as customer',   
                'projects.start_date',
                'projects.end_date'
            )
            ->whereIn('projects.id',$ids)//Filtro PM
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
    	$q->where('projects.name', 'LIKE', "%{$value}%");
    }

    /**
     * Filtro por cliente
     * 
     * @param  [type] $q     [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function filterByCustomer($q, $value)
    {
        $q->where('projects.customer_id', $value);    
    }
    
    /**
     * Filtro por fecha de fin
     * 
     * @param  [type] $q     [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function filterByType($q, $value)
    {
        if ($value == config('options.dates')[0]){
            $q->where('projects.end_date', null);
        }    
        elseif ($value == config('options.dates')[1]) {
            $q->where('projects.end_date', '<>', null);
        }
    }
}