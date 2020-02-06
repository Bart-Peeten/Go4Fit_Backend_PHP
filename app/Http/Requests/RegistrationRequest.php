<?php

namespace App\Http\Requests;

use App\Http\Requests\JsonRequest;

class RegistrationRequest extends JsonRequest
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
            'name' => 'required|string',
            'firstname' => 'required|string',
            'email' => 'required|email|unique:users',
            'telephone' => 'required',
            'password' => 'required|string|min:6|max:10',
        ];
    }
}
