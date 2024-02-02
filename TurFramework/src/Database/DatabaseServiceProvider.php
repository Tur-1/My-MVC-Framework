<?php

namespace TurFramework\Database;

use TurFramework\Database\DatabaseManager;
use TurFramework\Support\ServiceProvider;
use TurFramework\Database\Connectors\MySQLConnector;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $db = new DatabaseManager($this->getDatabaseDriver());
        $this->app->bind('database', function ($app) use ($db) {
            return $db;
        });

        $connection = $db->makeConnection();

        Model::setConnection($connection);
    }
    private function getDatabaseDriver()
    {

        return match (config('database.driver')) {
            'mysql' =>  new MySQLConnector,
            default =>  new MySQLConnector,
        };
    }
}
