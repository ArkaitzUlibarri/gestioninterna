<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GroupController extends ApiController
{

	/**
	 * 
	 * 
	 * @return json
	 */
	public function index(Request $request)
	{
		
		$validator = Validator::make($request->all(), [
			'project_id'    => 'required|numeric',
		]);
		
        if ($validator->fails()) {
        	return $this->respondNotAcceptable($validator->errors()->all());
        }

		$data = DB::table('groups')
			->where('project_id', $request->get('project_id'))
			->orderBy('name','asc')
			->get();
			//->toArray();
			
		return  $this->respond($data);
	
	}

		//'name'    => 'required|string',
		//'enabled' => 'required|boolean'

}
