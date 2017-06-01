<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class ApiRequest extends FormRequest
{
    /**
     * Override response function.
     * 
     * @param  array
     * @return json
     */
    public function response(array $errors)
    {
        $errosList = array();
        
        foreach ($errors as $group) {
            foreach ($group as $message) {
                $errosList[] = $message;
            }
        }
        
        return response()->json($errosList, Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
