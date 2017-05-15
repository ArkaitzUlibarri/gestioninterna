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
			
		return  $this->respond($data);
	
	}

	/**
	 * Creo un grupo de proyecto
	 * 
	 * @param  Request $request
	 * @return json
	 */
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name'    => 'required|string',
			'enabled' => 'required|boolean'
		]);
		
        if ($validator->fails()) {
        	return $this->respondNotAcceptable($validator->errors()->all());
        }

		$array = $request->all();

		unset($array['id']);

		$id = DB::table('groups')
			->insertGetId($array);

		return $this->respond($id);

	}

	/**
	 * Actualizo un grupo de proyecto
	 * 
	 * @param  Request $request
	 * @param  $id
	 * @return json
	 */
	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'name'    => 'required|string',
			'enabled' => 'required|boolean'
		]);
		
        if ($validator->fails()) {
        	return $this->respondNotAcceptable($validator->errors()->all());
        }

		$array = $request->all();

		$group = Group::find($id);

		if($group == null) {
			return $this->respondNotFound();
		}

		$confirmation = DB::table('groups')
			->where('id',$id)
			->update($array);

		return $this->respond($confirmation);

	}


}
