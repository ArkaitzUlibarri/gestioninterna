<?php

namespace App;

use App\User;
use App\GroupUser;
use Illuminate\Support\Facades\Auth;
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
                DB::raw("users.id, users.name, users.lastname, users.email, t.contract_type_id, t.start_date, t.estimated_end_date, t.end_date")
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
     
        if($data == []){
            $q = $this->Type($q, "");
        }
        elseif(isset($data['type'])){
            $q = $this->Type($q, $data['type']);
        }
        
        if(Auth::user()->primaryRole() == 'manager'){
            $groupedIds = $this->getGroupedUsers();
            $aloneIds = $this->getAloneUsers();
            $q = $q->whereIn('users.id', array_merge($groupedIds, $aloneIds));
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
        $q->where('name', 'LIKE', "%{$value}%")
          ->orWhere('lastname', 'LIKE', "%{$value}%");
    }

    /**
     * Filtro por tipo
     * 
     * @param  $q
     * @param  $value
     */
    public function Type($q, $value)
    { 
        if ($value == "inactive") {
            // Terminado sin contrato
            return $q->whereNotNull('t.end_date')
                ->orWhereNull('t.contract_type_id');
        }
        elseif($value == ""){
            // No terminado con contrato
            return $q->whereNull('t.end_date')
                ->whereNotNull('t.contract_type_id');
        }
        else{
            return $q;
        }

    }

    private function getGroupedUsers()
    {
        $ids = array();
        $projectIds = array_keys(Auth::user()->activeProjects());

        foreach ($projectIds as $projectId) {
            $project = Project::find($projectId);
            $groups = $project->groups;
            foreach ($groups as $group) {
                foreach ($group->users as $user) {
                    $ids [] = $user->id;
                }
            }
        }
        
        return array_unique($ids);    
    }

    private function getAloneUsers()
    {
        //Ids de usuarios con grupos asignados
        $groupUser_ids = array_unique(array_pluck(GroupUser::all()->toArray(), 'user_id'));

        //Descartamos estos usuarios del total
        $users_ids = array_pluck(User::all()->whereNotIn('id', $groupUser_ids)->toArray(), 'id');

        return $users_ids;
    }
}