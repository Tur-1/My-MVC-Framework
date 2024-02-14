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
    }

    public function validate()
    {

        foreach ($this->reuqestRules as $field => $rules) {
            foreach ($this->resloveRules($rules) as $rule) {
                [$rule, $params] = $this->getRuleParams($rule);

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
        return $this->errorsBag;
    }
    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function fails()
    {
        return !empty($this->errorsBag);
    }

    private function resloveRules($rules)
    {

        if (is_string($rules)) {
            return explode('|', $rules);
        }

        return $rules;
    }
    private function applyRule($field, $rule, $params)
    {


        if (!$this->rule->$rule($field, ...$params)) {
            $this->errorsBag[$field][] = $this->getMessage($field, $rule);
        }
    }
    public function getRuleParams($rule)
    {

        $params = [];
        if ($this->isRuleHasParam($rule)) {

            [$rule, $param] = $this->getParam($rule);

            $params = explode(',', $param);
        }

        return [$rule, $params];
    }

    private function getParam($rule)
    {
        return  explode(':', $rule, 2);
    }
    private function isRuleHasParam($rule)
    {
        return str_contains($rule, ':');
    }
    protected function getMessage($field, $rule)
    {

        if (isset($this->messages[$field . '.' . $rule])) {
            return $this->messages[$field . '.' . $rule];
        }
        return ucfirst($field) . ' does not meet the ' . $rule . ' rule.';
    }
}
