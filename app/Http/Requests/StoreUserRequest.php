<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class StoreUserRequest extends FormRequest
{



    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'email is required',
            'password.required' => 'password is required',
            'password.min' => 'the minmum password is 6',
        ];
    }
}
