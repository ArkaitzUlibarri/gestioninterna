<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
            'name'       => 'required|alpha',
            'lastname_1' => 'required|alpha',
            'lastname_2' => 'nullable|alpha',
            'role'       => 'required|in:user,admin,tools',
            'email'      => 'required|email|unique:users,email,' . $this->get('user_id') . ',id'
        ];
    }
}
