<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'name',
		'description', 		
		'customer_id', 
		'start_date',
		'end_date',
		'pm_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

   	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'projects';

	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = true;

	/**
     * Get the groups for the project.
     */
	public function groups()
	{
		return $this->hasMany('App\Group')->orderBy('project_id','name', 'desc');
	}

	/**
     * Get the customer record associated with the project.
     */
    public function customer()
    {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    /**
     * Get the pm record associated with the project.
     */
    public function pm()
    {
        return $this->hasOne('App\User', 'id', 'pm_id');
    }
    
    public function isActive()
    {
    	 return $this->end_date != null ? 0 : 1;
    }

}
