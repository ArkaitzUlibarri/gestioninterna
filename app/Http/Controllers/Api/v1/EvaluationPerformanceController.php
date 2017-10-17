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
			'year'     => 'required|numeric|between:2017,2030',
			'month'    => 'required|numeric|between:1,12',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->respond(
			$this->workingreportRepository->employeesReports(
				$request->get('year'),
				$request->get('month')
			));
	}

	public function loadMonthReports(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'     => 'required|numeric|between:2017,2030',
			'month'    => 'required|numeric|between:1,12',
			'employee' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}	
				
		return $this->respond(
			$this->workingreportRepository->formatReports(
				$this->workingreportRepository->employeeMonthReports(
					$request->get('year'),
					$request->get('month'),
					$request->get('employee')
				)
			)
		);
	}

	public function loadProjectTable(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year'     => 'required|numeric|between:2017,2030',
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
		for ($i = 0; $i <= count($request->all()) - 1; $i++) { 	
			$data[$i] = [
				'year'          => $request[$i]['year'],
				'month'         => $request[$i]['month'],
				'user_id'       => $request[$i]['user_id'],
				'project_id'    => $request[$i]['project_id'],
				'type'          => $request[$i]['code'],
				//'efficiency_id' => 9,
				'mark'          => $request[$i]['mark'],
				'comment'       => $request[$i]['comment'],
				'weight'		=> $request[$i]['weight'],
				'pm_id'         => Auth::user()->id
			];
		}

		//Transacción de inserción de datos
		DB::beginTransaction();

		try{
			foreach($data as $row)
			{
				//Insert
				$ids[] = DB::table('performances')->insertGetId($row);
			}
		
        	DB::commit();
		}
		catch(\Exception $e){
    		DB::rollback();
			return $this->respondInternalError($e);
		}

		return $this->respond($ids);	
	}

	public function update(PerformanceApiRequest $request, $ids)
	{	
		if(Auth::user()->primaryRole() == 'tools' || Auth::user()->primaryRole() == 'user'){
			return $this->respondInternalError('It is required a higher role');
		}

		$index = 0;
		$ids = explode(',', $ids);

		$performances = Performance::find($ids);

		if($performances == null) {
			return $this->respondNotFound();
		}

		//Array a Actualizar
		for ($i = 0; $i <= count($request->all()) - 1; $i++) { 	
			$data[$i] = [
				'year'          => $request[$i]['year'],
				'month'         => $request[$i]['month'],
				'user_id'       => $request[$i]['user_id'],
				'project_id'    => $request[$i]['project_id'],
				'type'          => $request[$i]['code'],
				//'efficiency_id' => 9,
				'mark'          => $request[$i]['mark'],
				'comment'       => $request[$i]['comment'],
				'weight'		=> $request[$i]['weight'],
				'pm_id'         => Auth::user()->id
			];
		}

		//Transacción de inserción de datos
		DB::beginTransaction();

		try{
			foreach($data as $row)
			{
				DB::table('performances')->where('id',$ids[$index])->update($row);
				$index++;
			}
		
        	DB::commit();
		}
		catch(\Exception $e){
    		DB::rollback();
			return $this->respondInternalError($e);
		}

		return $this->respond("UPDATED");
	}

	public function destroy($ids)
	{
		if(Auth::user()->primaryRole() == 'tools' || Auth::user()->primaryRole() == 'user'){
			return $this->respondInternalError('User does not have the required permissions');
		}

		$ids = explode(',', $ids);

		$performances = Performance::find($ids);

		if($performances == null) {
			return $this->respondNotFound();
		}

		Performance::destroy($ids);

		return $this->respond("DELETED");
	}

}