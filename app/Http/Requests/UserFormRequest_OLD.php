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


        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            case 'POST':
            {
                /*
                return [
                    'name'       => 'required|string',
                    'lastname'   => 'required|string',
                    'role'       => 'required|in:user,admin,tools',
                    'email'      => 'required|string|email|unique:users,email,' . $this->get('user_id') . ',id',
                    'password'   => 'required|string|min:6|confirmed'
                ];
                */
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    //'name'       => 'required|string',
                    //'lastname'   => 'required|string',
                    'role'       => 'required|in:user,admin,tools',
                    //'email'      => 'required|string|email|unique:users,email,' . $this->get('user_id') . ',id'
                ];
            }
            default:break;
        }
    }
}
