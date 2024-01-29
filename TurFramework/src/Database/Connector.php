<?php

namespace TurFramework\Database;

use PDO;

class Connector
{
    protected static $instance;
    protected $pdo;
    /**
     * Create a new PDO connection instance.
     *
     * @param  string  $dsn
     * @param  string  $username
     * @param  string  $password
     */
    protected function createConnection($dsn, $username, $password)
    {
        $this->pdo = new PDO($dsn, $username, $password);
    }
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Connector();
        }

        return self::$instance;
    }
    /**
     * @return PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }
}
