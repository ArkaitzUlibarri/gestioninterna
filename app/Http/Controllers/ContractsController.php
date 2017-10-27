<?php

namespace App\Http\Controllers;

use App\User;
use App\Contract;
use App\ContractType;
use App\ContractRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ContractFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ContractsController extends Controller
{
	protected $contractRepository;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	
	public function __construct(ContractRepository $contractRepository)
	{
		$this->middleware('auth');
		$this->middleware('checkrole');
		$this->contractRepository = $contractRepository;
	}

	public function index(Request $request)
	{
		$contracts = $this->contractRepository->search($request->all(), true);

		$contractTypes = ContractType::all();

		$filter = array(	
			'name'     => $request->get('name'),
			'status'   => $request->get('status'),
			'contract' => $request->get('contract'),
		);

    	return view('contracts.index', compact('contracts','filter','contractTypes'));
	}

	public function create()	
	{
		$bankHolidaysCodes = $this->getBankHolidaysCodes();
		$nationalDays  = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[0]);
		$regionalDays  = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[1]);
		$localDays     = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[2]);
		$contractTypes = ContractType::all();
		$users = User::orderBy('name')->get();

		return view('contracts.create', compact('users', 'contractTypes', 'nationalDays', 'regionalDays', 'localDays'));
	}

	public function show($id)
	{
		$contract = Contract::find($id);
		$bankHolidaysCodes = $this->getBankHolidaysCodes();
		$nationalDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[0]);
		$regionalDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[1]);
		$localDays    = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[2]);
		
		foreach ($nationalDays as $nationalDay) {
			if($nationalDay->id == $contract->national_days_id){
				$nationalDayName = $nationalDay->name;
			}
		}

		foreach ($regionalDays as $regionalDay) {
			if($regionalDay->id == $contract->regional_days_id){
				$regionalDayName = $regionalDay->name;
			}
		}

		foreach ($localDays as $localDay) {
			if($localDay->id == $contract->local_days_id){
				$localDayName = $localDay->name;
			}
		}

		return view('contracts.show', compact('contract','nationalDayName','regionalDayName','localDayName'));

	}

	public function edit($id)
	{
		$contract = $this->getContractEdit($id);
		$bankHolidaysCodes = $this->getBankHolidaysCodes();
		$nationalDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[0]);
		$regionalDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[1]);
		$localDays    = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[2]);
		$contractTypes = ContractType::all();
		$users = User::orderBy('name')->get();

		return view('contracts.edit', compact('contract', 'users', 'contractTypes', 'nationalDays', 'regionalDays', 'localDays'));
	}

	public function store(ContractFormRequest $request)
	{
		$contract = new Contract;
	    $contract->fill($request->all());
	    $contract->save();

		return redirect('contracts');
	}

	public function update(ContractFormRequest $request, $id)
	{			
        try{
	    	DB::beginTransaction();
			//******************************************************************************
        	$contract = Contract::find($id);
        	$contract->update($request->all());
	        //**************************************************************
	        //Cierre de contrato->Cerrar Teletrabajo y reduccion
	        if($request->get('end_date') != null){

				$reduction  = $contract->reductions->where('end_date',null)->first();
				if($reduction){
					DB::table('reductions')->where('id',$reduction->id)->update(['end_date' => $request->get('end_date')]);
				}

				$teleworking = $contract->teleworking->where('end_date',null)->first();
				if($teleworking){
					DB::table('teleworking')->where('id',$teleworking->id)->update(['end_date' => $request->get('end_date')]);
				}

			}	
			//******************************************************************************
			DB::commit();/* Transaction successful. */
		
		}catch(\Exception $e){       
		    DB::rollback(); /* Transaction failed. */ 
		    throw $e;
		}

		return redirect('contracts');
	}

	/**
     * Remove the specified contract from storage and his associated reductions or teleworkings
     *
     * @param  int  $id
     * @return Response
     */
	public function destroy($id)
	{
		$contract = Contract::find($id);
		$user = User::find($contract->user_id);

		//Hay reportes durante el teletrabajo
		if($this->hasReportsAssociated($contract,$user)){
			return redirect('contracts')->withErrors(['The contract has reports associated']);
		}

		try{
	    	DB::beginTransaction();
			//******************************************************************************
			//Borrar tanto el contrato como las reducciones y teletrabajos asociados
			$contract->delete();
			DB::table('reductions')->where('contract_id',$id)->delete();
			DB::table('teleworking')->where('contract_id',$id)->delete();

			Session::flash('message', 'The contract has been successfully deleted!');
			//******************************************************************************
			DB::commit();/* Transaction successful. */
		
		}catch(\Exception $e){       
		    DB::rollback(); /* Transaction failed. */ 
		    throw $e;
		}

		return redirect('contracts');
	}
    
    /**
     * Check if a user has reportes within the dates of the contract
     * @param  [type]  $contract [description]
     * @param  [type]  $user     [description]
     * @return boolean           [description]
     */
	private function hasReportsAssociated($contract,$user)
	{
		//Usuario con reportes
		if($user->workingReports->count() > 0){

			//Indefinido o Fin de Obra
			if(is_null($contract->estimated_end_date) && is_null($contract->end_date)){
				$reports_associated = $user->workingReports->where('created_at','>=',$contract->start_date);
			}
			//Con fecha de fin estimada (Temporal)
			if(! is_null($contract->estimated_end_date)){
				$reports_associated = $user->workingReports->where('created_at','>=',$contract->start_date)->where('created_at','<=',$contract->estimated_end_date);
			}
			//Contrato Finalizado
			if(! is_null($contract->end_date)){
				$reports_associated = $user->workingReports->where('created_at','>=',$contract->start_date)->where('created_at','<=',$contract->end_date);
			}
			
			return ($reports_associated->count() > 0) ? true: false;
		}
		return false;
	}

	private function filterBankHolidaysByType($array,$type)
	{
		return array_filter($array, function($item) use($type) {
			return $item->type == $type;
		});
	}

	private function getBankHolidaysCodes()
	{
		return DB::table('bank_holidays_codes')
			->select('id', 'type', 'code','name')
			->get()
			->toArray();
	}

	private function getContractEdit($id)
	{
		return DB::table('contracts')
			->join('contract_types','contracts.contract_type_id','=','contract_types.id')
			->join('users','contracts.user_id','=','users.id')
			->where('contracts.id',$id)
			->select(
				//'users.name as name',
				//'users.lastname as lastname',
				DB::raw("concat(users.name, ' ', users.lastname) as full_name"),
				'contracts.id',
				'contract_types.name as contract_type',	
				'contracts.start_date',
				'contracts.estimated_end_date',
				'contracts.end_date',
				'contracts.week_hours',
				'contracts.national_days_id',
				'contracts.regional_days_id',
				'contracts.local_days_id'
			)
			->first();
	}
}
