<?php

namespace TurFramework\Database\Grammars;


trait MySQLGrammar
{
    protected $columns = '*';
    protected $limit;
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

        return 'UPDATE ' . $this->table . 'SET (' . $columns . ') VALUES(' . $values . ')';
    }
    protected function setColumns($columns = ['*'])
    {
        $this->columns = implode(',', $columns);
    }

    protected function buildWhereClause($statement)
    {
        $wheresParams = [];
        if (!empty($this->wheres)) {
            $statement .= ' WHERE ';
            foreach ($this->wheres as $key => $where) {
                if ($key > 0) {
                    $statement .= ' ' . $where['type'] . ' ';
                }
                $wheresParams[$where['column']] = $where['value'];
                $condition = $where['column'] . ' ' . $where['operator'] . ' ' . ':' . $where['column'];
                $statement .=  "$condition";
            }
        }

        return [$statement,  $wheresParams];
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

        return $statement;
    }

    private function getFields($fields)
    {
        $columns = implode(',', array_keys($fields));
        $values =  implode(',', array_map(fn ($key) => ":$key", array_keys($fields)));

        return [$columns, $values];
    }
}
