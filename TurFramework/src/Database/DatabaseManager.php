<?php

namespace TurFramework\Database;

use PDO;

use InvalidArgumentException;
use TurFramework\Database\Managers\MySQLManager;
use TurFramework\Support\Arr;
use TurFramework\Database\Connectors\MySQLConnector;

class DatabaseManager
{
    /**
     * @var array $connections
     */
    protected static $connections = [];
    /**
     * make database connection
     * @return \TurFramework\Database\Contracts\DatabaseManagerInterface
     */
    public function makeConnection($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        $config = $this->getConfiguration($name);

        if (!isset(self::$connections[$name])) {
            self::$connections[$name] = $this->createSingleConnection($config, $name);
        }


        return self::$connections[$name];
    }



    protected function createSingleConnection($config, $name)
    {
        $pdo = $this->createPdoResolver($config);

        return $this->getDatabaseManager($pdo, $config, $name);
    }


    protected function createPdoResolver($config)
    {
        return $this->getConnector($config)->connect($config);
    }
    /**
     * Get the configuration for a connection.
     *
     * @param  string  $name
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function getConfiguration($name)
    {
        $config = Arr::get(config('database.connections'), $name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Database connection [{$name}] not configured.");
        }

        return $config;
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return config('database.default');
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
     * @param  \PDO|\Closure  $connection
     * @param  string  $database 
     * @param  array  $config
     * @return \TurFramework\Database\Contracts\DatabaseManagerInterface;
     *
     * @throws \InvalidArgumentException
     */
    protected function getDatabaseManager($connection, $config, $name)
    {

        $driver = $config['driver'];

        return match ($driver) {
            'mysql' => new MySQLManager(new Connection($connection, $config), $config, $name),
            default => throw new InvalidArgumentException("Unsupported driver [{$driver}]."),
        };
    }
}
