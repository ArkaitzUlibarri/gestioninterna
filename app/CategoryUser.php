<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryUser extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'category_user';

	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = true;
}
