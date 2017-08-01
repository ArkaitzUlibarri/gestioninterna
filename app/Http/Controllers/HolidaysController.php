<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;

class HolidaysController extends Controller
{
   	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		//$this->middleware('checkrole');
	}

    /**
     * Show the people without a validation
     * 
     * @return view
     */
    public function index()
    {
    	$users_holidays = $this->usersHolidays();
    	return view('holidays.pending',compact('users_holidays'));
    }

    private function usersHolidays()
    {
    	//$actual_year = Carbon::now()->year;
    	
    	return DB::table('calendar_holidays as c')
    		->select(
    			'u.id as user_id',
    			'u.name',
    			'u.lastname',
    			DB::raw('year(date) as yeardate'),
    			DB::raw('GROUP_CONCAT(DISTINCT CONCAT("W",week(date)) SEPARATOR "-") as weekdate'),
    			DB::raw('count(validated) as count')
    		)
    		->join('users as u','u.id','c.user_id')
    		//->where(DB::raw('year(date)'),$actual_year)//Filtrar por año actual
    		->where('validated', 0)//Sin validar
    		->groupby('user_id','yeardate')
    		->orderby('u.name')
    		->get();		
    }

    /**
     * Show the users holidays validation
     * 
     * @return view
     */
    public function edit($id)
    {
    	$user = User::find($id);
    	//$days = $this->getDaysPerUser($id);
    	//dd($days);
    	return view('holidays.validation.validation',compact('user'));
    }

    private function getDaysPerUser($user_id)
    {
    	/*
    	return DB::table('calendar_holidays')
    		->select(
    			'date',
    			DB::raw('CONCAT("W",week(date)) as weekdate')
    		)
    		->where('user_id',$user_id)
    		->where('validated', 0)//Sin validar
    		->get();
    	*/
    
		/*
    	return DB::table('working_report as wr')
    		->join('users as u','u.id','wr.user_id')
    		->LeftJoin('users as um','um.id','wr.manager_id')
    		->select(
    			'wr.user_id',
    			DB::raw("CONCAT(u.name, ' ', u.lastname ) as user_name"),
    			'wr.created_at',
    			'wr.activity',
    			DB::raw("(wr.time_slots)*0.25 as hours"),
    			'wr.pm_validation',
    			'wr.admin_validation',
    			'wr.manager_id',
    			DB::raw("CONCAT(um.name, ' ', um.lastname ) as manager_name")
    		)
    		->where('wr.user_id',$user_id)
    		->where('wr.activity','<>','project')
    		->get();
		*/
		/*
    	return DB::table('bank_holidays as bh')
    		->join('bank_holidays_codes as bhc','bh.code_id','bhc.id')
    		->select(
    			'bh.date',
    			DB::raw('CONCAT("W",week(date)) as weekdate'),
    			'bh.code_id',
    			'bhc.type'
    		)
    		->where(DB::raw("YEAR(bh.date)"),2017)
    		->orderby('bh.date','asc')
    		->get();
    	*/
    }
}
