<?php

namespace TurFramework\Database;

use TurFramework\Database\MySqlManager;


/**
 * @method static MySqlManager table(string $table)
 * @method static MySqlManager all()
 * @method static MySqlManager create(array $fields) 
 * @method static MySqlManager select($fields)
 * @see TurFramework\Database\MySqlManager
 */
class DB
{
    protected static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new MySqlManager();
        }

        return self::$instance;
    }

    /**
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getInstance();

        return $instance->{$method}(...$args);
    }
}
