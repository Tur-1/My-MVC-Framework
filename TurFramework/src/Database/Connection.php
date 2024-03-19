<?php

namespace TurFramework\Database;

use PDO;
use TurFramework\Database\Contracts\ConnectionInterface;


class Connection implements ConnectionInterface
{
    /**
     * The database connection configuration options.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var mixed \PDO pdo
     */
    protected $pdo;
    /**
     * The default fetch mode of the connection.
     *
     * @var int
     */
    protected $fetchMode = PDO::FETCH_OBJ;

    public function __construct($pdo, array $config = [])
    {
        $this->pdo = $pdo;
        $this->config = $config;
    }


    public function select($query, $bindings = [])
    {

        $statement = $this->getPdo()->prepare($query);

        $this->bindValues($statement, $bindings);

        $statement->setFetchMode($this->fetchMode);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Run an insert statement against the database.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return bool
     */
    public function insert($query, $bindings = [])
    {
        return  $this->statement($query, $bindings);
    }
    /**
     * Execute an SQL statement and return the boolean result.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return \PDOStatement
     */
    public function statement($query, $bindings = [])
    {
        $statement = $this->getPdo()->prepare($query);

        $this->bindValues($statement, $bindings);
        $statement->execute();

        return $statement;
    }

    /**
     * Run an SQL statement and get the number of rows affected.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public function affectingStatement($query, $bindings = [])
    {

        $statement = $this->getPdo()->prepare($query);

        $this->bindValues($statement, $bindings);

        $statement->execute();

        $count = $statement->rowCount() > 0;

        return $count;
    }
    /**
     * Run an update statement against the database.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public function update($query, $bindings = [])
    {
        return  $this->affectingStatement($query, $bindings);
    }

    /**
     * Run a delete statement against the database.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public function delete($query, $bindings = [])
    {
        return  $this->affectingStatement($query, $bindings);
    }

    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
    /**
     * Bind values to their parameters in the given statement.
     *
     * @param  \PDOStatement  $statement
     * @param  array  $bindings
     * @return void
     */
    public function bindValues($statement, $bindings)
    {
        foreach ($bindings as $key => $value) {
            $statement->bindValue(
                is_string($key) ? $key : $key + 1,
                $value,
                match (true) {
                    is_int($value) => PDO::PARAM_INT,
                    is_resource($value) => PDO::PARAM_LOB,
                    default => PDO::PARAM_STR
                },
            );
        }
    }
}
