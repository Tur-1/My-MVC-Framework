<?php

namespace TurFramework\Database;

use PDO;
use TurFramework\Database\Grammars\Grammar;
use TurFramework\Database\Contracts\QueryBuilderInterface;


class QueryBuilder extends Grammar implements QueryBuilderInterface
{

    /**
     * The model being queried.
     *
     * @var \TurFramework\Database\Model
     */
    protected $model;
    protected $wheres = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;
    /**
     * 
     *
     * @var \PDO
     */
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    /**
     * Set a model instance for the model being queried.
     * @param \TurFramework\Database\Model
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        $this->table = $this->model->getTable();

        return $this;
    }


    public function create(array $fields)
    {
        $statement = $this->connection->prepare($this->insertStatement($fields));
        $this->bindValues($statement, $fields);
        return  $statement->execute();
    }

    /**
     * Update records in the database.
     *
     * @param  array  $fields
     * @return int
     */
    public function update(array $fields)
    {
        $statement = $this->connection->prepare($this->updateStatement($fields));
        $this->bindValues($statement, $fields);
        return  $statement->execute();
    }

    /**
     * Set the columns to be selected.
     *
     * @param  array|string $columns
     * @return $this
     */
    public function select($columns = ['*'])
    {
        $this->selectColumns($columns);

        return $this;
    }
    public function where($column, $operator = null, $value = null)
    {
        $this->wheres[] = [
            'type' => 'AND',
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];

        return $this;
    }
    public function orWhere($column, $operator = null, $value = null)
    {
        $this->wheres[] = [
            'type' => 'OR',
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];

        return $this;
    }

    public function get()
    {

        $statement = $this->connection->prepare($this->readStatement());

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, get_class($this->model));
    }
    public function all()
    {

        $statement = $this->connection->prepare($this->readStatement());

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, get_class($this->model));
    }

    public function find($id)
    {
        return $this->where('id', '=', $id)->get();
    }
}
