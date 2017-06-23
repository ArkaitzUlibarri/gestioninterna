<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $response = array();
        $response = [  
                         'user_id'            => 'required|exists:users,id',
                         'contract_type_id'   => 'required|exists:contract_types,id',
                         'estimated_end_date' => 'nullable|date|after:start_date|date_format:Y-m-d',
                         'end_date'           => 'nullable|date|after:start_date|date_format:Y-m-d',
                         'national_days_id'   => 'required|exists:bank_holidays_codes,id',
                         'regional_days_id'   => 'required|exists:bank_holidays_codes,id',
                         'local_days_id'      => 'required|exists:bank_holidays_codes,id',
                         'week_hours'         => 'required|numeric|min:0|max:40'
                    ];

        $lastContract = $this->getPreviousContracts();
        
        if($lastContract !=[]){
            if($lastContract->end_date){
                $response['start_date'] = 'required|date|after:' . Carbon::createFromFormat('Y-m-d', $lastContract->end_date)->toDateString() . '|date_format:Y-m-d';    
                return $response;
            }
        }

        $response['start_date'] = 'start_date'         => 'required|date|date_format:Y-m-d';    
        return $response;
         
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $lastContract = $this->getPreviousContracts();
            if($lastContract !=[]){
                $start_date = $lastContract->start_date;
                $end_date = $lastContract->end_date;  

                if($end_date == null) {
                    $validator->errors()->add('end_date', "Can't be more than an active contract for this user");
                }
            }
        });
    }

    private function getPreviousContracts()
    {
        $user_id = request()->get('user_id');
        $id      = request()->get('contract_id');

        $lastContract = DB::table('contracts')
                            ->where('user_id',$user_id)
                            ->where('id','<>',$id)
                            ->orderBy('start_date','desc')
                            ->first();

        return $lastContract;
    }
}
