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
        'name','type',//'role',
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

        $last = DB::table('contracts')->select('user_id', DB::raw('MAX(start_date) as start_date'))->groupBy('user_id');//Last contract by user
        
        //Filter by Type
        if($data == [] || ! isset($data['type'])){
            $last = $this->Type($last, "");
        }
        elseif(isset($data['type'])){
            $last = $this->Type($last, $data['type']);
        }

        $sql = $last->toSql();

        //Obtener info del contrato
        $sql2 = DB::table(DB::raw("($sql) AS r")) 
            ->join("contracts as u",function($join){
                $join->on("u.user_id","=","r.user_id")
                    ->on("u.start_date","=","r.start_date");
            })
            ->select('u.user_id','u.contract_type_id','r.start_date','u.estimated_end_date','u.end_date')
            ->toSql();
        
        //Obtener info del usuario
        $q = $this->getModel()
            ->RightJoin(DB::raw("($sql2) AS t"),'users.id','t.user_id')  
            ->select(
                DB::raw("users.id, users.name, users.lastname, users.email, t.contract_type_id, t.start_date, t.estimated_end_date, t.end_date")
            )
            ->orderBy('users.name','asc');


        foreach ($data as $field => $value) {
            $filterMethod = 'filterBy' . studly_case($field);
            if(method_exists(get_called_class(), $filterMethod)) {
                $this->$filterMethod($q, $value);
            }
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
            return $q->where('end_date','<>', null);
        }
        elseif($value == ""){
            // No terminado con contrato
            return $q->where('end_date', null);
        }
        else{
            return $q;
        }
    }

    
    /**
     * Filtro por role
     * 
     * @param  $q
     * @param  $value
     */
    /*
    public function filterByRole($q, $value)
    { 
        if($value == "admin"){
            return $q->where('users.role', 'admin');
        }
        elseif($value == ""){
            return $q;
        }
        else{
            $q->Join('category_user as cu','cu.user_id','users.id');
            $q->Join('categories as c','c.id','cu.category_id');

            if ($value == "manager") {
                return $q->where('c.code','RP')->orwhere('c.code','RTP');
            }
            elseif($value == "tools"){
                return $q->where('c.code','<>','RP')->where('c.code','<>','RTP');
            }
            elseif($value == "user"){
                return $q->where('users.role', 'user')->where('c.code','<>','RP')->where('c.code','<>','RTP');
            }
        }
    }
    */

    public function getGroupedUsers()
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