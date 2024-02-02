<?php

namespace TurFramework\Database\Grammars;


class Grammar
{
    /**
     * @var mixed columns
     */
    protected $columns = '*';
    /**
     * @var mixed table
     */
    protected $table;
    protected $wheres;
    /**
     * Compile an insert statement into SQL.
     *
     * @return string
     */
    protected function insertStatement($fields)
    {
        [$columns, $values] = $this->getFields($fields);

        return 'INSERT INTO ' . $this->table . '(' . $columns . ') VALUES(' . $values . ')';
    }

    protected function updateStatement($fields)
    {
        [$columns, $values] = $this->getFields($fields);

        return 'UPDATE ' . $this->table . '(' . $columns . ') VALUES(' . $values . ')';
    }
    protected function selectColumns($columns = ['*'])
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->columns = implode(',', $columns);
    }

    protected function buildWhereClause()
    {
    }
    /**
     * bindValues
     *
     * @param mixed statement
     * @param mixed fields
     *
     * @return void
     */
    protected function bindValues($statement, array $fields)
    {
        foreach ($fields as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }
    }
    protected function readStatement()
    {
        $statement = 'SELECT ' . $this->columns . ' FROM ' . $this->table;
        if (!empty($this->wheres)) {
            $statement .= ' WHERE ';
            foreach ($this->wheres as $key => $where) {
                if ($key > 0) {
                    $statement .= ' ' . $where['type'] . ' ';
                }
                $parameters[$where['column']] = $where['value'];
                $statement .=  $where['column'] . ' ' . $where['operator'] . ' ' . $where['value'];
            }
        }
        return $statement;
    }

    private function getFields($fields)
    {
        $columns = implode(',', array_keys($fields));
        $values =  implode(',', array_map(fn ($key) => ":$key", array_keys($fields)));

        return [$columns, $values];
    }
}
