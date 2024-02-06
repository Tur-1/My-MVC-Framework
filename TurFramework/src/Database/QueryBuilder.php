<?php

namespace TurFramework\Database;

use TurFramework\Database\Contracts\DatabaseManagerInterface;


class QueryBuilder
{

    /**
     * @var \TurFramework\Database\Contracts\DatabaseManagerInterface
     */
    protected $manager;

    public function __construct(DatabaseManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function makeConnection($connection =null)
    {
       
        $this->manager->connect($connection);

        return $this;
    }
    /**
     * Set a model instance for the model being queried.
     * 
     * @param \TurFramework\Database\Model $model
     * @return $this
     */
    public function setModel(Model $model)
    {

        $this->manager->setModel($model);

        return $this;
    }


    public function create(array $fields)
    {
        return $this->manager->create($fields);
    }


    public function update(array $fields)
    {
        return $this->manager->update($fields);
    }


    public function select($columns = ['*'])
    {
        $this->manager->select($columns);

        return $this;
    }
    public function where($column, $operator = null, $value = null)
    {

        $this->manager->where($column, $operator, $value);

        return $this;
    }
    public function orWhere($column, $operator = null, $value = null)
    {
        $this->manager->orWhere($column, $operator, $value);

        return $this;
    }

    public function get()
    {
        return $this->manager->get();
    }

    public function first()
    {
        return $this->manager->first();
    }
    public function all()
    {
        return $this->manager->all();
    }

    public function find($id)
    {
        return $this->manager->find($id);
    }
}
