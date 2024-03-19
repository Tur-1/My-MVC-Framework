<?php

namespace TurFramework\Database\Managers;

use TurFramework\Support\Arr;
use TurFramework\Database\Model;
use TurFramework\Pagination\Paginator;
use TurFramework\Pagination\PaginatorService;
use TurFramework\Database\Grammars\MySQLGrammar;
use TurFramework\Database\Contracts\ConnectionInterface;
use TurFramework\Database\Contracts\DatabaseManagerInterface;

class MySQLManager extends MySQLGrammar implements DatabaseManagerInterface
{
    /**
     * The model being queried.
     *
     * @var \TurFramework\Database\Model
     */
    protected $model;

    /**
     * The database connection.
     *
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * The name of the database connection.
     *
     * @var string
     */
    protected $connectionName;

    /**
     * The database configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * 
     *
     * @param ConnectionInterface $connection
     * @param mixed $config
     * @param string $name
     */
    public function __construct(ConnectionInterface $connection, $config, $name)
    {
        $this->connection = $connection;
        $this->config = $config;
        $this->connectionName = $name;
    }

    /**
     * Creates a new record in the database.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        $model = $this->newModelInstance($attributes, $this->connectionName);

        return $model->save();
    }

    /**
     * Updates records in the database.
     *
     * @param array $attributes
     * @return Model
     */
    public function update(array $attributes): Model
    {
        $model = $this->newModelInstance($attributes, $this->connectionName, true);

        return $model->save();
    }

    /**
     * Performs an update operation.
     *
     * @param array $attributes
     * @return mixed
     */
    public function performUpdate(array $attributes)
    {
        return $this->connection->update(
            $this->updateQuery($attributes),
            $this->prepareBindingsForUpdate($this->bindings, $attributes)
        );
    }
    /**
     * Prepares the bindings for an update operation.
     *
     * @param array $bindings
     * @param array $values
     * @return array
     */
    private function prepareBindingsForUpdate($bindings, array $values)
    {
        return array_values(
            array_merge($values, Arr::flatten($bindings['where']))
        );
    }


    /**
     * Inserts a new record into the database.
     *
     * @param array $attributes
     * @return bool
     */
    public function insert(array $attributes): bool
    {
        return $this->connection->insert($this->insertQuery($attributes), $this->getBindings());
    }

    /**
     * Inserts a new record into the database and retrieves the last inserted ID.
     *
     * @param array $attributes
     * @return int|string
     */
    public function insertAndGetId(array $attributes): int|string
    {
        $this->connection->insert($this->insertQuery($attributes), $this->getBindings());

        $id = $this->connection->getPdo()->lastInsertId();

        return is_numeric($id) ? (int) $id : $id;
    }
    /**
     * Deletes records from the database.
     *
     * @param mixed $id
     * @return bool 
     */
    public function delete($id = null): bool
    {
        if (!is_null($id)) {
            $this->where('id', '=', $id);
        }

        return $this->connection->delete($this->deleteQuery(), $this->getBindings());
    }
    /**
     * Paginate the given query.
     *
     */
    public function paginate($perPage = 10, $pageName = 'page')
    {

        $page = PaginatorService::resolveCurrentPage($pageName);

        $total = $this->count();
        $data =  $this->skip(($page - 1) * $perPage)->limit($perPage)->get();

        return new Paginator($data, $total, $perPage, $page, $pageName);
    }
    /**
     * Get the count of the total records.
     *
     * @param  array  $columns
     * @return int
     */
    public function count($columns = ['*'])
    {
        $this->select($columns);
        $statement = $this->connection->statement($this->countQuery(), $this->getBindings());

        return $statement->fetchColumn();
    }
    /**
     * Alias to set the "offset" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function skip($value)
    {
        $this->addComponent('offset', 'offset ' . $value);

        return $this;
    }
    /**
     * Retrieves records from the database.
     *
     * @return array
     */
    public function get(): array
    {
        $models = $this->connection->select($this->selectQuery(), $this->getBindings());

        return $this->getModel()
            ->newCollection($models, $this->connectionName);
    }

    /**
     * Finds a record by its ID.
     *
     * @param mixed $id
     * @return Model|null
     */
    public function find($id): Model|null
    {
        return $this->where('id', '=', $id)->first();
    }

    /**
     * Retrieves the first record.
     *
     * @return Model|null
     */
    public function first(): Model|null
    {
        return Arr::first($this->limit(1)->get());
    }
    /**
     * Checks if a record exists.
     *
     * @param mixed $id
     * @return bool
     */
    public function exists($id = null): bool
    {
        if (!is_null($id)) {
            $this->where('id', $id);
        }

        $statement = $this->connection->statement($this->existsQuery(), $this->getBindings());

        return (bool) $statement->fetchColumn();
    }
    /**
     * Selects specific columns to retrieve.
     *
     * @param array $columns
     * @return $this
     */
    public function select($columns = ['*']): self
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->addComponent('select', 'SELECT ' .  implode(',', $columns));

