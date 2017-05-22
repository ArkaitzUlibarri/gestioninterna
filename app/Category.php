<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [	
		'name',	
		'code',
		'description',
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
	protected $table = 'categories';
	
	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = true;

    /**
     * The users that belong to the category.
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
