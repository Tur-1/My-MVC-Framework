<?php

namespace TurFramework\Validation;

use TurFramework\Support\Arr;
use TurFramework\Validation\ValidationMessages;

class Validator
{
    protected $reuqestRules;
    protected $data;
    protected $messages;
    protected $attributes;
    protected $rule;
    protected $errorsBag = [];

    public function __construct(array $data, array $rules, array $messages = [], array $attributes = [])
    {

        $this->reuqestRules = $rules;
        $this->setData($data);
        $this->messages = $messages;
        $this->attributes = $attributes;
        $this->rule = new Rule($data);
        $this->errorsBag = new ErrorsBag();
    }

    private function setData($data)
    {
        $this->data = $this->reuqestRules ? Arr::only($data, array_keys($this->reuqestRules)) : $data;
    }
    /**
     * Run the validator's rules against its data.
     * 
     * 
     */
    public function validate()
    {

        return $this->validated();
    }

    public function getData()
    {
        return $this->data;
    }
    public function validated()
    {

        foreach ($this->reuqestRules as $field => $rules) {
            foreach (RulesResolver::make($rules) as $rule) {

                [$rule, $params] = RulesResolver::resolveRuleParam($rule);

                if ($this->isRuleNotExists($rule)) {
                    throw  RuleException::invalidRule($field, $rules, $rule);
                    break;
                }

                $this->validateAttribute($field, $rule, $params);
            }
        }
        return  $this->data;
    }

    public function getErrors()
    {
        return $this->errorsBag->all();
    }

    public function passes()
    {
        return !$this->fails();
    }
    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function fails()
    {
        return $this->errorsBag->isNotEmpty();
    }
    private function isRuleNotExists($rule)
    {
        return !method_exists($this->rule, $rule);
    }
    private function validateAttribute($field, $rule, $params)
    {

        if (empty($this->data[$field]) && $rule == 'nullable') {
            unset($this->data[$field]);
        }

        if ($this->ruleFailed($field, $rule, $params)) {
            $this->errorsBag->add($field, $this->getMessage($field, $rule, $params));
        }
    }

    protected function ruleFailed($field, $rule, $params)
    {
        return !$this->rule->$rule($field, ...$params);
    }

    public function errorsBag()
    {
        return $this->errorsBag;
    }

    protected  function getMessage($field, $rule, $params)
    {
        return ValidationMessages::generateMessage($field, $rule, $params, $this->messages, $this->attributes);
    }
}
