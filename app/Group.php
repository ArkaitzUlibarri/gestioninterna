<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [	
		'project_id', 
		'name',	
		'enabled'
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
	protected $table = 'groups';
	
	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = true;

	/**
     * Get the project that owns the group.
     */
    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
