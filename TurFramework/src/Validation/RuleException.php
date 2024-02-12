<?php

namespace TurFramework\Validation;

use Exception;

class RuleException  extends Exception
{
    protected $message = '';
    protected $secondary_message = '';

    public function __construct($field, array $rules, $rule)
    {
        $this->message =  'invalid Rule  ' . $rule;

        $this->secondary_message =   $field . ' => [ ' . implode(',', $rules) . ' ] ';
    }
    public static function invalidRule($field, $rules, $rule)
    {
        return new self($field, $rules, $rule);
    }

    public function getSecondaryMessage()
    {
        return $this->secondary_message;
    }
}
