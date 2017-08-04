<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHoliday extends Model
{
     /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_holidays';

	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = false;
}
