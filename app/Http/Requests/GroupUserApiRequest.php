<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupUserApiRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'  => 'required|numeric',
            'group_id' => 'required|numeric|unique:group_user,group_id,' . $this->get('group_id') . ',id,user_id,' . $this->user_id        
        ];
    }
}
