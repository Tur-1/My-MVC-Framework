<?php

namespace TurFramework\Auth;

use TurFramework\Support\Hash;
use TurFramework\Database\Model;
use TurFramework\Database\Contracts\ConnectionInterface;

class UserProvider
{
    /**
     * The active database connection.
     * 
     */
    protected $model;




    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier 
     */
    public function retrieveById($id)
    {
    }
    /**
     * Create a new instance of the model.
     *
     * @return \TurFramework\Database\Model
     */
    public function createModel()
    {
        $class = '\\' . ltrim($this->model, '\\');

        return new $class;
    }
    /**
     * Get a new query builder for the model instance.
     *
     * @param \TurFramework\Database\Model|null  $model
     * @return \TurFramework\Database\Contracts\DatabaseManagerInterface
     */
    protected function newModelQuery($model = null)
    {
        $query = is_null($model)
            ? $this->createModel()->newQuery()
            : $model->newQuery();

        return $query;
    }
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials 
     */
    public function retrieveByCredentials(array $credentials)
    {
        return $this->newModelQuery()->where('email', $credentials['email'])->first();
    }


    /**
     * Gets the name of the Eloquent user model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Validate a user password against the given password.
     *
     * @param string $userPassword
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $userPassword, string $password)
    {
        return Hash::verify($password, $userPassword);
    }
}
