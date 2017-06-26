<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\TeleworkingApiRequest;
use App\Contract;
use App\Teleworking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeleworkingController extends ApiController
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

		$data = DB::table('teleworking')
			->where('contract_id', $request->get('id'))
			->select(
				'teleworking.id',
				'teleworking.contract_id',
				'teleworking.start_date',
				'teleworking.end_date',
				'teleworking.monday',
				'teleworking.tuesday',
				'teleworking.wednesday',
				'teleworking.thursday',
				'teleworking.friday',
				'teleworking.saturday',
				'teleworking.sunday'
			)
			->orderBy('start_date','id','asc')
			->get()
			->toArray();
				
		return $this->respond($data);
	}

	/**
	 * 
	 * 
	 * @param  TeleworkingApiRequest $request
	 * @return json
	 */
	public function store(TeleworkingApiRequest $request)
	{
		$array = $request->all();

		unset($array['id']);
		unset($array['contract_start_date']);
		unset($array['contract_estimated_end_date']);

		$id = DB::table('teleworking')->insertGetId($array);

		return $this->respond($id);
	}

	/**
	 * Actualizo un reporte de trabajo
	 * 
	 * @param  TeleworkingApiRequest $request
	 * @param  $id
	 * @return json
	 */
	public function update(TeleworkingApiRequest $request, $id)
	{
		$array = $request->all();

		unset($array['contract_start_date']);
		unset($array['contract_estimated_end_date']);

		$teleworking = Teleworking::find($id);

		if($teleworking == null) {
			return $this->respondNotFound();
		}

		$confirmation = DB::table('teleworking')
			->where('id',$id)
			->update($array);

		return $this->respond("Updated");

	}

	/**
	 * Elimino un reporte de trabajo mediante el id.
	 * 
	 * @param  $id
	 */
	public function destroy($id)
	{
		$teleworking = Teleworking::find($id);

		if($teleworking == null) {
			return $this->respondNotFound();
		}

		$teleworking = DB::table('teleworking')
			->where('id',$id)
			->delete();

		return $this->respond("Deleted");
	}
}
