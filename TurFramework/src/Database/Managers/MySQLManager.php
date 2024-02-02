<?php

namespace TurFramework\Database\Managers;

use PDO;
use TurFramework\Database\Model;
use TurFramework\Database\Connectors\MySQLConnector;
use TurFramework\Database\Grammars\MySqlQueryBuilder;
use TurFramework\Database\Contracts\DatabaseManagerInterface;

class MySQLManager
{

    /**
     * The base query builder instance.
     *
     * @var \TurFramework\Database\Grammars\MySqlQueryBuilder
     */
    protected $query;
    /**
     * The model being queried.
     *
     * @var \TurFramework\Database\Model
     */
    protected $model;

    protected $table;
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

    public function getModel()
    {
        return $this->model;
    }
    /**
     * select
     *
     * @return $this
     */
    public function select()
    {
        $this->query->setColumns($columns);
        return $this;
    }

    public function create(array $fields)
    {

        // $statement = $this->connection->prepare($this->query->createQuery($fields));

        // $this->query->bindValues($statement, $fields);

        // return $statement->execute();
    }


    public function all()
    {

        $statement = $this->connection->prepare('SELECT * FROM ' . $this->table);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, get_class($this->model));
    }
}
