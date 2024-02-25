<?php

namespace TurFramework\Validation;

use Exception;
use TurFramework\Validation\Validator;

class ValidationException extends Exception
{
    /**
     * The status code to use for the response.
     *
     * @var int
     */
    public $status = 422;

    public $data = 422;

    public Validator $validator;



    public function __construct(Validator $validator)
    {

        $this->validator = $validator;
    }
    public static function withMessages(array $data, array $messages)
    {
        $static = new static(ValidatorFactory::make($data, [], $messages));

        foreach ($messages as $key => $value) {
            $static->validator->errorsBag()->add($key, $value);
        };

        return $static;
    }

    /**
     * Get all of the validation error messages.
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errorsBag()->all();
    }


    /**
     * Set the URL to redirect to on a validation error.
     *
     * @param  string  $url
     * @return $this
     */
    public function redirect($url = null)
    {
        return redirect()
            ->to($url ?? request()->previousUrl())
            ->withOldValues($this->validator->getData())
            ->withErrors($this->errors());
    }
}
