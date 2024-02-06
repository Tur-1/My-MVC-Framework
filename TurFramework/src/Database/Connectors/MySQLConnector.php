<?php

namespace TurFramework\Database\Connectors;

use TurFramework\Database\Connectors\Connector;
use TurFramework\Database\Contracts\ConnectorInterface;


class MySQLConnector extends Connector
{



    /**
     * connect database
     * 
     */
    public function connect($config): \PDO
    {

        $connection = $this->createConnection($this->getDsn($config), $config['username'], '');
        return $connection;
    }

    /**
     * Create a DSN string from a configuration.
     *
     * @param  array  $config
     * @return string
     */
    private  function getDsn($config)
    {

        extract($config, EXTR_SKIP);

        return "mysql:host={$host};port={$port};dbname={$database}";
    }
}
