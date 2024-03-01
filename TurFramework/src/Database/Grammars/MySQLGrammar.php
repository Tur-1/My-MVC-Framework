<?php

namespace TurFramework\Database\Grammars;

use TurFramework\Support\Arr;


class MySQLGrammar
{
    /**
     * The current query value bindings.
     *
     * @var array
     */
    protected $bindings = [
        'columns_values' => [],
        'where' => [],
    ];
    protected $wheres;
    protected $table;


    protected $queryComponents = [
        'select' => 'SELECT *',
        'from' => [],
        'join' => [],
        'where' => [],
        'groupBy' => [],
        'having' => [],
        'orderBy' => [],
        'limit' => [],
    ];

    protected function deleteQuery()
    {
        $table = $this->queryComponents['from'];


        return "DELETE $table " . $this->getWhereStatment();
    }
    protected function getWhereStatment()
    {

        if (!empty($this->queryComponents['where'])) {
            return  $this->queryComponents['where'];
        }

        return '';
    }
    protected function insertQuery($fields)
    {

        $columns =  implode(', ', array_keys($fields));
        $parameters =  trim(str_repeat('?,', count(array_keys($fields))), ',');

        $this->bindings['columns_values'] = array_values($fields);

        return "insert into $this->table ($columns) values ($parameters)";
    }
    protected function updateQuery($fields)
    {

        foreach ($fields as $column => $value) {
            $columns[] = "$column = ? ";
        }

        $columns = implode(', ', $columns);

        $this->bindings['columns_values'] = array_values($fields);

        return "UPDATE $this->table SET $columns" . $this->getWhereStatment();
    }

    public function existsQuery()
    {
        $select = $this->selectQuery();

        return "select exists({$select}) as record_exists";
    }

    protected function selectQuery()
    {
        $sqlQuery = $this->concatenate($this->compileComponents());

        return $sqlQuery;
    }
    /**
     * Compile the components necessary for a select clause.
     *
     * @return array
     */
    protected function compileComponents()
    {
        $sql = [];

        foreach ($this->queryComponents as $key => $value) {
            if (!empty($value)) {
                $sql[$key] = '' . $value;
            }
        }

        return $sql;
    }
    /**
     * Concatenate an array of segments, removing empties.
     *
     * @param  array  $segments
     * @return string
     */
    protected function concatenate($segments)
    {
        return implode(' ', array_filter($segments, function ($value) {
            return (string) $value !== '';
        }));
    }

    protected function addComponent($key, $value)
    {
        $this->queryComponents[$key] = $value;
    }
    protected function addArrayOfWheres($column, $type)
    {
        $wheres = [];

        foreach ($column as $key => $value) {
            $wheres[] = [
                'type' => $type,
                'column' => $key,
                'operator' => '=',
                'value' => $value
            ];
        }

        $this->wheres[] = $wheres;
    }
    protected function compileWhere()
    {
        $statement = '';
        $bindings = [];

        foreach ($this->wheres as $key => $group) {
            $groupStatement = '';
            if (isset($group[0]) && count($group[0]) > 0) {
                foreach ($group as $where) {
                    $condition = $this->buildWhereStatement($where);

                    $groupStatement .= ($groupStatement ? " {$where['type']} " : '') . $condition;

                    $bindings[] = $where['value'] ?? [];
                }

                $groupStatement = "($groupStatement)";

                $statement .= ($statement ? ' OR ' : '') . $groupStatement;
            } else {

                $statement .= $this->getWhereType($key, $group['type']);
                $statement .=  $this->buildWhereStatement($group);


                $bindings[] = $group['value'] ?? [];
            }
        }

        $this->bindings['where'] = Arr::flatten($bindings);

        return  $statement;
    }

    private function buildWhereStatement($where)
    {
        if ($this->isNullOrNotNull($where['operator'])) {
            return $this->buildWhereNull($where);
        }

        if ($this->isWhereIn($where['operator'])) {
            return $this->buildWhereInStatement($where);
        }

        return $where['column'] . ' ' . $where['operator'] . ' ?';
    }
    private function isNullOrNotNull($operator)
    {
        return $operator == 'IS NULL' || $operator == 'IS NOT NULL';
    }
    private function buildWhereNull($where)
    {
        return $where['column'] . ' ' . $where['operator'];
    }
    private function isWhereIn($operator)
    {
        return $operator == 'IN' ||  $operator == 'NOT IN';
    }
    private function buildWhereInStatement($where)
    {
        foreach ($where['value'] as $index => $value) {
            $valuess[] = '?';
        }

        return $where['column'] . ' IN (' . implode(', ', $valuess) . ')';
    }
    private function getWhereType($key, $type)
    {
        if ($key > 0) {
            return ' ' . $type . ' ';
        }
    }
    /**
     * Prepare the value and operator for a where clause.
     *
     * @param  string  $value
     * @param  string  $operator
     * @param  bool  $useDefault
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function prepareValueAndOperator($value, $operator, $useDefault = false)
    {
        if ($useDefault) {
            return [$operator, '='];
        }
        return [$value, $operator];
    }
}
