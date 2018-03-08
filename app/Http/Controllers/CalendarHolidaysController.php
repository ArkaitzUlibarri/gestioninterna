<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CalendarHolidaysController extends Controller
{
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
