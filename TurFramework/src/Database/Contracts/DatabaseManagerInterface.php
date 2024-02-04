<?php

namespace TurFramework\Database\Contracts;

use TurFramework\Database\Model;


interface DatabaseManagerInterface
{

    /**
     * connect
     *
     * @return \PDO
     */
    public function connect(): \PDO;
    /**
     * Set a model instance for the model being queried.
     * 
     * @param \TurFramework\Database\Model $model
     * @return $this
     */
    public function setModel(Model $model);

    public function all();

    public function create(array $fields);

    public function update(array $fields);

    public function select($columns = ['*']);

    public function where($column, $operator = null, $value = null);

    public function orWhere($column, $operator = null, $value = null);

    public function first();

    public function get();

    public function find($id);
}
