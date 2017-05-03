<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

abstract class ApiController extends Controller
{

	protected $statusCode = Response::HTTP_OK;


	public function getStatusCode()
	{
		return $this->statusCode;
	}


	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;
		return $this;
	}


	/**
	 * 200. Success!
	 * 
	 * @param  $data
	 * @return json
	 */
	public function respond($data = '')
	{
		return response()->json($data, $this->getStatusCode());
	}


	/**
	 * 400. The request was invalid or cannot be otherwise served.
	 * An accompanying error message will explain further. Requests
	 * without authentication are considered invalid and will yield
	 * this response.
	 * 
	 * @param  $data
	 * @return json
	 */
	public function respondBadRequest($data = '')
	{
		return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respond($data);
	}


	/**
	 * 401. Missing or incorrect authentication credentials.
	 * 
	 * @param  $data
	 * @return json
	 */
	public function respondUnauthorized($data = '')
	{
		return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->respond($data);
	}


	/**
	 * 403.	The request is understood, but it has been refused or access
	 * is not allowed. An accompanying error message will explain why.
	 * 
	 * @param  $data
	 * @return json
	 */
	public function respondForbidden($data = '')
	{
		return $this->setStatusCode(Response::HTTP_FORBIDDEN)->respond($data);
	}


	/**
	 * 404. The URI requested is invalid or the resource requested, such
	 * as a user, does not exists. Also returned when the requested format
	 * is not supported by the requested method.
	 * 
	 * @param  $data
	 * @return json]
	 */
	public function respondNotFound($data = '')
	{
		return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respond($data);
	}


	/**
	 * 406. Returned by the Search API when an invalid format is specified in the request.
	 * 
	 * @param  $data
	 * @return json]
	 */
	public function respondNotAcceptable($data = '')
	{
		return $this->setStatusCode(Response::HTTP_NOT_ACCEPTABLE)->respond($data);
	}


	/**
	 * 500. Something is broken.
	 * 
	 * @param  $data
	 * @return json
	 */
	public function respondInternalError($data = '')
	{
		return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respond($data);
	}
}