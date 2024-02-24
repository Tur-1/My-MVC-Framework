<?php

namespace TurFramework\Auth;

use TurFramework\Database\Contracts\ConnectionInterface;
use TurFramework\Support\Hash;

class UserProvider
{
    /**
     * The active database connection.
     * 
     */
    protected $model;


    /**
     * The table containing the users.
     *
     * @var string
     */
    protected $table;


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
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials 
     */
    public function retrieveByCredentials(array $credentials)
    {
        $this->model->query()->where('email', $credentials['email'])->where('email',);
    }


    /**
     * Validate a user against the given credentials.
     *
     * @param  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials($user, array $credentials)
    {
        return Hash::verify($credentials['password'], $user->getAuthPassword());
    }
}
