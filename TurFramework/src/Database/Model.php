<?php

namespace TurFramework\Database;


abstract class Model
{
    protected static $manager;
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
     * Create a new Eloquent query builder for the model.
     * @return \TurFramework\Database\QueryBuilder
     */
    private function newQueryBuilder()
    {
        return new QueryBuilder(static::$manager);
    }
    /**
     * Begin querying the model.
     *
     * @return \TurFramework\Database\QueryBuilder
     */
    public static function query()
    {
        $model = new static;

        return $model->newQueryBuilder()->setModel($model);
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
     * Set the connection resolver instance.
     *
     * @param  
     * @return void
     */
    public static function setManager($manager)
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
        return static::$method(...$parameters);
    }
}
