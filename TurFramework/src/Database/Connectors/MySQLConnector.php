<?php

namespace TurFramework\Database\Connectors;

use TurFramework\Database\Connectors\Connector;
use TurFramework\Database\Contracts\ConnectorInterface;


class MySQLConnector extends Connector implements ConnectorInterface
{

    /**
     * @var \PDO
     */
    protected static $instance;


    /**
     * connect database
     *
     * @return \PDO
     */
    public function connect(): \PDO
    {
        if (!self::$instance) {
            self::$instance = $this->createConnection($this->getDsn(), 'root', '');
        }

        return self::$instance;
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
