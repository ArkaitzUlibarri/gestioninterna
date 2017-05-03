<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

        return [
            'user_id'            => 'required|exists:users,id',
            'contract_type_id'   => 'required|exists:contract_types,id',
            'start_date'         => 'required|date',
            'estimated_end_date' => 'nullable|date|after:start_date',
            'end_date'           => 'nullable|date|after:start_date',
            'national_days_id'   => 'required|exists:bank_holidays_codes,id',
            'regional_days_id'   => 'required|exists:bank_holidays_codes,id',
            'local_days_id'      => 'required|exists:bank_holidays_codes,id',
            'week_hours'         => 'required|numeric|min:0|max:40'
        ];
    }
}
