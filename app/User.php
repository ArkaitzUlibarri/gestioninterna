<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $appends = ['full_name'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'lastname_1', 'lastname_2', 'role', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'password', 'remember_token',
    ];

    protected function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->lastname_1 . ' ' . $this->lastname_2;
    }

    public function contracts()
    {
        return $this->hasMany('App\Contract')->orderBy('start_date', 'desc');
    }

    public function workingReports()
    {
        return $this->hasMany('App\WorkingReport')->orderBy('user_id','date','desc');
    }

    /**
     * The categories that belong to the user.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category','category_user');
    }

    /**
     * The categories that belong to the user.
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group','group_user');
    }
    
    //***********************************************************************
    //FUNCIONES DE AYUDA
    //***********************************************************************
    
    /**
     * Check the role of the user
     */
    public function isRole($rolename)
    {
        return $this->role == strtolower($rolename) ? 1 : 0;
    }

    /**
     * Check the role of the user
     */
    public function isAdmin()
    {
        return $this->role == config('options.roles')[0] ? 1 : 0;
    }

    /**
     * Check if the user has teleworking in his active contract
     */
    public function hasTeleworking()
    {
        $contracts = $this->contracts; 
        
        if($contracts->isNotEmpty()){
            $active_contract = $this->contracts->where('end_date',null)->first();

             if($active_contract !=[] ){
                $teleworkingRegisters = $active_contract->teleworking;
                
                if($teleworkingRegisters->isNotEmpty()){
                    $teleworking = $teleworkingRegisters->where('end_date',null)->first();

                    if($teleworking !=[]){
                        return 1;
                    }
                }
             }
        }

        return 0;
    }

    /**
     * Check if the user is ProjectManager
     */
    public function isPM()
    {
        foreach ($this->categories as $category) {
            if($category->code == 'RP' || $category->code == 'RTP'){
                return 1;
            }
        }

        return 0;
    }

    /**
     * Return the active projects in which a User is PM
     */
    public function PMProjects()
    {
        $array = array();

        if($this->isPM()){   
            foreach ($this->groups as $group) {              
                $pm_id = $group->project->pm_id;
                $project_end_date = $group->project->end_date;

                if($pm_id == $this->id && $project_end_date == null){
                     $array[$group->project->id] = $group->project->name;
                }
            }
        }
        
        return $array;
    }
}
