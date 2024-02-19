<?php

namespace TurFramework\Database\Contracts;

use TurFramework\Database\Model;


interface DatabaseManagerInterface
{
    /**
     * Set a model instance for the model being queried.
     * 
     * @param \TurFramework\Database\Model $model
     * @return $this
     */
    public function setModel(Model $model);

    public function get();

    public function create(array $fields);

    public function update(array $fields);

    public function delete($id = null);

    public function first();

    public function find($id);

    public function select($columns = ['*']): self;

    public function limit(int $number): self;

    public function where($column, $operator = null, $value = null): self;

    public function orWhere($column, $operator = null, $value = null): self;

    public function whereIn($column, $values = []): self;

    public function orWhereIn($column, $values = []): self;

    public function whereNull($column): self;

    public function orWhereNull($column): self;

    public function whereNotNull($column): self;

    public function orWhereNotNull($column): self;

    public function orderBy($column, $direction = 'ASC'): self;

    public function exstis($id = null);

    public function table($table);

    public function preformUpdate(array $attributes);

    public function insert(array $attributes);
}
