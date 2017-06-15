<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\GroupUserApiRequest;
use App\User;
use App\GroupUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupUserApiController extends ApiController
{

	/**
	 * 
	 * 
	 * @return json
	 */
	public function index(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id'    => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		$data = DB::table('group_user')
			->where('user_id', $request->get('id'))
			->where('projects.end_date',null)
			->select(
				'group_user.id as id',
				'group_user.user_id as user_id',
				'group_user.group_id as group_id',
				'groups.name as group',
				'groups.project_id as project_id',
				'groups.enabled as enabled',
				'projects.name as project'
			)
			->join('users','group_user.user_id','=','users.id')
			->join('groups','group_user.group_id','=','groups.id')
			->join('projects','groups.project_id','=','projects.id')
			->get()
			->toArray();
				
		return $this->respond($data);
	}

	/**
	 * 
	 * 
	 * @param  GroupUserApiRequest $request
	 * @return json
	 */
	public function store(GroupUserApiRequest $request)
	{
		$array = $request->all();

		unset($array['id']);
		unset($array['enabled']);
		unset($array['group']);
		unset($array['project']);
		unset($array['project_id']);

		$id = DB::table('group_user')->insertGetId($array);

		return $this->respond($id);
	}

	/**
	 * Elimino un grupo asignado a un usuario
	 * 
	 * @param  $id
	 */
	public function destroy($id)
	{
		
		$groupuser = GroupUser::find($id);

		if($groupuser == null) {
			return $this->respondNotFound();
		}

		$groupuser = DB::table('group_user')
			->where('id',$id)
			->delete();

		return $this->respond("Deleted");
		
	}
}
