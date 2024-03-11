<?php

namespace App\Http\Requests;

use TurFramework\Facades\Auth;
use TurFramework\Http\FormRequest;
use TurFramework\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'password' => 'required',
        ];
 
    }

    public function authenticate()
    {
        $this->validated();

        if (!Auth::attempt($this->only('email', 'password'))) {
            throw ValidationException::withMessages(
                $this->only('email', 'password'),
                [
                    'email' => 'These credentials do not match our records.'
                ]
            );
        }
    }
}