        return $this;
    }

    /**
     * Adds a "where" clause to the query.
     *
     * @param mixed $column
     * @param string|null $operator
     * @param mixed|null $value
     * @param string $type
     * @return self
     */
    public function where($column, $operator = null, $value = null, $type = 'AND'): self
    {
        if (is_array($column)) {
            $this->addArrayOfWheres($column, $type);
            $this->addComponent('where', 'WHERE ' . $this->compileWhere());
            return $this;
        }

        [$value, $operator] = $this->prepareValueAndOperator(
            $value,
            $operator,
            func_num_args() === 2 && is_null($value)
        );


        $this->wheres[] = [
            'type' => $type,
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];


        $this->addComponent('where', 'WHERE ' . $this->compileWhere());


        return $this;
    }

    /**
     * Conditionally applies a callback to the query.
     *
     * @param mixed $value
     * @param callable $callback
     * @return self
     */
    public function when($value, callable $callback): self
    {
        if ($value) {
            return $callback($this) ?? $this;
        }

        return $this;
    }

    /**
     * Adds an "OR where" clause to the query.
     *
     * @param mixed $column
     * @param string|null $operator
     * @param mixed|null $value
     * @return self
     */
    public function orWhere($column, $operator = null, $value = null): self
    {
        if (is_array($column)) {

            $this->where($column);
            return $this;
        }

        [$value, $operator] = $this->prepareValueAndOperator(
            $value,
            $operator,
            func_num_args() === 2 && is_null($value)
        );

        $this->where($column, $operator, $value, 'OR');
        return $this;
    }

    /**
     * Adds a "where in" clause to the query.
     *
     * @param string $column
     * @param array $values
     * @return self
     */
    public function whereIn($column, $values = []): self
    {
        $this->where($column, 'IN', $values, 'AND');
        return $this;
    }


    /**
     * Adds an "OR where in" clause to the query.
     *
     * @param string $column
     * @param array $values
     * @return self
     */
    public function orWhereIn($column, $values = []): self
    {

        $this->where($column, 'IN', $values, 'OR');
        return $this;
    }

    /**
     * Adds a "where null" clause to the query.
     *
     * @param string $column
     * @return self
     */
    public function whereNull($column): self
    {
        $this->where($column, 'IS NULL', null, 'AND');
        return $this;
    }
    /**
     * Adds an "OR where null" clause to the query.
     *
     * @param string $column
     * @return self
     */
    public function orWhereNull($column): self
    {
        $this->where($column, 'IS NULL', null, 'OR');
        return $this;
    }
    /**
     * Adds a "where not null" clause to the query.
     *
     * @param string $column
     * @return self
     */
    public function whereNotNull($column): self
    {

        $this->where($column, 'IS NOT NULL', null);
        return $this;
    }
    /**
     * Adds an "OR where not null" clause to the query.
     *
     * @param string $column
     * @return self
     */
    public function orWhereNotNull($column): self
    {
        $this->where($column, 'IS NOT NULL', null, 'OR');
        return $this;
    }

    /**
     * Sets the limit for the query.
     *
     * @param int $number
     * @return self
     */
    public function limit(int $number): self
    {

        $this->addComponent('limit', 'LIMIT ' .  $number);
        return $this;
    }
    /**
     * Sets the table for the query.
     *
     * @param string $table
     * @return self
     */
    public function table($table): self
    {
        $this->addComponent('from', 'FROM ' . $table);

        $this->table = $table;
        return $this;
    }

    /**
     * Groups the query results by a given column.
     *
     * @param string $column
     * @return self
     */
    public function groupBy($column): self
    {
        $this->addComponent('groupBy', 'GROUP BY ' . $column);
        return $this;
    }

    /**
     * Orders the query results by a given column.
     *
     * @param string $column
     * @param string $direction
     * @return self
     */
    public function orderBy($column, $direction = 'ASC'): self
    {

        $this->addComponent('orderBy', 'ORDER BY ' . $column . ' ' . $direction);
        return $this;
    }


    /**
     * Sets the model instance for the query.
     *
     * @param Model $model
     * @return self
     */
    public function setModel(Model $model): self
    {
        $this->model = $model;
        $this->addComponent('from', 'FROM ' . $this->model->getTable());
        $this->table = $this->model->getTable();
        return $this;
    }

    /**
     * Retrieves the model instance for the query.
     *
     * @return Model
     */
    protected function getModel()
    {
        return  $this->model;
    }

    /**
     * Creates a new model instance.
     *
     * @param array $attributes
     * @param mixed $connection
     * @param bool $exists
     * @return Model
     */
    protected function newModelInstance($attributes = [], $connection = null, $exists = false)
    {
        return $this->getModel()->newInstance($attributes, $connection, $exists);
    }

    /**
     * Retrieves the bindings for the query.
     *
     * @return array
     */
    protected function getBindings()
    {
        $bindings = [];

        foreach ($this->bindings as $key => $value) {
            if (!empty($value)) {
                $bindings[$key] = $value;
            }
        }
        $this->clearBindings();

        return Arr::flatten($bindings);
    }
    /**
     * Clears the bindings for the query.
     *
     * @return void
     */
    protected function clearBindings()
    {
        $this->bindings = [];
        $this->wheres = [];
    }
}
