<?php

namespace TurFramework\Database\Managers;

use PDO;
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
    protected $model;
    protected $columns = '*';
    protected $limit;
    protected $table;
    /**
     * The relationships that should be eager loaded.
     *
     * @var array
     */
    protected $eagerLoad = [];

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

    public function create(array $fields)
    {

        $statement = $this->connection->prepare($this->insertQuery($fields));

        $this->bindValues($statement, $fields);
        return  $statement->execute();
    }


    public function update(array $fields)
    {
        $statement = $this->connection->prepare($this->updateQuery($fields));
        $this->bindValues($statement, $fields);
        return  $statement->execute();
    }

    public function get()
    {


        $statement = $this->connection->prepare($this->readQuery());
        $this->bindValues($statement, $this->getWhereValues());
        $statement->execute();

        $res = $statement->fetchAll(PDO::FETCH_CLASS, get_class($this->model));

        return $res;
    }

    public function first()
    {

        $statement = $this->connection->prepare($this->readQuery());

        $this->bindValues($statement, $this->getWhereValues());

        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, get_class($this->model));

        $res = $statement->fetch();

        return $res;
    }
    public function all()
    {
        return $this->get();
    }

    public function delete()
    {
        $statement = $this->connection->prepare($this->deleteQuery());
        $this->bindValues($statement, $this->getWhereValues());
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
        $this->addWhere($column, $operator, $value, $type);
        return $this;
    }
    public function orWhere($column, $operator = null, $value = null): self
    {
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

    public function whereNotNull($column): self
    {
        $this->where($column, 'IS NOT NULL', 'null');
        return $this;
    }
    public function limit(int $number): self
    {
        $this->setQueryLimit($number);
        return $this;
    }
}
