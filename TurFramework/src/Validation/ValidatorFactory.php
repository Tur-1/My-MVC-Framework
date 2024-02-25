<?php

namespace TurFramework\Validation;


class ValidatorFactory
{
    /**
     * Create a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $attributes
     */
    public static function make(array $data, array $rules, array $messages = [], array $attributes = [])
    {
        $validator = new Validator($data, $rules, $messages, $attributes);

        return $validator;
    }
}
