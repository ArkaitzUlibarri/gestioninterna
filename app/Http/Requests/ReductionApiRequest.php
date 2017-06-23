<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReductionApiRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {     
        $response = array();
        $response = [  
                        'contract_start_date'         => 'required|date|date_format:Y-m-d',
                        'contract_estimated_end_date' => 'nullable|date|date_format:Y-m-d',
                        'contract_id'                 => 'required|numeric',
                        'week_hours'                  => 'required|numeric'
                    ];
  
        $lastReduction = $this->getPreviousReductions();

        if($lastReduction != []){
            if($lastReduction->end_date){
                $response['start_date'] ='required|date|after:' . Carbon::createFromFormat('Y-m-d', $lastReduction->end_date)->toDateString() . '|date_format:Y-m-d';
            }
        }
        else{
            $response['start_date'] ='required|date|after_or_equal:contract_start_date|date_format:Y-m-d';
        }

        if( request()->get('contract_estimated_end_date')){
            $response['end_date'] ='nullable|date|after:start_date|before:contract_estimated_end_date|date_format:Y-m-d';
        }
        else{
            $response['end_date'] ='nullable|date|after:start_date|date_format:Y-m-d';                   
        }

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
            $lastReduction = $this->getPreviousReductions();
            if($lastReduction !=[]){
                $start_date = $lastReduction->start_date;
                $end_date = $lastReduction->end_date;  

                if($end_date == null) {
                    $validator->errors()->add('end_date', "Can't be more than an active reduction for this user");
                }
            }
        });
    }

    private function getPreviousReductions()
    {
        $contract_id = request()->get('contract_id');
        $id = request()->get('id');

        $lastReduction = DB::table('reductions')
                            ->where('contract_id',$contract_id)
                            ->where('id','<>',$id)
                            ->orderBy('start_date','desc')
                            ->first();

        return $lastReduction;
    }
}
