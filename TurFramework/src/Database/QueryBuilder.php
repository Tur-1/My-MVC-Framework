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
    protected $columns = '*';
    protected $limit;
    protected $table;
    protected $wheres;
    /**
     * All of the available clause operators.
     *
     * @var string[]
     */
    public $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>', '&~', 'is', 'is not',
        'rlike', 'not rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    /**
     * All of the available bitwise operators.
     *
     * @var string[]
     */
    public $bitwiseOperators = [
        '&', '|', '^', '<<', '>>', '&~',
    ];
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

    public function get()
    {

        $statement = $this->connection->prepare($this->readStatement());

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, get_class($this->model));
    }

    public function first()
    {
        [$sql,  $wheresParams] = $this->buildWhereClause($this->readStatement());

        $statement = $this->connection->prepare($sql);

        $this->bindValues($statement, $wheresParams);

        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, get_class($this->model));

        return $statement->fetch();
    }
    public function all()
    {

        $statement = $this->connection->prepare($this->readStatement());

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, get_class($this->model));
    }

    /**
     * Execute a query for a single record by ID.
     *
     * @param  int|string  $id
     * @param  array|string  $columns
     * @return mixed|static
     */
    public function find($id)
    {
        return $this->where('id', '=', $id)->first();
    }
}
