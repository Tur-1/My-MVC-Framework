<?php

namespace TurFramework\Database\Contracts;

use TurFramework\Database\Model;


interface DatabaseManagerInterface
{
    /**
     * Creates a new record in the database.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * Updates records in the database.
     *
     * @param array $attributes
     * @return Model
     */
    public function update(array $attributes): Model;

    /**
     * Performs an update operation.
     *
     * @param array $attributes
     * @return mixed
     */
    public function performUpdate(array $attributes);

    /**
     * Inserts a new record into the database.
     *
     * @param array $attributes
     * @return bool
     */
    public function insert(array $attributes): bool;

    /**
     * Inserts a new record into the database and retrieves the last inserted ID.
     *
     * @param array $attributes
     * @return int|string
     */
    public function insertAndGetId(array $attributes): int|string;
    /**
     * Deletes records from the database.
     *
     * @param mixed $id
     * @return bool 
     */
    public function delete($id = null): bool;

    /**
     * Retrieves records from the database.
     *
     * @return array
     */
    public function get(): array;

    /**
     * Finds a record by its ID.
     *
     * @param mixed $id
     * @return Model|null
     */
    public function find($id): Model|null;
    /**
     * Retrieves the first record.
     *
     * @return Model|null
     */
    public function first(): Model|null;
    /**
     * Checks if a record exists.
     *
     * @param mixed $id
     * @return bool
     */
    public function exists($id = null): bool;
    /**
     * Selects specific columns to retrieve.
     *
     * @param array $columns
     * @return $this
     */
    public function select($columns = ['*']): self;

    /**
     * Adds a "where" clause to the query.
     *
     * @param mixed $column
     * @param string|null $operator
     * @param mixed|null $value
     * @param string $type
     * @return self
     */
    public function where($column, $operator = null, $value = null, $type = 'AND'): self;

    /**
     * Conditionally applies a callback to the query.
     *
     * @param mixed $value
     * @param callable $callback
     * @return self
     */
    public function when($value, callable $callback): self;
    /**
     * Adds an "OR where" clause to the query.
     *
     * @param mixed $column
     * @param string|null $operator
     * @param mixed|null $value
     * @return self
     */
    public function orWhere($column, $operator = null, $value = null): self;
    /**
     * Adds a "where in" clause to the query.
     *
     * @param string $column
     * @param array $values
     * @return self
     */
    public function whereIn($column, $values = []): self;


    /**
     * Adds an "OR where in" clause to the query.
     *
     * @param string $column
     * @param array $values
     * @return self
     */
    public function orWhereIn($column, $values = []): self;

    /**
     * Adds a "where null" clause to the query.
     *
     * @param string $column
     * @return self
     */
    public function whereNull($column): self;
    /**
     * Adds an "OR where null" clause to the query.
     *
     * @param string $column
     * @return self
     */
    public function orWhereNull($column): self;
    /**
     * Adds a "where not null" clause to the query.
     *
     * @param string $column
     * @return self
     */
    public function whereNotNull($column): self;
    /**
     * Adds an "OR where not null" clause to the query.
     *
     * @param string $column
     * @return self
     */
    public function orWhereNotNull($column): self;

    /**
     * Sets the limit for the query.
     *
     * @param int $number
     * @return self
     */
    public function limit(int $number): self;
    /**
     * Sets the table for the query.
     *
     * @param string $table
     * @return self
     */
    public function table($table): self;
    /**
     * Groups the query results by a given column.
     *
     * @param string $column
     * @return self
     */
    public function groupBy($column): self;
    /**
     * Orders the query results by a given column.
     *
     * @param string $column
     * @param string $direction
     * @return self
     */
    public function orderBy($column, $direction = 'ASC'): self;

    /**
     * Sets the model instance for the query.
     *
     * @param Model $model
     * @return self
     */
    public function setModel(Model $model): self;
}
