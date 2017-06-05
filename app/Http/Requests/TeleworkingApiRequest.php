<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
        return [
            'contract_start_date'         => 'required|date|date_format:Y-m-d',
            'contract_estimated_end_date' => 'nullable|date|date_format:Y-m-d',
            'contract_id'                 => 'required|numeric',
            'start_date'                  => 'required|date|after_or_equal:contract_start_date|date_format:Y-m-d',
            'end_date'                    => 'nullable|date|after:start_date|date_format:Y-m-d', 
            'monday'                      => 'required|boolean',
            'tuesday'                     => 'required|boolean',
            'wednesday'                   => 'required|boolean',
            'thursday'                    => 'required|boolean',
            'friday'                      => 'required|boolean',
            'saturday'                    => 'required|boolean',
            'sunday'                      => 'required|boolean',
        ];
        
    }
}
