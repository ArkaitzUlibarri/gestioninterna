<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'lastname_1', 'lastname_2', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at','password', 'remember_token',
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
        return $this->hasMany('App\WorkingReport')->orderBy('user','date','desc');
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
     * Check the role of the user
     */
    public function isRole($rolename)
    {

        if ($this->role == strtolower($rolename))
        {
            return true;
        }

        return false;
    }

    /**
     * Check if the user is ProjectManager
     */
    public function isPM()
    {
        foreach ($this->categories as $category) {
            if($category->code == 'RP' || $category->code == 'RTP'){
                return true;
            }
        }

        return false;
    }

    /**
     * Return the projects in which a User is PM
     */
    public function PMProjects()
    {
        $array = array();

        if($this->isPM()){   
            foreach ($this->groups as $group) {
                if($group->project->pm_id == $this->id){
                     $array[$group->project->id] = $group->project->name;
                }
            }
        }

        return $array;
    }


}
