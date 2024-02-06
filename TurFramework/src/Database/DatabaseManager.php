<?php

namespace TurFramework\Database;

use PDO;

use InvalidArgumentException;
use TurFramework\Database\Managers\MySQLManager;

use TurFramework\Database\Connectors\Connector;
use TurFramework\Database\Connectors\MySQLConnector;
use TurFramework\Database\Contracts\DatabaseManagerInterface;

class DatabaseManager
{
    /**
     * @var array $connections
     */
    protected  $connections = [];

    /**
     * connect database
     * 
     */
    public function makeConnection($name)
    {
        $config = config('database.connections.' . $name);
      
        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->createConnection($config);
        }

       return $this->getManager($this->connections[$name],$config);
    }

    protected function createConnection($config)
    {
        return  $this->getConnector($config)->connect($config);
    }
    
   /**
     * get a connector instance based on the configuration.
     *
     * @param  array  $config
     * @return \TurFramework\Database\Contracts\ConnectorInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getConnector(array $config)
    {
        if (!isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }
        return match ($config['driver']) {
            'mysql' => new MySqlConnector,
            default => throw new InvalidArgumentException("Unsupported driver [{$config['driver']}]."),
        };
    }
    
    /**
     * Create a new connection instance.
     *
     * @param  string  $driver
     * @param  \PDO|\Closure  $connection
     * @param  string  $database 
     * @param  array  $config
     * @return \TurFramework\Database\Contracts\DatabaseManagerInterface;
     *
     * @throws \InvalidArgumentException
     */
    protected function getManager($connection, array $config = [])
    {
        $driver = $config['driver'];

        return match ($driver) {
            'mysql' => new MySQLManager($connection, $config),
            default => throw new InvalidArgumentException("Unsupported driver [{$driver}]."),
        };
    }
}
