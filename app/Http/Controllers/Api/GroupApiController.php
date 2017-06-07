<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Group;
use Illuminate\Http\Request;
use App\Http\Requests\GroupApiRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GroupApiController extends ApiController
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
	 * @param  GroupApiRequest $request
	 * @return json
	 */
	public function store(GroupApiRequest $request)
	{

		$array = $request->all();

		unset($array['id']);

		$id = DB::table('groups')
			->insertGetId($array);

		return $this->respond($id);

	}

	/**
	 * Actualizo un grupo de proyecto
	 * 
	 * @param  GroupApiRequest $request
	 * @param  $id
	 * @return json
	 */
	public function update(GroupApiRequest $request, $id)
	{

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

	/**
	 * Elimino un grupo del proyecto mediante el id.
	 * 
	 * @param  $id
	 */
	public function destroy($id)
	{
		$group = Group::find($id);

		if($group == null) {
			return $this->respondNotFound();
		}
		
		if($group->name !='-'){
			$reports = DB::table('working_report')
				->where('group_id',$id)
				->get()
				->toArray();

			if($reports == []) {
				$group = DB::table('groups')
					->where('id',$id)
					->delete();

				return $this->respond(true);
			}
		}

		return $this->respond(false);

	}

}
