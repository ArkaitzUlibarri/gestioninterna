<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
   	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customers';

	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = true;

}
