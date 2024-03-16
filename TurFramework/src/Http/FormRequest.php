<?php

namespace TurFramework\Http;

use RuntimeException;
use TurFramework\Validation\ValidationHandlerTrait;
use TurFramework\Validation\ValidatorFactory;

class FormRequest extends Request
{
    use ValidationHandlerTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    protected function authorize(): bool
    {
        return true;
    }
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
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    protected function attributes()
    {
        return [];
    }
    /**
     * Get request rules
     *
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function rules()
    {
        throw new RuntimeException(get_class($this) . ' does not implement rules method.');
    }

    public function validated()
    {
        return $this->validateResolved();
    }

    /**
     * Get the validator instance for the request.
     *
     * @return \TurFramework\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $validator =  ValidatorFactory::make(
            $this->validationData(),
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        return $validator;
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
}
