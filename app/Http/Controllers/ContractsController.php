<?php

namespace App\Http\Controllers;

use App\Contract;
use App\ContractRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ContractFormRequest;
use Illuminate\Support\Facades\DB;

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
		$this->contractRepository = $contractRepository;
	}

	public function index(Request $request)
	{
		$contracts = $this->contractRepository->search($request->all(), true);

    	return view('contracts.index', compact('contracts'));
	}

	public function create()	
	{
		$bankHolidaysCodes = $this->getBankHolidaysCodes();

		$nationalDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[0]);
		$regionalDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[1]);
		$localDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[2]);
		
		$contractTypes=$this->getContractTypes();
		
		$users=$this->getUsers();

		return view('contracts.create', compact('users', 'contractTypes', 'nationalDays', 'regionalDays', 'localDays'));
	}

	public function show($id)
	{
		//$contract = $this->getContractShow($id);
		$contract = Contract::find($id);

		$bankHolidaysCodes = $this->getBankHolidaysCodes();

		$nationalDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[0]);
		$regionalDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[1]);
		$localDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[2]);
		
		foreach ($nationalDays as $nationalDay) {
			if($nationalDay->id == $contract->national_days_id){
				$nationalDayName=$nationalDay->name;
			}
		}

		foreach ($regionalDays as $regionalDay) {
			if($regionalDay->id == $contract->regional_days_id){
				$regionalDayName=$regionalDay->name;
			}
		}

		foreach ($localDays as $localDay) {
			if($localDay->id == $contract->local_days_id){
				$localDayName=$localDay->name;
			}
		}

		return view('contracts.show', compact('contract','nationalDayName','regionalDayName','localDayName'));

	}

	public function edit($id)
	{
		$contract = $this->getContractEdit($id);

		$bankHolidaysCodes =$this->getBankHolidaysCodes();

		$nationalDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[0]);
		$regionalDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[1]);
		$localDays = $this->filterBankHolidaysByType($bankHolidaysCodes,config('options.bank_holidays')[2]);
		
		$contractTypes=$this->getContractTypes();
		
		$users=$this->getUsers();

		return view('contracts.edit', compact('contract','users', 'contractTypes', 'nationalDays', 'regionalDays', 'localDays'));
	}

	public function store(ContractFormRequest $request)
	{
		$contract = new Contract;
		
        $contract->fill($request->all());

        $contract->save();

		return redirect('/contracts');
	}

	public function update(ContractFormRequest $request, $id)
	{			
        $contract = Contract::find($id);

        $contract->update($request->all());

		return redirect('/contracts');
	}

	private function filterBankHolidaysByType($array,$type)
	{
		return array_filter($array, function($item) use($type) {
			return $item->type == $type;
		});
	}

	private function getUsers()
	{
		return DB::table('users')
			->select(
				'id',
				'name',
				DB::raw("CONCAT(name, ' ', lastname_1) as full_name")
			)
			->orderBy('full_name', 'ASC')
			->get();
	}

	private function getBankHolidaysCodes()
	{
		return DB::table('bank_holidays_codes')
			->select('id', 'type', 'code','name')
			->get()
			->toArray();
	}

	private function getContractTypes()
	{
		return DB::table('contract_types')
			->select('id','name')
			->get();
	}

	private function getContractShow($id)
	{
		return DB::table('contracts')
			->join('contract_types','contracts.contract_type_id','=','contract_types.id')
			->join('users','contracts.user_id','=','users.id')
			->where('contracts.id',$id)
			->select(
				DB::raw("CONCAT(users.name, ' ', users.lastname_1 ) as full_name"),
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

	private function getContractEdit($id)
	{
		return DB::table('contracts')
			->join('contract_types','contracts.contract_type_id','=','contract_types.id')
			->join('users','contracts.user_id','=','users.id')
			->where('contracts.id',$id)
			->select(
				'users.name as name',
				'users.lastname_1 as lastname_1',
				'users.lastname_2 as lastname_2',
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
