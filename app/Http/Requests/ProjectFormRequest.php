<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectFormRequest extends FormRequest
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
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            case 'POST':
            {
                return [
                    'name'        => 'required|string|unique:projects,name,' . $this->get('name') . ',id,customer_id,'. $this->customer_id,
                    'description' => 'required|string',
                    'customer_id' => 'required|exists:customers,id',
                    'start_date'  => 'required|date',
                    'end_date'    => 'nullable|date|after:start_date',
                    'pm_id'       => 'required|string'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'        => [
                        'required',
                        'string',
                        'unique:projects,name,' . $this->get('project_id') . ',id,customer_id,'. $this->customer_id
                    ],
                         
                    'description' => 'required|string',
                    'customer_id' => 'required|exists:customers,id',
                    'start_date'  => 'required|date',
                    'end_date'    => 'nullable|date|after:start_date',
                    'pm_id'       => 'required|string'
                ];
            }
            default:break;
        }
    }
}
