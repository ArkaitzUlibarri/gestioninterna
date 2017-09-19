<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [	
    	'user_id',
		'project_id', 
		'type',	
		'year',
		'month',
		'comment',
		'mark',
		'pm_id'
    ];
     /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'performances';

	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = true;
}
