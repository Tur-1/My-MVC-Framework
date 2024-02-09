<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql_1'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Tur-Framework is shown below to make development simple.
    |
    |
    | All database work in Tur-Framework is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
        'mysql_1' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],
        'mysql_2' => [
            'driver' => 'mysql',
            'host' => env('DB_SEC_HOST', '127.0.0.1'),
            'port' => env('DB_SEC_PORT', '3306'),
            'database' => env('DB_SEC_DATABASE', 'forge'),
            'username' => env('DB_SEC_USERNAME', 'root'),
            'password' => env('DB_SEC_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

        'pgsql_1' => [
            'driver' => 'pgsql',
            'host' => env('DB_pgsql_HOST', '127.0.0.1'),
            'port' => env('DB_pgsql_PORT', '3306'),
            'database' => env('DB_pgsql_DATABASE', 'forge'),
            'username' => env('DB_pgsql_USERNAME', 'root'),
            'password' => env('DB_pgsql_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],
        'pgsql_2' => [
            'driver' => 'pgsql',
            'host' => env('DB_pgsql_SEC_HOST', '127.0.0.1'),
            'port' => env('DB_pgsql_SEC_PORT', '3306'),
            'database' => env('DB_pgsql_SEC_DATABASE', 'forge'),
            'username' => env('DB_pgsql_SEC_USERNAME', 'root'),
            'password' => env('DB_pgsql_SEC_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],
    ],


];
