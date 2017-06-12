<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUserApiRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required|numeric',
            'user_id'     => 'required|numeric|unique:category_user,user_id,' . $this->get('user_id') . ',id,category_id,' . $this->category_id
        ];
    }
}