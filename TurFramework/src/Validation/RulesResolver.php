<?php

namespace TurFramework\Validation;

class RulesResolver
{
    public static function make($rules)
    {

        if (is_string($rules)) {
            return explode('|', $rules);
        }

        return $rules;
    }
    public static function resolve($rule)
    {
        $params = [];
        if (static::isRuleHasParam($rule)) {

            [$rule, $param] = static::getParam($rule);

            $params = explode(',', $param);
        }

        return [$rule, $params];
    }
    private static function getParam($rule)
    {
        return  explode(':', $rule, 2);
    }
    private static function isRuleHasParam($rule)
    {
        return str_contains($rule, ':');
    }
}
