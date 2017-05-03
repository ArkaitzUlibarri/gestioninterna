<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teleworking extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'teleworking';

	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = true;

	/**
     * Get the contract that has teleworking.
     */
	public function contract()
	{
		return $this->belongsTo('App\Contract');
	}
}
