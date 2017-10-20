<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\PerformanceApiRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Performance;
use App\PerformanceRepository;
use App\WorkingreportRepository;
use App\User;
use Auth;

class EvaluationPerformanceController extends ApiController
{
	protected $performanceRepository;
	protected $workingreportRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PerformanceRepository $performanceRepository,WorkingreportRepository $reportRepository)
    {
        $this->performanceRepository = $performanceRepository;
        $this->workingreportRepository = $reportRepository;
    }
	
	public function loadEmployees(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'     => 'required|numeric|between:2015,2030',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->respond(
			$this->workingreportRepository->employeesReports($request->get('year'))
		);
	}

	public function loadReports(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'     => 'required|numeric|between:2015,2030',
			'employee' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}	
				
		return $this->respond(
			$this->workingreportRepository->formatReports(
				$this->workingreportRepository->employeeYearReports(
					$request->get('year'),
					$request->get('employee')
				)
			)
		);
	}

	public function loadPerformance(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'     => 'required|numeric|between:2015,2030',
			'employee' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->respond(
			$this->performanceRepository->formatOutput(
				$this->performanceRepository->performanceByUserYear(
					$request->get('year'),
					$request->get('employee')
				)
			)
		);
	}

	public function store(PerformanceApiRequest $request)
	{
		if(Auth::user()->primaryRole() == 'tools' || Auth::user()->primaryRole() == 'user'){
			return $this->respondInternalError('It is required a higher role');
		}

		//Array a Insertar
		$data = [
			'year'          => $request['year'],
			'month'         => $request['month'],
			'user_id'       => $request['user_id'],
			'project_id'    => $request['project_id'],
			'type'          => $request['code'],
			//'efficiency_id' => 9,
			'mark'          => $request['mark'],
			'comment'       => $request['comment'],
			'weight'		=> $request['weight'],
			'pm_id'         => Auth::user()->id
		];
		
		$id = DB::table('performances')->insertGetId($data);

		return $this->respond($id);	
	}

	public function destroy($id)
	{
		if(Auth::user()->primaryRole() == 'tools' || Auth::user()->primaryRole() == 'user'){
			return $this->respondInternalError('User does not have the required permissions');
		}

		$performances = Performance::find($id);

		if($performances == null) {
			return $this->respondNotFound();
		}

		Performance::destroy($id);

		return $this->respond("DELETED");
	}

}