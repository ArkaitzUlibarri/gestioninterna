<?php

namespace App\Http\Requests;


class GroupApiRequest extends ApiRequest
{
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
                    'project_id' => 'required|integer',
                    'name'       => 'required|string|unique:groups,name,' . $this->get('name') . ',id,project_id,' . $this->project_id,
                    'enabled'    => 'required|boolean'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'project_id' => 'required|integer',
                    'name'       => 'required|string|unique:groups,name,' . $this->get('id') . ',id,project_id,' . $this->project_id,
                    'enabled'    => 'required|boolean'
                ];
            }
            default:break;
        }
    }
}
