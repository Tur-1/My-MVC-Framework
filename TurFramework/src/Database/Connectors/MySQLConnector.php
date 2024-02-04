<?php

namespace TurFramework\Database\Connectors;

use TurFramework\Database\Connectors\Connector;
use TurFramework\Database\Contracts\ConnectorInterface;


class MySQLConnector extends Connector implements ConnectorInterface
{

    /**
     * @var \PDO $connection
     */
    protected static $connection;


    /**
     * connect database
     *
     * @return \PDO
     */
    public function connect(): \PDO
    {
        if (!self::$connection) {
            self::$connection = $this->createConnection($this->getDsn(), 'root', '');
        }

        return self::$connection;
    }

    /**
     * Create a DSN string from a configuration.
     *
     * @param  array  $config
     * @return string
     */
    private  function getDsn()
    {
        return 'mysql:host=localhost;dbname=nodejs_database';
    }
}
