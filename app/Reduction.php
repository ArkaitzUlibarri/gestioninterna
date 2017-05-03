<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reduction extends Model
{
     /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'reductions';

	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = true;

	 /**
     * Get the contract that has a reduction.
     */
	public function contract()
	{
		return $this->belongsTo('App\Contract');
	}
}
