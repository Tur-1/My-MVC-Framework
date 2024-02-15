<?php

namespace TurFramework\Database\Managers;

use PDO;
use TurFramework\Support\Arr;
use TurFramework\Database\Model;
use TurFramework\Database\Grammars\MySQLGrammar;
use TurFramework\Database\Contracts\DatabaseManagerInterface;

class MySQLManager extends MySQLGrammar implements DatabaseManagerInterface
{

    /**
     * The model being queried.
     *
     * @var \TurFramework\Database\Model
     */
    protected $fetchMode = PDO::FETCH_CLASS;
    protected $model;


    /**
     * @var \PDO 
     */
    protected $connection;


    public function __construct($connection, $config)
    {
        $this->connection = $connection;
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

        return $this;
    }

    public function getModel()
    {
        return  $this->model;
    }


    /**
     * Run the query as a "select" statement against the connection.
     *
     * @return array
     */

    protected function runSelect()
    {

        $statement = $this->connection->prepare($this->readQuery());

        $this->bindValues($statement, $this->bindings);

        $statement->setFetchMode($this->fetchMode, get_class($this->getModel()));
        $statement->execute();

        return $statement->fetchAll();
    }

    private function getFields($fields)
    {
        return  $this->getModel()->getFillable() ? Arr::only($fields, $this->getModel()->getFillable()) : $fields;
    }
    public function create(array $fields)
    {

        $statement = $this->connection->prepare($this->insertQuery($this->getFields($fields)));

        $this->bindValues($statement, $this->bindings);
        return  $statement->execute();
    }


    public function update(array $fields)
    {

        $statement = $this->connection->prepare($this->updateQuery($this->getFields($fields)));

        $this->bindValues($statement, $this->bindings);
        return  $statement->execute();
    }

    public function get()
    {
        return $this->runSelect();
    }

    public function first()
    {
        return Arr::first($this->limit(1)->get(), default: []);
    }
    public function all()
    {
        return $this->get();
    }

    public function delete($id = null)
    {
        if (!is_null($id)) {
            $this->where('id', '=', $id);
        }

        $statement = $this->connection->prepare($this->deleteQuery());
        $this->bindValues($statement, $this->bindings);
        return $statement->execute();
    }
    public function find($id)
    {
        return $this->where('id', '=', $id)->first();
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

    /**
     * Add an "order by" clause to the query.
     *
     * @param  string  $column
     * @param  string  $direction
     * @return $this
     *
     */
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
    public function exstis($id = null)
    {
        if (!is_null($id)) {
            $this->where('id', $id);
        }


        $statement = $this->connection->prepare($this->existsQuery());

        $this->bindValues($statement, $this->bindings);

        $statement->execute();

        return $statement->fetchColumn() ? true : false;
    }
}
