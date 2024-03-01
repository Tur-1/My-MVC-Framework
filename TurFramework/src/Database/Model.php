<?php

namespace TurFramework\Database;

use TurFramework\Database\Concerns\ModelAttributes;

abstract class Model
{
    use ModelAttributes;

    /**
     * @var mixed manager
     */
    private static $manager;
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
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Indicates if the model exists.
     *
     * @var bool
     */
    public $exists = false;

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

    public function update(array $attributes)
    {
        return $this->fill($attributes)->save();
    }
    public function delete()
    {
        if (is_null($this->getKeyName())) {
            throw new \LogicException('No primary key defined on model.');
        }
        if (!$this->exists) {
            return;
        }

        $this->setKeysForSaveQuery($this->newQuery())->delete();

        $this->exists = false;
    }
    protected function setKeysForSaveQuery($query)
    {
        $query->where($this->getKeyName(), '=', $this->getKey());

        return $query;
    }

    public function save()
    {
        $query = $this->newQuery();

        if ($this->exists) {
            $saved = $this->performUpdate($query);
        } else {
            $saved = $this->performInsert($query);
        }

        return $saved;
    }
    private function performUpdate($query)
    {
        $saved = $this->setKeysForSaveQuery($query)->performUpdate($this->getAttributes());

        return  $saved;
    }
    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    protected function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }
    private function performInsert($query)
    {
        $attributes = $this->getAttributes();

        $id = $query->performInsert($attributes);

        $keyName = $this->getKeyName();

        $this->setAttribute($keyName, $id);

        $this->exists = true;

        return $this;
    }
    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return $this->incrementing;
    }

    /**
     * Set whether IDs are incrementing.
     *
     * @param  bool  $value
     * @return $this
     */
    public function setIncrementing($value)
    {
        $this->incrementing = $value;

        return $this;
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
        return $this->getManager()->setModel($this);
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
     * Get the database connection for the model.
     *
     * @return \TurFramework\Database\Contracts\DatabaseManagerInterface
     */
    private function getManager()
    {
        return static::resolveConnection($this->getConnectionName());
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
     * Get the primary key for the model.
     *
     * @return string
     */
    protected function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * Set the primary key for the model.
     *
     * @param  string  $key
     * @return $this
     */
    public function setKeyName($key)
    {
        $this->primaryKey = $key;

        return $this;
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
