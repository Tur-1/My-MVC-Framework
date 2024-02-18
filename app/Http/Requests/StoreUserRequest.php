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
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
            'name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [];
    }


    public function attributes(): array
    {
        return [
            'email' => 'email address',
        ];
    }
}
