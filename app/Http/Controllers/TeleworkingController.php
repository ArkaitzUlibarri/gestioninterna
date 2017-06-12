<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;

class TeleworkingController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('checkrole');
	}

    public function edit($id)
    {
        $contract  = Contract::find($id);
		
    	return view('teleworking.create',compact('contract'));
    }
}
