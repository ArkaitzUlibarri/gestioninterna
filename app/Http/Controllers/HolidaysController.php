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
    	return view('holidays.validation.validation',compact('user'));
    }

}
