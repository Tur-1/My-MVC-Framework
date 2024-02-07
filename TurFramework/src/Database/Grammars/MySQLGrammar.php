<?php

namespace TurFramework\Database\Grammars;


class MySQLGrammar
{
    protected $columns = '*';
    protected $limit;
    protected $table;
    protected $wheres;
    protected $wheresParams;
    /**
     * Compile an insert statement into SQL.
     *
     * @return string
     */
    protected function insertQuery($fields)
    {
        [$columns, $values] = $this->getFields($fields);

        return 'INSERT INTO ' . $this->table . '(' . $columns . ') VALUES(' . $values . ')';
    }

    protected function updateQuery($fields)
    {
        [$columns, $values] = $this->getFields($fields);

        return 'UPDATE ' . $this->table . 'SET (' . $columns . ') VALUES(' . $values . ')';
    }
    protected function readQuery()
    {
        $statement = 'SELECT ' . $this->columns . ' FROM ' . $this->table . $this->whereStatement();

        return $statement;
    }
    protected function setColumns($columns = ['*'])
    {
        $this->columns = implode(',', $columns);
    }

    protected function whereStatement()
    {
        $statement = $this->buildWhereClause();

        return $statement;
    }

    protected function getWhereValues()
    {
        return  $this->wheresParams;
    }
    private function buildWhereClause()
    {
        if (!empty($this->wheres)) {
            $statement = ' WHERE ';
            foreach ($this->wheres as $key => $where) {
                if ($key > 0) {
                    $statement .= ' ' . $where['type'] . ' ';
                }
                $this->wheresParams[$where['column']] = $where['value'];
                $condition = $where['column'] . ' ' . $where['operator'] . ' ' . ':' . $where['column'];
                $statement .=  "$condition";
            }
            return $statement;
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
            foreach ($fields as $key => $value) {
                $statement->bindValue(':' . $key, $value);
            }
        }
    }


    private function getFields($fields)
    {
        $columns = implode(',', array_keys($fields));
        $values =  implode(',', array_map(fn ($key) => ":$key", array_keys($fields)));

        return [$columns, $values];
    }
}
