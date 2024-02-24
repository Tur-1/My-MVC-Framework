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

    /**
     *  first 
     *  
     * @return \TurFramework\Database\Model
     */
    public function get();

    public function create(array $fields);

    public function update(array $fields);

    public function delete($id = null);

    /**
     *  first 
     *  
     * @return \TurFramework\Database\Model
     */
    public function first();
    /**
     *  first 
     *  
     * @return \TurFramework\Database\Model
     */
    public function find($id);

    public function exstis($id = null);

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

    public function table($table);

    public function groupBy($column): self;

    public function performUpdate(array $attributes);

    public function performInsert(array $attributes);
}
