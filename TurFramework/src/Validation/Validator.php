<?php

namespace TurFramework\Validation;

class Validator
{
    protected $reuqestRules;
    protected $data;
    protected $messages;
    protected $rule;
    protected $errorsBag = [];

    public function __construct(array $data, array $rules, array $messages = [])
    {
        $this->data = $data;

        $this->reuqestRules = $rules;
        $this->messages = $messages;

        $this->rule = new Rule($data);
        $this->errorsBag = new ErrorsBag();
    }

    public static function make(array $data, array $rules, array $messages = [])
    {
        $self = new self($data, $rules, $messages);

        $self->validate();

        return $self;
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

        if (!$this->rule->$rule($field, ...$params)) {
            $this->errorsBag->add($field, $this->getMessage($field, $rule));
        }
    }

    protected function getMessage($field, $rule)
    {

        if (isset($this->messages[$field . '.' . $rule])) {
            return $this->messages[$field . '.' . $rule];
        }
        return ucfirst($field) . ' does not meet the ' . $rule . ' rule.';
    }
}
