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


    public function __construct(ConnectionInterface $connection, $config)
    {
        $this->connection = $connection;
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
        return $this->connection->update($this->updateQuery($attributes), $this->bindings);
    }
    public function performInsert(array $attributes)
    {
        return $this->connection->insert($this->insertQuery($attributes), $this->bindings);
    }
    public function delete($id = null)
    {
        if (!is_null($id)) {
            $this->where('id', '=', $id);
        }

        return $this->connection->delete($this->deleteQuery(), $this->bindings);
    }
    public function get()
    {
        $models = $this->connection->select($this->selectQuery(), $this->bindings);
        foreach ($models as $key => &$model) {
            $model->exists = true;
        }
        return $models;
    }

    /**
     * @return \TurFramework\Database\Model
     */
    public function first()
    {
        return Arr::first($this->limit(1)->get(), default: []);
    }

    public function all()
    {
        return $this->get();
    }



    public function find($id)
    {
        return $this->where('id', '=', $id)->first();
    }
    public function exstis($id = null)
    {
        if (!is_null($id)) {
            $this->where('id', $id);
        }

        return $this->connection->exstis($this->existsQuery(), $this->bindings);
    }
    public function select($columns = ['*']): self
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->setColumns($columns);

        return $this;
    }
    public function where($column, $operator = null, $value = null, $type = 'AND'): self
    {
        $this->addWhere(...func_get_args());
        return $this;
    }
    public function orWhere($column, $operator = null, $value = null): self
    {
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
        $this->where($column, 'IS NULL', 'null');
        return $this;
    }
    public function orWhereNull($column): self
    {
        $this->where($column, 'IS NULL', 'null', 'OR');
        return $this;
    }

    public function whereNotNull($column): self
    {

        $this->where($column, 'IS NOT NULL', 'null');
        return $this;
    }
    public function orWhereNotNull($column): self
    {
        $this->where($column, 'IS NOT NULL', 'null', 'OR');
        return $this;
    }

    public function limit(int $number): self
    {
        $this->setQueryLimit($number);
        return $this;
    }

    public function orderBy($column, $direction = 'ASC'): self
    {
        $this->setOrderBy($column, $direction);
        return $this;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }
    public function groupBy($column): self
    {
        $this->setGroupBy($column);
        return $this;
    }
    /**
     * Set a model instance for the model being queried.
     * 
     * @param \TurFramework\Database\Model
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        $this->table = $this->model->getTable();

        $this->connection->setFetchMode(get_class($this->model));

        return $this;
    }

    public function getModel()
    {
        return  $this->model;
    }


    public function newModelInstance($attributes = [], $exists = false)
    {
        return $this->model->newInstance($attributes, $exists);
    }
}
