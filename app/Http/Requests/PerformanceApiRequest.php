<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerformanceApiRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'    => 'required|numeric',
            'project_id' => 'required_unless:code,knowledge|numeric|nullable',
            'code'       => 'required|string',
            'year'       => 'required|numeric|between:2015,2030',
            'month'      => 'required|numeric|between:1,12',
            'comment'    => 'nullable|string',
            'mark'       => 'required|numeric'
        ];
    }
}