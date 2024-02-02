<?php

namespace TurFramework\Database\Connectors;

use PDO;

class Connector
{

    /**
     * The default PDO connection options.
     *
     * @var array
     */
    protected $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /**
     * Create a new PDO connection.
     *
     * @param  string  $dsn
     * @param  string  $username
     * @param  string  $password
     * @return \PDO
     *
     * @throws \Exception
     */
    public function createConnection($dsn, $username, $password)
    {

        return $this->createPdoConnection($dsn, $username, $password, $this->options);
    }

    /**
     * Create a new PDO connection instance.
     *
     * @param  string  $dsn
     * @param  string  $username
     * @param  string  $password
     * @param  array  $options
     * @return \PDO
     */
    protected function createPdoConnection($dsn, $username, $password, $options): \PDO
    {
        return new PDO($dsn, $username, $password, $options);
    }
}
