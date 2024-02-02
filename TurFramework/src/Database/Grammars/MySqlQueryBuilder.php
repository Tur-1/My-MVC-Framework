<?php

namespace TurFramework\Database\Grammars;

use TurFramework\Database\Model;
use TurFramework\Database\Managers\MySQLManager;

class MySqlQueryBuilder extends MySqlGrammar
{
    /**
     * @var $mySql
     */
    protected static $mySql;
    /**
     * @var mixed fields
     */
    protected $fields = [];

    /**
     * @var mixed columns
     */
    protected $columns = '*';
    /**
     * @var mixed table
     */
    protected $table;



    /**
     * getColumns
     *
     * @return $this
     */
    public function getColumns()
    {
        return $this->columns;
    }

    public function setColumns($columns = ['*'])
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->columns = implode(',', $columns);
    }
}
