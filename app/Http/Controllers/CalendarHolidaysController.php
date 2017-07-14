<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CalendarHolidaysController extends Controller
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
     * Show the main validation view.
     * 
     * @return view
     */
    public function index()
    {
    	return view('holidays.index');
    }
}
