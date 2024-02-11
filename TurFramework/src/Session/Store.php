<?php

namespace TurFramework\Session;

use TurFramework\Support\Arr;

class Store
{
    /**
     * The session attributes.
     *
     * @var array
     */
    protected $attributes = [];


    public function __construct()
    {
        $this->attributes = $_SESSION;
    }
    /**
     * Put a value in the session.
     *
     * @param string $key
     * @param mixed $value
     * 
     * @return void
     */
    public function put($key, $value = null)
    {
        if (!is_array($key)) {
            $key = [$key => $value];
        }

        foreach ($key as $arrayKey => $arrayValue) {
            Arr::set($_SESSION, $arrayKey, $arrayValue);
        }
    }
    /**
     * Get all of the session data.
     *
     * @return array
     */
    public function all()
    {
        return  $_SESSION;
    }

    /**
     * Get an item from the session.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {

        if ($this->hasflash($key)) {
            $sestion = Arr::get($_SESSION['_flash'], $key, $default);
            $this->remove('_flash');
            return $sestion;
        }
        return Arr::get($_SESSION, $key, $default);
    }

    private function hasflash($key)
    {
        return isset($_SESSION['_flash']) ? Arr::get($_SESSION['_flash'], $key) : false;
    }
    /**
     * Check if a key exists in the session.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        if (isset($_SESSION['_flash'][$key])) {
            return Arr::exists($_SESSION['_flash'], $key);
        }
        return Arr::exists($_SESSION, $key);
    }

    /**
     * Remove one or many items from the session.
     *
     * @param  array  $keys
     * @return void
     */
    public function forget(array $keys)
    {
        Arr::forget($_SESSION, $keys);
    }


    /**
     *  Remove a value from the session.
     *
     * @param string key
     *
     * @return void
     */
    public function remove(string $key)
    {

        Arr::forget($_SESSION, $key);
    }


    /**
     * Make the value available for the next request.
     * (Flash message)
     * 
     * @param string $key
     * @param string|null $value
     * @return mixed
     */
    public function flash($key, $value = null)
    {

        if (!is_array($key)) {
            $key = [$key => $value];
        }
        $this->put('_flash', $key);
    }
}
