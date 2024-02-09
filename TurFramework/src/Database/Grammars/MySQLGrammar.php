<?php

namespace TurFramework\Database\Grammars;


class MySQLGrammar
{
    protected $columns = '*';
    protected $table;
    protected $wheres;
    protected $wheresValues;
    protected $limit;
    /**
     * All of the available clause operators.
     *
     * @var string[]
     */
    public $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>', '&~', 'is', 'is not',
        'rlike', 'not rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];
    /**
     * Compile an insert statement into SQL.
     *
     * @return string
     */
    protected function insertQuery($fields)
    {

        return 'INSERT INTO ' . $this->table . $this->setColumnsAndValues($fields);
    }
    protected function deleteQuery()
    {
        return 'DELETE FROM ' . $this->table . $this->whereStatement();
    }
    protected function updateQuery($fields)
    {
        return 'UPDATE ' . $this->table . 'SET ' . $this->setColumnsAndValues($fields);
    }
    protected function readQuery()
    {
        $statement = 'SELECT ' . $this->columns . ' FROM ' . $this->table . $this->whereStatement() .   $this->limit();

        return $statement;
    }

    private function limit()
    {
        if ($this->limit) {
            return ' LIMIT ' .  $this->limit;
        }
    }

    protected function setQueryLimit($limit)
    {
        $this->limit = $limit;
    }
    protected function setColumns($columns = ['*'])
    {
        $this->columns = implode(',', $columns);
    }

    protected function whereStatement()
    {
        if (!empty($this->wheres)) {
            return $this->buildWhereClause();
        }
    }

    protected function getWhereValues()
    {
        return  $this->wheresValues;
    }

    private function buildWhereClause()
    {


        $statement = ' WHERE ';
        foreach ($this->wheres as $key => $where) {
            // if where is a more than one condition ? add type --> AND , OR etc..
            $statement .= $this->getWhereType($key, $where['type']);

            // build statement for example: WHERE column operator :column
            $statement .= $this->buildWhereStatement($where);
        }

        return $statement;
    }
    private function setWhereValues($column, $value)
    {
        return  $this->wheresValues[$column] = $value;
    }
    private function buildWhereStatement($where)
    {
        if ($this->isNullOrNotNull($where['operator'])) {
            return $this->buildWhereNull($where);
        }

        $this->setWhereValues($where['column'], $where['value']);

        if ($this->isWhereIn($where['operator'])) {
            return $this->buildWhereInStatement($where);
        }

        return $where['column'] . ' ' . $where['operator'] . ' :' . $where['column'];
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
            $valuess[] = ':' . $where['column'] . $index;
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
     * bindValues
     *
     * @param mixed statement
     * @param array fields
     *
     * @return void
     */
    protected function bindValues($statement, $fields)
    {

        if ($fields) {
            foreach ($fields as $column => $value) {
                if (is_array($value)) {
                    foreach ($value as $index => $val) {
                        $statement->bindValue(':' . $column . $index, $val);
                    }
                } else {

                    $statement->bindValue(':' . $column, $value);
                }
            }
        }
    }


    private function setColumnsAndValues($fields)
    {
        $columns = $this->prepareColumns(array_keys($fields));
        $values = $this->prepareValues(array_keys($fields));

        return  ' (' . $columns . ') VALUES(' . $values . ')';
    }
    private function prepareColumns(array $columns)
    {
        return implode(',',  $columns);
    }
    private function prepareValues(array $columns)
    {
        return ':' . implode(',:', $columns);
    }
    protected function addWhere($column, $operator = null, $value = null, $type = 'AND')
    {
        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.

        [$value, $operator] = $this->prepareValueAndOperator(
            $value,
            $operator,
            func_num_args() === 2 && is_null($value)
        );
        $this->wheres[] = [
            'type' => $type,
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];
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
        } elseif ($this->invalidOperatorAndValue($operator, $value)) {
            throw new \InvalidArgumentException('Illegal operator and value combination.');
        }

        return [$value, $operator];
    }
    /**
     * Determine if the given operator and value combination is legal.
     *
     * Prevents using Null values with invalid operators.
     *
     * @param  string  $operator
     * @param  mixed  $value
     * @return bool
     */
    protected function invalidOperatorAndValue($operator, $value)
    {
        return is_null($value) && in_array($operator, $this->operators) && !in_array($operator, ['=', '<>', '!=']);
    }
}
