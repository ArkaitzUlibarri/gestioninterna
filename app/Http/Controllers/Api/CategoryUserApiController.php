<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryUserApiRequest;
use App\User;
use App\CategoryUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryUserApiController extends ApiController
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

		$data = DB::table('category_user')
			->where('user_id', $request->get('id'))
			->select(
				'category_user.id as id',
				'category_user.user_id as user_id',
				'category_user.category_id as category_id',
				//'categories.name as name',
				//'categories.description as description',
				DB::raw("CONCAT(categories.name, ' - ', categories.description ) as category")

			)
			->join('users','category_user.user_id','=','users.id')
			->join('categories','category_user.category_id','=','categories.id')
			->get()
			->toArray();
				
		return $this->respond($data);
	}

	/**
	 * 
	 * 
	 * @param  CategoryUserApiRequest $request
	 * @return json
	 */
	public function store(CategoryUserApiRequest $request)
	{
		$array = $request->all();

		unset($array['id']);
		unset($array['category']);

		$id = DB::table('category_user')->insertGetId($array);

		return $this->respond($id);
	}

	/**
	 * Elimino un grupo asignado a un usuario
	 * 
	 * @param  $id
	 */
	public function destroy($id)
	{
		$categoryuser = CategoryUser::find($id);

		if($categoryuser == null) {
			return $this->respondNotFound();
		}

		$categoryuser = DB::table('category_user')
			->where('id',$id)
			->delete();

		return $this->respond("Deleted");		
	}
}
