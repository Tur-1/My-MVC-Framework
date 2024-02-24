<?php

namespace TurFramework\Validation;

use TurFramework\Database\Model;
use TurFramework\Support\Arr;

class Rule
{

    use DatabaseRule;

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

    public function unique($field, $table, $cloumn = null)
    {
        [$connection, $table] = $this->resolveTable($table);
        $cloumn = $this->resolveCloumn($field, $cloumn);

        if (class_exists($table)) {
            $model = new $table;
            if ($model instanceof Model) {
                $exstis = $model->connection($connection)->where($cloumn, $this->data[$field])->exstis();
            }
        }

        if (!str_contains($table, '\\') || !class_exists($table)) {

            $exstis = app('db')
                ->makeConnection($connection)
                ->table($table)
                ->where($cloumn, $this->data[$field])
                ->exstis();
        }

        if ($exstis) {
            return false;
        }

        return true;
    }

    public function confirmed($field)
    {
        return $this->same($field, $field . '_confirmation');
    }
    public function nullable()
    {
        return true;
    }
    public function between($field, $min, $max)
    {

        $length = strlen($this->data[$field]);

        return ($length >= $min && $length <= $max);
    }

    public function same($field, $otherField)
    {

        if ($this->required($field) || $this->required($otherField)) {
            return $this->data[$field] === $this->data[$otherField];
        }

        return true;
    }
}
