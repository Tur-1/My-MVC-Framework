<?php

namespace TurFramework\Database;

use TurFramework\Database\Contracts\DatabaseManagerInterface;

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
     * Indicates if the model exists.
     *
     * @var bool
     */
    public $exists = false;


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

        if (array_key_exists($key, $this->attributes)) {
            $value = $this->attributes[$key];
        }
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }


        return  $value;
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
    public function create(array $fields)
    {
        return static::query()->create($fields);
    }


    /**
     * Get the fillable attributes of a given array.
     *
     * @param  array  $attributes
     * @return array
     */
    protected function fillableFromArray(array $attributes)
    {
        if (count($this->getFillable()) > 0) {
            return array_intersect_key($attributes, array_flip($this->getFillable()));
        }

        return $attributes;
    }
    protected function fill($attributes)
    {

        $fillable = $this->fillableFromArray($attributes);

        foreach ($fillable as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }
    protected function save($query = null)
    {


        if ($this->exists) {
            $saved = $this->performUpdate($query);
        } else {

            $query = $this->newQuery();
            $saved = $this->performInsert($query);
        }

        return $saved;
    }
    protected function performUpdate(DatabaseManagerInterface $query)
    {
        $attributes = $this->getAttributes();

        return $query->preformUpdate($attributes);
    }
    protected function performInsert($query)
    {
        $attributes = $this->getAttributes();

        return $query->insert($attributes);
    }
    /**
     * Create a new instance of the given model.
     *
     * @param  array  $attributes
     * @param  bool  $exists
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {

        $model = new static;

        $model->exists = $exists;

        $model->setConnection($this->getConnectionName());

        $model->setTable($this->getTable());

        $model->fill($attributes);

        return $model;
    }

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
    /**
     * Get the fillable attributes for the model.
     *
     * @return array<string>
     */
    public function getFillable()
    {
        return $this->fillable;
    }
    /**
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
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
