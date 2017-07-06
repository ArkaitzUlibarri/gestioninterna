<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Project;
use App\User;
use App\WorkingreportRepository;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ValidationController extends ApiController
{
    protected $reportRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WorkingreportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

	/**
	 * Get reports by users role and projects associated
	 * 
	 * @param  Request $request
	 * @return json
	 */
	public function index(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'year' => 'required|numeric|between:2017,2030',
			'week' => 'required|numeric|between:1,53'
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		return $this->respond($this->reportRepository->formatOutput(
            $this->reportRepository->fetchData($request->all())
        ));
	}

	/**
	 * Update user reports by week.
	 * 
	 * @param  Request $request
	 * @return json
	 */
	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id'          => 'required|numeric',
			'day'              => 'required|date',
			'admin_validation' => 'required|boolean',
			'pm_validation'    => 'required|boolean',
		]);

		if ($validator->fails()) {
			return $this->respondNotAcceptable($validator->errors()->all());
		}

		if (($user = User::find($request['user_id'])) == null) {
			return $this->respondNotFound('User not found.');
		}

		$currentValues = DB::table('working_report as wr')
			->leftJoin('users as u', 'wr.manager_id','=','u.id')
			->where('wr.user_id', $request->get('user_id'))
			->where('wr.created_at', $request->get('day'))
			->select('wr.admin_validation', 'wr.pm_validation', 'wr.manager_id', 'u.name as manager')
			->first();

		if (Auth::user()->primaryRole() != 'admin' &&
			($request['admin_validation'] != $currentValues->admin_validation || $request['pm_validation'] != $currentValues->pm_validation)) {
			return $this->respondInternalError('Refresh the browser to update the status of the tasks.');
		}

		if ($currentValues == null) {
			return $this->respondInternalError('Could not get the validation status.');
		}

		$newValues = $this->getValuesToUpdate(
			$user->primaryRole(),
			$currentValues->admin_validation,
			$currentValues->pm_validation,
			$currentValues->manager_id
		);

		if ($newValues != []) {
			DB::table('working_report')
	            ->where('user_id', $request->get('user_id'))
	            ->where('created_at', $request->get('day'))
	            ->update($newValues);

	        $newValues['manager_id'] = $this->getManager($newValues, $currentValues->manager);

	        return $this->respond($newValues);
		}

 		return $this->respond([]);
	}

	/**
	 * Get the name of the person who has validated the day.
	 * 
	 * @param  $newValues
	 * @param  $oldManager
	 * @return string
	 */
	protected function getManager($newValues, $oldManager)
	{
		if (! isset($newValues['manager_id']) || $newValues['manager_id'] == null) {
			return $newValues['manager_id'] = '';
		}

		if ($newValues['manager_id'] == Auth::user()->id) {
			return Auth::user()->name;
		}

		return $oldManager;
	}

	/**
	 * Get array with values to update.
	 * 
	 * @param  $userRole
	 * @param  $valueAdmin
	 * @param  $valueManager
	 * @param  $managerId
	 * @return array
	 */
	protected function getValuesToUpdate($userRole, $valueAdmin, $valueManager, $managerId)
	{
		//Admin
		if (Auth::user()->primaryRole() == 'admin') {

			//Mismo validador,Validado totalmente		
			if($managerId == Auth::user()->id && $valueAdmin == true && $valueManager == true){
				return [
					'admin_validation' => ! $valueAdmin,
					'pm_validation' => ! $valueAdmin,
					'manager_id' => null
				];
			}
	
			$managerId = $managerId != null ? $managerId : Auth::user()->id;

			// Admin validates another admin or tools user
			if ($userRole == 'admin' || $userRole == 'tools') {
				$valueAdmin = $valueAdmin == false ? true : false;
				return [
					'admin_validation' => $valueAdmin,
					'pm_validation' => $valueAdmin,
					'manager_id' => $valueAdmin == true ? $managerId : null
				];
			}

			// Admin validates a user
			if ($valueAdmin == false) {
				return [
					'admin_validation' => true, 
					'pm_validation' => true, 
					'manager_id' => $managerId
				];
			}

			return [
				'admin_validation' => false, 
				'manager_id' => $managerId
			];
			
		}

		// Project Manager
		$valueManager = $valueManager == false ? true : false;
		return $valueAdmin == false
			? ['pm_validation' => $valueManager, 'manager_id' => $valueManager == true ? Auth::user()->id : null]
			: [];
	}
}
