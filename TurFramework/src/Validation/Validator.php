<?php

namespace TurFramework\Validation;

class Validator
{
    protected $reuqestRules;
    protected $data;
    protected $messages;
    protected $rule;

    public function __construct(array $data, array $rules, array $messages = [])
    {
        $this->data = $data;

        $this->reuqestRules = $rules;
        $this->messages = $messages;

        $this->rule = new Rule($data);
    }

    public function validate()
    {
        $errors = [];


        foreach ($this->reuqestRules as $field => $rules) {
            foreach ($rules as $rule) {
                [$rule, $params] = $this->parseRule($rule);
                if (!method_exists($this->rule, $rule)) {
                    throw  RuleException::invalidRule($field, $rules, $rule);
                    break;
                }
                if (!$this->rule->$rule($field, $params)) {
                    $errors[$field][] = $this->getMessage($field, $rule);
                }
            }
        }

        if (!empty($errors)) {

            redirect()->back()->withErrors($errors);
        }
    }

    public function parseRule($rule)
    {

        $params = [];
        if (strpos($rule, ':') !== false) {
            [$rule, $params] = explode(':', $rule, 2);
        }

        return [$rule, $params];
    }

    protected function getMessage($field, $rule)
    {

        if (isset($this->messages[$field . '.' . $rule])) {
            return $this->messages[$field . '.' . $rule];
        }
        return ucfirst($field) . ' does not meet the ' . $rule . ' rule.';
    }
}
