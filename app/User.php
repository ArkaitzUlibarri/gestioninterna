<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    const ROLES = [
        'admin',
        'tools',
        'user'
    ];

    protected $appends = ['full_name'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'lastname',
        'role',
        'password'
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
     * The groups that belong to the user.
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
     * Return the holidays requested by the user
     */
    public function holidays()
    {
        return $this->hasMany('App\CalendarHoliday')->whereYear('date','>=',Carbon::now()->year)->orderBy('date','desc');
    }

    /**
     * Return the  projects which a user can reportate
     */
    public function reportableProjects()
    {
        $groups = $this->groups;
        
        $array = [];

        foreach ($groups as $group) {
            $array[$group->project->id] = $group->project->name;
        }
        
        return $array;
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
