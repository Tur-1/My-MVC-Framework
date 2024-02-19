<?php

namespace TurFramework\Session;

use TurFramework\Support\Arr;
use TurFramework\Validation\MessageBag;
use SessionHandlerInterface;

class Store
{

    /**
     * The session attributes.
     *
     * @var array
     */
    protected $attributes = [];
    /**
     * The session handler implementation.
     *
     * @var \SessionHandlerInterface
     */
    protected $handler;
    /**
     * Session store started status.
     *
     * @var bool
     */
    protected $started = false;


    public function __construct()
    {
        $this->start();
    }

    /**
     * Start the session, reading the data from a handler.
     *
     * @return bool
     */
    public function start()
    {
        $this->loadSession();

        if (!$this->has('_token')) {
            $this->regenerateToken();
        }

        return $this->started = true;
    }

    /**
     * Load the session data from the handler.
     *
     * @return void
     */
    protected function loadSession()
    {
        $this->attributes = $_SESSION;

        $this->loadErrosMessages();
    }



    public function loadErrosMessages()
    {
        $errorBag = new MessageBag;

        foreach ($this->get('errors', []) as $key => $value) {
            $errorBag->add($key, $value);
        }

        $this->put('errors', $errorBag);
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
        if ($this->flashHas($key)) {
            $value = Arr::get($_SESSION['_flash'], $key, $default);
            unset($_SESSION['_flash'][$key]);
            return $value;
        }

        return Arr::get($_SESSION, $key, $default);
    }

    public function getOldValue($key, $default = null)
    {
        if ($this->flashHas('old')) {
            $value = Arr::get($_SESSION['_flash']['old'], $key, $default);
            unset($_SESSION['_flash']['old'][$key]);
            return $value;
        }
    }

    public function flashHas($key)
    {
        return isset($_SESSION['_flash'][$key]);
    }
    /**
     * Check if a key exists in the session.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        if ($this->flashHas($key)) {
            return true;
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
     * Get the CSRF token value.
     *
     * @return string
     */
    public function token()
    {
        return $this->get('_token');
    }

    /**
     * Regenerate the CSRF token value.
     *
     * @return void
     */
    public function regenerateToken()
    {
        $this->put('_token', bin2hex(random_bytes(32)));
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
