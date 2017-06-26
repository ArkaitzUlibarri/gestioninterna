<?php

namespace App\Http\Controllers\Api\v1;

use App\User;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends ApiController
{
	/**
	 * Update user
	 * 
	 * @param  $id
	 * @param  Request $request
	 * @return  json
	 */
	public function update($id, Request $request)
	{
		$validator = Validator::make($request->all(), ['role'  => 'required|in:user,admin,tools']);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		$user = User::find($id);

		if ($user == null) {
			return $this->respondNotFound('User not found.');
		}

		$user->role = $request->get('role');
		$user->save();

		return $this->respond();
	}
}