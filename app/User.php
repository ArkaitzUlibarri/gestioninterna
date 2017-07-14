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
        'username', 'name', 'email', 'lastname', 'role', 'password'
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
        return $this->name . ' ' . $this->lastname;
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

    /**
     * Return the projects which a user is manager.
     */
    public function projects()
    {
        return $this->hasMany('App\Project', 'pm_id');
    }

    /**
     * Return the active projects which a user is manager.
     */
    public function activeProjects()
    {
        $projects = $this->projects()
            ->whereNull('end_date')
            ->select('id', 'name')
            ->get();

        $array = [];
        foreach ($projects as $project) {
            $array[$project->id] = $project->name;
        }

        return $array;
    }

    /**
     * Return the projects which a user is manager.
     */
    public function managerProjects()
    {
        $projects = $this->projects()
            ->select('id', 'name')
            ->get();

        $array = [];
        foreach ($projects as $project) {
            $array[$project->id] = $project->name;
        }

        return $array;
    }

    /**
     * Get the main role of the user
     */
    public function primaryRole()
    {
        if ($this->role == 'admin') {
            return 'admin';
        }

        foreach ($this->categories as $category) {
            if ($category->code == 'RP' || $category->code == 'RTP') {
                return 'manager';
            }
        }

        return $this->role == 'tools'
            ? 'tools'
            : 'user';
    }



    /**
     * Check the role of the user

    public function isRole($rolename)
    {
        return $this->role == strtolower($rolename) ? 1 : 0;
    }
 */
    /**
     * Check the role of the user
    
    public function isAdmin()
    {
        return $this->role == config('options.roles')[0] ? 1 : 0;
    }
 */
    /**
     * Check if the user is ProjectManager
    
    public function isPM()
    {
        foreach ($this->categories as $category) {
            if($category->code == 'RP' || $category->code == 'RTP'){
                return 1;
            }
        }

        return 0;
    }
 */
    /**
     * Return the active projects in which a User is PM

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
     */


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
}
