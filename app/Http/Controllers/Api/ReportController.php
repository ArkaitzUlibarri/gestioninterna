<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportController extends ApiController
{

	/**
	 * 
	 * 
	 * @return json
	 */
	public function index(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id'    => 'required|numeric',
			'created_at' => 'required|date',
		]);

        if ($validator->fails()) {
        	return $this->respondNotAcceptable($validator->errors()->all());
        }

        $data=DB::table('working_report')
        	->where('user_id', $request->get('user_id'))
        	->where('created_at', $request->get('created_at'))
        	->orderBy('created_at','id','asc')
        	->get()
        	->toArray();
        		
		return $this->respond($data);

	}

		//'activity'   => 'required|' . Rule::in(config('options.activities')),
		//'time_slots' => 'required|numeric',
		//'job_type' => 'required|' . Rule::in(config('options.typeOfJob')),
		//'comments'   => 'required|string',

}
