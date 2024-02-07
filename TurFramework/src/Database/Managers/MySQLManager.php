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
    protected $wheres;
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
    public function with($relation)
    {

        $this->eagerLoad[] =  $this->getModel()->newInstance()->$relation();


        return $this;
    }

    protected function loadRelation(&$models, $relation)
    {

        // foreach ($models as $model) {
        //     $model->relations = $relation->where('brand_id', $model->id)->get();
        // }
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

        if ($this->eagerLoad) {
            foreach ($this->eagerLoad as $relation) {
                $this->loadRelation($res, $relation);
            }
        }


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
    }

    public function find($id)
    {
        return $this->where('id', '=', $id)->first();
    }

    public function select($columns = ['*'])
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->setColumns($columns);

        return $this;
    }
    public function where($column, $operator = null, $value = null)
    {

        if (is_null($value)) {
            $value = $operator;
            $operator = '=';
        }
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
}
