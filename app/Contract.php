<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contract extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'user_id',
		'contract_type_id', 		
		'start_date', 
		'estimated_end_date',
		'end_date',
		'national_days_id',
		'regional_days_id',
		'local_days_id',
		'week_hours'
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
	protected $table = 'contracts';
	
	/**
	 * Does not have timestamps
	 */
	public $timestamps = false;

	/**
	 * Does not have incremental ID
	 */
	public $incrementing = true;

	/**
     * Get the reductions for the contract.
     */
	public function reductions()
	{
		return $this->hasMany('App\Reduction')->orderBy('start_date', 'desc');
	}

	/**
     * Get the teleworking for the contract.
     */
	public function teleworking()
	{
		return $this->hasMany('App\Teleworking')->orderBy('start_date', 'desc');
	}

	 /**
     * Get the user that owns the contract.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Return the user holidays card
     */
    public function holidaysCard()
    {
        return $this->hasMany('App\UserHoliday','contract_id')->where('year','=',Carbon::now()->year);
    }

	 /**
     * Get the type of contract record associated with the contract.
     */
    public function contractType()
    {
        return $this->hasOne('App\ContractType','id','contract_type_id');
    }

    public function isActive()
    {
    	return $this->end_date != null ? 0 : 1;
    }

}
