<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingReport extends Model
{

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'working_report';
	
	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = true;

	/**
     * Get the user that owns the report.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
