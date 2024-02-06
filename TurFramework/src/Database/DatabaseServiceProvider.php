<?php

namespace TurFramework\Database;

use TurFramework\Support\ServiceProvider;
use TurFramework\Database\Managers\MySQLManager;
use TurFramework\Database\Managers\DatabaseManager;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
         
        // SET default database connection
        //  Model::setConnection(config('database.default'));
    }
}
