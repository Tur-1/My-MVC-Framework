<?php

namespace App\Http\Requests;

use TurFramework\Http\Request;
use TurFramework\Validation\Rule;
use TurFramework\Validation\Validator;

abstract class FormRequest extends Request
{

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    protected function messages()
    {
        return [];
    }
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        return $this->all();
    }


    public function validated()
    {
        $rules = method_exists($this, 'rules') ? call_user_func([$this, 'rules']) : [];

        $vaildator = new Validator($this->validationData(), $rules, $this->messages());
        $vaildator->validate();
    }
}
