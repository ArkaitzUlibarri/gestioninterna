<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\CalendarHoliday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;

class CalendarController extends ApiController
{

	/**
	 * Get the bank holidays and the holidays requested by the user in the selected year 
	 * 
	 * @param  Request $request
	 * @return json
	 */
	public function index(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year' => 'required|numeric|between:2004,2030',
			'user' => 'required|numeric'
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->getHolidays($request['user'],$request['year']) == null
			?	$this->respondNotFound('Holidays and Bank Holidays not found')
			:	$this->respond($this->getHolidays($request['user'],$request['year']));
			  
	}

	/**
	 * Get the holidays and the left for a user in a year
	 * 
	 * @param  Request $request
	 * @return json
	 */
	public function userHolidays(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year' => 'required|numeric|between:2004,2030',
			'user' => 'required|numeric'
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->leftHolidays($request['user'],$request['year']) == null
			?	$this->respondNotFound('User Holidays not found')
			:	$this->respond($this->leftHolidays($request['user'], $request['year']));
	}

	/**
	 * Save the holiday selected by the user
	 * 
	 * @param  Request $request
	 * @return int
	 */
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'required|numeric',
			'type' => 'required|string',
			'date' => 'required|date|date_format:Y-m-d',
			'comments' => 'nullable|string',
			'validated' => 'required|boolean'
		]);
		$array = $request->all();

		$active_contract = Auth::user()->contracts->where('end_date',null)->first();
		if($active_contract == null) {
			return $this->respondNotFound('Contract does not exist');
		}

		$year = Carbon::now()->year;

		DB::beginTransaction();
		try{
			$id = DB::table('calendar_holidays')->insertGetId($array);//Insertar dia de vacaciones
			DB::table('user_holidays')
				->where('contract_id',$active_contract->id)
				->where('year',$year)
				->increment('used_'. strval($request['type']));//Actualizar contador en usuarios
			DB::commit();
		}
		catch (\Exception $e) {
    		DB::rollback();
    		return $this->respondInternalError();
		}

		return $this->respond($id);
	}

	/**
	 * Delete the holiday selected by the user
	 * 
	 * @param  $id
	 */
	public function destroy($id)
	{
		$calendar = CalendarHoliday:: find($id);
		$active_contract = Auth::user()->contracts->where('end_date',null)->first();

		if($calendar == null || $active_contract == null) {
			return $this->respondNotFound('Calendar or active contract does not exist');
		}

		$year = Carbon::now()->year;

		DB::beginTransaction();
		try{
			$answer = DB::table('calendar_holidays')->where('id',$id)->delete();//Borrar dia de vacaciones
			DB::table('user_holidays')
				->where('contract_id',$active_contract->id)
				->where('year',$year)
				->decrement('used_'. strval($calendar->type));//Actualizar contador en usuarios
			DB::commit();
		}
		catch (\Exception $e) {
    		DB::rollback();
    		return $this->respondInternalError();
		}

		return $this->respond($answer);
	}

	private function getHolidays($user_id, $year)
	{
		//SELECT DATE,TYPE,NAME,COMMENTS,VALIDATED
		$first = DB::table('bank_holidays as b')
			->LeftJoin('bank_holidays_codes as bc','b.code_id','bc.id')
			->LeftJoin('contracts as c','b.code_id','c.national_days_id')
			->select(DB::raw('null as id'),'b.date as date','bc.type as type','bc.name as name',DB::raw('null as comments'),DB::raw('null as validated'))
			->where(DB::raw('YEAR(b.date)'),$year)
			->where('c.user_id',$user_id)
			->where('c.end_date',null);

		$second = DB::table('bank_holidays as b')
			->LeftJoin('bank_holidays_codes as bc','b.code_id','bc.id')
			->LeftJoin('contracts as c','b.code_id','c.regional_days_id')
			->select(DB::raw('null as id'),'b.date as date','bc.type as type','bc.name as name',DB::raw('null as comments'),DB::raw('null as validated'))
			->where(DB::raw('YEAR(b.date)'),$year)
			->where('c.user_id',$user_id)
			->where('c.end_date',null);
			
	 	$third = DB::table('bank_holidays as b')
			->LeftJoin('bank_holidays_codes as bc','b.code_id','bc.id')
			->LeftJoin('contracts as c','b.code_id','c.local_days_id')
			->select(DB::raw('null as id'),'b.date as date','bc.type as type','bc.name as name',DB::raw('null as comments'),DB::raw('null as validated'))
			->where(DB::raw('YEAR(b.date)'),$year)
			->where('c.user_id',$user_id)
			->where('c.end_date',null);

		$data = DB::table('bank_holidays as b')
			->LeftJoin('bank_holidays_codes as bc','b.code_id','bc.id')
			->select(DB::raw('null as id'),'b.date as date','bc.type as type','bc.name as name',DB::raw('null as comments'),DB::raw('null as validated'))
			->where(DB::raw('YEAR(b.date)'),$year)
			->where('bc.name','Adjustment');


		return DB::table('calendar_holidays')
			->select('id','date','type',DB::raw('null as name'),'comments','validated')
			->where('user_id',$user_id)
			->where(DB::raw('YEAR(date)'),$year)
			->union($second)
			->union($first)
			->union($third)
			->union($data)
			->orderBy('date','asc')
			->get();
	}

	private function leftHolidays($user_id, $year)
	{
		return DB::table('user_holidays as u')
			->join('contracts as c','u.contract_id','c.id')
			->select(
				'u.current_year',
				'u.used_current_year',
				'u.last_year',
				'u.used_last_year',
				'u.extras',
				'u.used_extras',
				DB::raw('(u.current_year + u.last_year + u.extras) as total'),
				DB::raw('(u.used_current_year + u.used_last_year + u.used_extras) as used_total'),
				'u.used_next_year'
			)
			->where(DB::raw('u.year'),$year)
			->where('c.user_id',$user_id)
			->where('c.end_date',null)//Contrato activo
			->orderBy('c.start_date','desc')
			->first();
	}
}