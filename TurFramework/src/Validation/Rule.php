<?php

namespace TurFramework\Validation;

class Rule
{
    public $data = [];
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function required($field)
    {
        return isset($this->data[$field]) && !empty($this->data[$field]);
    }

    public function min($field, $params)
    {
        return isset($this->data[$field]) && strlen($this->data[$field]) >= $params;
    }

    public function email($field)
    {
        return isset($this->data[$field]) && filter_var($this->data[$field], FILTER_VALIDATE_EMAIL);
    }
}
