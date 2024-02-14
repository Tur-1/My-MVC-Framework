<?php

namespace App\Http\Requests;

use TurFramework\Http\FormRequest;

class StoreUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users'],
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'email is required',
            'password.required' => 'password is required',
            'password.min' => 'the minmum password is 6',
        ];
    }
}
