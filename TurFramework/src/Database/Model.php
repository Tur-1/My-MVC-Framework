<?php

namespace TurFramework\Database;

use TurFramework\Database\Concerns\ModelAttributes;
use TurFramework\Database\Concerns\ModelTimestamps;

abstract class Model
{
    use ModelAttributes, ModelTimestamps;

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
     * Indicates if the model was inserted during the object's lifecycle.
     *
     * @var bool
     */
    public $wasRecentlyCreated = false;

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

    public function newCollection($models, $connection)
    {
        foreach ($models as $key => &$model) {
            $model = $this->newFromBuilder((array) $model, $connection);
        }

        return $models;
    }
    /**
     * Create a new model instance that is existing.
     *
     * @param  array  $attributes
     * @param  string|null  $connection
     * @return static
     */
    protected function newFromBuilder($attributes = [], $connection = null)
    {

        $model = $this->newInstance([], $connection, true);

        $model->setRawAttributes($attributes);

        return $model;
    }
    /**
     * Create a new instance of the given model.
     *
     * @param  array  $attributes
     * @param  bool  $exists
     * @return static
     */
    public function newInstance($attributes = [], $connection = null, $exists = false)
    {

        $model = new static;

        $model->exists = $exists;

        $model->setConnection($connection ?? $this->getConnectionName());

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
        if (is_null($this->getPrimaryKeyName())) {
            throw new \LogicException('No primary key defined on model.');
        }

        if (!$this->exists) {
            return;
        }

        $query = $this->newQuery();

        $this->setKeysForSaveQuery($query)->delete();

        $this->exists = false;

        return true;
    }
    protected function setKeysForSaveQuery($query)
    {
        $query->where($this->getPrimaryKeyName(), '=', $this->getAttribute($this->getPrimaryKeyName()));

        return $query;
    }

    public function save()
    {
        $query = $this->newQuery();

        if ($this->exists) {
            $savedModel = $this->performUpdate($query);
        } else {
            $savedModel = $this->performInsert($query);
        }

        return $savedModel;
    }
    private function performUpdate($query)
    {
        if ($this->usesTimestamps()) {
            $this->updateTimestamps();
        }

        $saved = $this->setKeysForSaveQuery($query)
            ->performUpdate($this->getAttributes());

        return $saved;
    }

    private function performInsert($query)
    {

        if ($this->usesTimestamps()) {
            $this->updateTimestamps();
        }
        $attributes = $this->getAttributes();

        if ($this->isAutoIncrement()) {
            $this->insertAndSetId($query, $attributes);
        } else {
            $query->insert($attributes);
        }

        $this->exists = true;

        $this->wasRecentlyCreated = true;

        return $this;
    }
    /**
     * Insert the given attributes and set the ID on the model.
     *
     * @param  $query
     * @param  array  $attributes
     * @return void
     */
    protected function insertAndSetId($query, $attributes)
    {
        $id = $query->insertAndGetId($attributes);

        $this->setAttribute($this->getPrimaryKeyName(), $id);
    }
    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function isAutoIncrement()
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
    public function newQuery()
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
     * Handle dynamic static method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {

        return (new static)->query()->$method(...$parameters);
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getPrimaryKeyName()
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
}
