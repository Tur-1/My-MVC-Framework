<?php

namespace TurFramework\Database\Managers;

use TurFramework\Support\Arr;
use TurFramework\Database\Model;
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
     * @var ConnectionInterface $connection
     */
    protected $connection;

    protected $connectionName;

    protected $config;


    public function __construct(ConnectionInterface $connection, $config, $name)
    {
        $this->connection = $connection;
        $this->config = $config;
        $this->connectionName = $name;
    }
    public function create(array $attributes)
    {
        $model = $this->newModelInstance($attributes);

        return $model->save();
    }
    public function update(array $attributes)
    {
        $model = $this->newModelInstance($attributes, true);

        return $model->save();
    }

    public function performUpdate(array $attributes)
    {
        return $this->connection->update(
            $this->updateQuery($attributes),
            $this->prepareBindingsForUpdate($this->bindings, $attributes)
        );
    }

    public function prepareBindingsForUpdate($bindings, array $values)
    {
        return array_values(
            array_merge($values, Arr::flatten($bindings['where']))
        );
    }
    public function performInsert(array $attributes)
    {

        $this->connection->insert($this->insertQuery($attributes), $this->getBindings());

        return $this->connection->getPdo()->lastInsertId();
    }
    public function delete($id = null)
    {
        if (!is_null($id)) {
            $this->where('id', '=', $id);
        }

        return $this->connection->delete($this->deleteQuery(), $this->getBindings());
    }
    public function get()
    {
        $models = $this->connection->select($this->selectQuery(), $this->getBindings());

        return $this->getModel()->newCollection($models, $this->connectionName);
    }

    public function find($id)
    {
        return $this->where('id', '=', $id)->first();
    }
    public function first()
    {
        return  Arr::first($this->limit(1)->get());
    }
    public function exists($id = null)
    {
        if (!is_null($id)) {
            $this->where('id', $id);
        }
        return $this->connection->exists($this->existsQuery(), $this->getBindings());
    }
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
    protected function clearBindings()
    {
        $this->bindings = [];
        $this->wheres = [];
    }
    public function select($columns = ['*']): self
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->addComponent('select', 'SELECT ' .  implode(',', $columns));

        return $this;
    }


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

    public function orWhere($column, $operator = null, $value = null): self
    {
        if (is_array($column)) {

            $this->where($column, $operator, $value);
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
    public function whereIn($column, $values = []): self
    {
        $this->where($column, 'IN', $values, 'AND');
        return $this;
    }
    public function orWhereIn($column, $values = []): self
    {

        $this->where($column, 'IN', $values, 'OR');
        return $this;
    }

    public function whereNull($column): self
    {
        $this->where($column, 'IS NULL', null, 'AND');
        return $this;
    }
    public function orWhereNull($column): self
    {
        $this->where($column, 'IS NULL', null, 'OR');
        return $this;
    }

    public function whereNotNull($column): self
    {

        $this->where($column, 'IS NOT NULL', null);
        return $this;
    }
    public function orWhereNotNull($column): self
    {
        $this->where($column, 'IS NOT NULL', null, 'OR');
        return $this;
    }
    public function limit(int $number): self
    {

        $this->addComponent('limit', 'LIMIT ' .  $number);
        return $this;
    }

    public function table($table)
    {
        $this->addComponent('from', 'FROM ' . $table);

        $this->table = $table;
        return $this;
    }
    public function groupBy($column): self
    {
        $this->addComponent('groupBy', 'GROUP BY ' . $column);
        return $this;
    }
    public function orderBy($column, $direction = 'ASC'): self
    {

        $this->addComponent('orderBy', 'ORDER BY ' . $column . ' ' . $direction);
        return $this;
    }


    public function setModel(Model $model)
    {
        $this->model = $model;

        $this->addComponent('from', 'FROM ' . $this->model->getTable());
        $this->table = $this->model->getTable();

        return $this;
    }

    protected function getModel()
    {
        return  $this->model;
    }

    protected function newModelInstance($attributes = [], $exists = false)
    {
        return $this->getModel()->newInstance($attributes, $exists);
    }
}
