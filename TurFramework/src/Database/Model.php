<?php

namespace TurFramework\Database;

abstract class Model
{
    /**
     * @var mixed manager
     */
    private static $manager;

    /**
     * @var mixed attributes
     */
    protected $attributes = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;
    /**
     * Set model attribute 
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    protected function setAttribute($key, $value)
    {

        $method = 'set' . ucfirst($key) . 'Attribute';
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    protected function getAttribute($key)
    {
        $method = 'get' . ucfirst($key) . 'Attribute';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return  $this->attributes[$key] ?? null;
    }
    public static function connection($connection = null)
    {
        $model = new static;

        $model->setConnection($connection);

        return $model->newQuery();
    }

    /**
     * Begin querying the model.
     *
     *@return \TurFramework\Database\Contracts\DatabaseManagerInterface
     */
    public static function query()
    {
        $model = new static;

        return $model->newQuery();
    }
    public static function create(array $fields)
    {

        $model = new static;

        $model->fillAttributes($model, $fields);

        return static::query()->create($model->attributes);
    }

    private function fillAttributes($model, $fields)
    {

        foreach ($fields as $key => $value) {
            $model->setAttribute($key, $value);
        }
    }
    // public static function update(array $fields)
    // {

    //     $model = new static;

    //     $model->fillAttributes($model, $fields);


    //     return static::query()->update($model->attributes);
    // }
    /**
     * Resolve a connection instance.
     *
     * @param  string|null  $connection
     * @return \TurFramework\Database\Contracts\DatabaseManagerInterface
     */
    private static function resolveConnection($connection = null)
    {
        return static::$manager->makeConnection($connection);
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
     * Create a new query for the model.
     * @return \TurFramework\Database\Contracts\DatabaseManagerInterface
     */
    private function newQuery()
    {
        return $this->getConnection()->setModel($this);
    }
    /**
     * Get the database connection for the model.
     *
     * @return \TurFramework\Database\Contracts\DatabaseManagerInterface
     */
    private function getConnection()
    {
        return static::resolveConnection($this->getConnectionName());
    }

    /**
     * Set the connection associated with the model.
     *
     * @param  string|null  $name
     * @return $this
     */
    private function setConnection($name)
    {
        $this->connection = $name;

        return $this;
    }
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * Get the current connection name for the model.
     *
     * @return string|null
     */
    private function getConnectionName()
    {
        return $this->connection;
    }
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }


    /**
     * Set Database Manager.
     *
     * @param 
     * @return void
     */
    public static function setDatabaseManager($manager)
    {
        static::$manager = $manager;
    }
    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->$method(...$parameters);
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
