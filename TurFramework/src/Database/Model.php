<?php

namespace TurFramework\Database;

use TurFramework\Database\Managers\MySQLManager;
use TurFramework\Database\DatabaseManager;


abstract class Model
{
    protected $connection;
    protected static $manager;
    protected static $model;
    protected $attributes = [];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;
    /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = 'updated_at';

    /**
     * Set the connection associated with the model.
     *
     * @param  string|null  $name
     * @return $this
     */
    public function setConnection($name)
    {
        $this->connection = $name;

        return $this;
    }


    /**
     * Get the current connection name for the model.
     *
     * @return string|null
     */
    public function getConnectionName()
    {
        return $this->connection;
    }
    public static function connection($connection = null)
    {
        $model = new static;

        $model->setConnection($connection);

        return $model->newQuery($model);
    }
    /**
     * Create a new Eloquent query builder for the model.
     * @return \TurFramework\Database\Contracts\DatabaseManagerInterface
     */
    private function newQueryBuilder()
    {
        return (new DatabaseManager())->makeConnection($this->getConnectionName());
    }
    /**
     * Begin querying the model.
     *
     * @return \TurFramework\Database\Managers\MySQLManager
     */
    public static function query()
    {
        $model = new static;

        return $model->newQuery($model);
    }

    private function newQuery($model)
    {
        return $this->newQueryBuilder()->setModel($model);
    }


    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table ?? pluralStudly(class_basename($this));
    }
    /**
     * Set the table associated with the model.
     *
     * @param  string  $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
        $this->setTable($this->getTable());
    }
    /**
     * Set the default connection .
     *
     * @param  
     * @return void
     */
    public static function setDefaultConnection($connection)
    {
        static::$connection = $connection;
    }

    /**
     * Handle dynamic static method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}
