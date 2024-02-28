<?php

namespace TurFramework\Auth;

use TurFramework\Support\Hash;

class AuthProvider
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
     * @param  mixed  $id 
     * @return \TurFramework\Database\Model|mixed
     */
    public function retrieveById($id)
    {
        return $this->newModelQuery()->find($id);
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
    public function getByCredentials(array $credentials)
    {
        $credentials = array_filter(
            $credentials,
            fn ($key) => !str_contains($key, 'password'),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($credentials)) {
            return;
        }


        $query = $this->newModelQuery();


        foreach ($credentials as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
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
