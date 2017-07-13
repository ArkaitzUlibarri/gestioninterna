<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CalendarController extends ApiController
{
	/**
	 * Get the holidays requested by the user in the selected year
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

		$data = $this->getHolidays($request['user'],$request['year']);
		return ($data);
	}

	private function getHolidays($user_id,$year)
	{
		$first = DB::table('bank_holidays as b')
		->LeftJoin('bank_holidays_codes as bc','b.code_id','bc.id')
		->LeftJoin('contracts as c','b.code_id','c.national_days_id')
		//->select('b.date','b.code_id','bc.type','bc.name','bc.code')
		->select('b.date as date','bc.type as type')
		->where(DB::raw('YEAR(b.date)'),$year)
		->where('c.user_id',$user_id)
		->where('c.end_date',null);

		$second = DB::table('bank_holidays as b')
		->LeftJoin('bank_holidays_codes as bc','b.code_id','bc.id')
		->LeftJoin('contracts as c','b.code_id','c.regional_days_id')
		//->select('b.date','b.code_id','bc.type','bc.name','bc.code')
		->select('b.date as date','bc.type as type')
		->where(DB::raw('YEAR(b.date)'),$year)
		->where('c.user_id',$user_id)
		->where('c.end_date',null);

		$third = DB::table('bank_holidays as b')
		->LeftJoin('bank_holidays_codes as bc','b.code_id','bc.id')
		//->select('b.date','b.code_id','bc.type','bc.name','bc.code')
		->select('b.date as date','bc.type as type')
		->where(DB::raw('YEAR(b.date)'),$year)
		->where('bc.name','Adjustment');
			
	 	$data = DB::table('bank_holidays as b')
		->LeftJoin('bank_holidays_codes as bc','b.code_id','bc.id')
		->LeftJoin('contracts as c','b.code_id','c.local_days_id')
		//->select('b.date','b.code_id','bc.type','bc.name','bc.code')
		->select('b.date as date','bc.type as type')
		->where(DB::raw('YEAR(b.date)'),$year)
		->where('c.user_id',$user_id)
		->where('c.end_date',null);


		return DB::table('calendar_holidays')
			//->select('date','type','comments','validated')
			->select('date','type')
			->where('user_id',$user_id)
			->where(DB::raw('YEAR(date)'),$year)
			->union($second)
			->union($first)
			->union($third)
			->union($data)
			->orderBy('date','asc')
			->get();
	}
}