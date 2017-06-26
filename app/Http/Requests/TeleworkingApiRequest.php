<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeleworkingApiRequest extends ApiRequest
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
                        'monday'                      => 'required|boolean',
                        'tuesday'                     => 'required|boolean',
                        'wednesday'                   => 'required|boolean',
                        'thursday'                    => 'required|boolean',
                        'friday'                      => 'required|boolean',
                        'saturday'                    => 'required|boolean',
                        'sunday'                      => 'required|boolean',
                    ];

        $lastTeleworking = $this->getPreviousTeleworking();

        if($lastTeleworking != []){
            if($lastTeleworking->end_date){
                $response['start_date'] =  'required|date|after:' . Carbon::createFromFormat('Y-m-d', $lastTeleworking->end_date)->toDateString() . '|date_format:Y-m-d';
            }
        }
        else{
            $response['start_date'] = 'required|date|after_or_equal:contract_start_date|date_format:Y-m-d';
        }

        if( request()->get('contract_estimated_end_date')){
            $response['end_date'] = 'nullable|date|after:start_date|before:contract_estimated_end_date|date_format:Y-m-d';
        }
        else{
            $response['end_date'] = 'nullable|date|after:start_date|date_format:Y-m-d';                   
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
            $lastTeleworking = $this->getPreviousTeleworking();
            if($lastTeleworking !=[]){
                $start_date = $lastTeleworking->start_date;
                $end_date = $lastTeleworking->end_date;  

                if($end_date == null) {
                    $validator->errors()->add('end_date', "Can't be more than an active teleworking for this user");
                }
            }
        });
    }

    private function getPreviousTeleworking()
    {
        $contract_id = request()->get('contract_id');
        $id          = request()->get('id');

        $lastTeleworking = DB::table('teleworking')
                            ->where('contract_id',$contract_id)
                            ->where('id','<>',$id)
                            ->orderBy('start_date','desc')
                            ->first();

        return $lastTeleworking;
    }
}
