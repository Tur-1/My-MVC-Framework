<?php

namespace TurFramework\Database;

use PDO;
use TurFramework\Support\Hash;

class MySqlManager extends Connector
{
    /**
     * @var mixed fields
     */
    private $fields = [];

    /**
     * @var mixed columns
     */
    private $columns = '*';
    /**
     * @var mixed table
     */
    private $table;
    /**
     * @var mixed limit
     */
    private $limit;
    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function __construct()
    {
        $this->createConnection($this->getDsn(), 'root', '');
    }

    /**
     * set table name
     *
     * @param  string  $table
     * @return $this
     */
    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }
    public function executeQuery()
    {


        $statement = $this->getPDO()->prepare($this->getQueryStatement());
        $statement->execute();
        return $statement;
    }

    private function getQueryStatement()
    {
        return ' SELECT ' . $this->columns . ' FROM ' . $this->table;
    }


    /**
     * select
     *
     * @return $this
     */
    public function select($columns = ['*'])
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->columns = implode(',', $columns);

        return $this;
    }
    /**
     * Compile an insert statement into SQL.
     *
     * @return string
     */
    private function getInsertQuery($fields)
    {

        $columns = implode(',', array_keys($fields));
        $values =  implode(',', array_map(fn ($key) => ":$key", array_keys($fields)));

        return 'INSERT INTO ' . $this->table . '(' . $columns . ') VALUES(' . $values . ')';
    }
    public function create(array $fields)
    {

        $statement = $this->getPDO()->prepare($this->getInsertQuery($fields));

        $this->bindValues($statement, $fields);

        return $statement->execute();
    }

    private function bindValues($statement, $fields)
    {
        foreach ($fields as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }
    }
    public function all()
    {
        $statement = $this->executeQuery();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Create a DSN string from a configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getDsn()
    {
        return 'mysql:host=localhost;dbname=nodejs_database';
    }
}
