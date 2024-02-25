<?php

namespace TurFramework\Validation;

use TurFramework\Validation\Validator;
use TurFramework\Validation\ValidationException;


trait ValidationHandlerTrait
{

    protected $redirectTo = null;

    public function validateResolved()
    {
        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $instance = $this->getValidatorInstance();

        $validated =  $instance->validate();

        if ($instance->fails()) {
            $this->failedValidation($instance);
        }


        return $validated;
    }
    /**
     * Determine if the request passes the authorization check.
     *
     * @return bool
     */
    protected function passesAuthorization()
    {
        if (method_exists($this, 'authorize')) {
            return $this->authorize();
        }

        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \TurFramework\Validation\Validator $validator
     * 
     * @return void 
     * @throws \TurFramework\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $exception = new ValidationException($validator);

        throw $exception->redirect($this->getRedirectTo());
    }
    protected function setRedirectTo($url)
    {
        $this->redirectTo = $url;
    }
    protected function getRedirectTo()
    {
        return $this->redirectTo;
    }
    /**
     * Get the validator instance for the request.
     *
     * @return \TurFramework\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        return $this->getValidator();
    }
    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     * 
     */
    protected function failedAuthorization()
    {
        abort(403);
    }
}
