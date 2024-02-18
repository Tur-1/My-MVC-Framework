<?php

namespace TurFramework\Validation;

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
        $this->data = $data;

        $this->reuqestRules = $rules;
        $this->messages = $messages;

        $this->attributes = $attributes;

        $this->rule = new Rule($data);
        $this->errorsBag = new ErrorsBag();
    }

    public function validate()
    {

        foreach ($this->reuqestRules as $field => $rules) {
            foreach (RulesResolver::make($rules) as $rule) {
                [$rule, $params] = RulesResolver::resolve($rule);

                if (!method_exists($this->rule, $rule)) {
                    throw  RuleException::invalidRule($field, $rules, $rule);
                    break;
                }


                $this->applyRule($field, $rule, $params);
            }
        }


        return  $this->data;
    }

    public function getErrors()
    {
        return $this->errorsBag->all();
    }
    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function fails()
    {
        return $this->errorsBag->any();
    }
    public function passes()
    {
        return !$this->fails();
    }

    private function applyRule($field, $rule, $params)
    {
        if (empty($this->data[$field]) && $rule == 'nullable') {
            unset($this->data[$field]);
        }
        if (!$this->rule->$rule($field, ...$params)) {
            $this->errorsBag->add($field, $this->getMessage($field, $rule));
        }
    }

    protected  function getMessage($field, $rule)
    {
        return ValidationMessages::generateMessage($field, $rule, $this->messages, $this->attributes);
    }
}
