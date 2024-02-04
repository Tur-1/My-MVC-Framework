<?php

namespace TurFramework\Database;

use TurFramework\Support\ServiceProvider;
use TurFramework\Database\Managers\MySQLManager;
use TurFramework\Database\Managers\DatabaseManager;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $db = new DatabaseManager($this->getDatabaseDriver());

        Model::setManager($db->getManager());
    }
    private function getDatabaseDriver()
    {

        return match (config('database.driver')) {
            'mysql' =>  new MySQLManager,
            default =>  new MySQLManager,
        };
    }
}
