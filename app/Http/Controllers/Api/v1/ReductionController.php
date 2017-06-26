<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\ReductionApiRequest;
use App\Contract;
use App\Reduction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReductionController extends ApiController
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

		$data = DB::table('reductions')
			->where('contract_id', $request->get('id'))
			->select(
				'reductions.id',
				'reductions.contract_id',
				'reductions.start_date',
				'reductions.end_date',
				'reductions.week_hours'
			)
			->orderBy('start_date','id','asc')
			->get()
			->toArray();
				
		return $this->respond($data);
	}

	/**
	 * 
	 * 
	 * @param  ReductionApiRequest $request
	 * @return json
	 */
	public function store(ReductionApiRequest $request)
	{
		$array = $request->all();

		unset($array['id']);
		unset($array['contract_start_date']);
		unset($array['contract_estimated_end_date']);

		$id = DB::table('reductions')->insertGetId($array);

		return $this->respond($id);
	}

	/**
	 * Actualizo un reporte de trabajo
	 * 
	 * @param  ReductionApiRequest $request
	 * @param  $id
	 * @return json
	 */
	public function update(ReductionApiRequest $request, $id)
	{
		$array = $request->all();

		unset($array['contract_start_date']);
		unset($array['contract_estimated_end_date']);

		$reduction = Reduction::find($id);

		if($reduction == null) {
			return $this->respondNotFound();
		}

		$confirmation = DB::table('reductions')
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
		$reduction = Reduction::find($id);

		if($reduction == null) {
			return $this->respondNotFound();
		}

		$reduction = DB::table('reductions')
			->where('id',$id)
			->delete();

		return $this->respond("Deleted");
	}
}
