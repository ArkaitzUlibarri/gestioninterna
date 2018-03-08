<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;

class ReductionsController extends Controller
{

    public function edit($id)
    {
        $contract  = Contract::find($id);
		
    	return view('reductions.create',compact('contract'));
    }
}
