<?php

namespace TurFramework\Session;

use TurFramework\Support\Arr;
use TurFramework\Validation\MessageBag;

class Session
{
    /**
     * The session name.
     *
     * @var string
     */
    protected $name;

    /**
     * The session attributes.
     *
     * @var array
     */
    protected $attributes = [];



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
    }
    /**
     * Get the name of the session.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of the session.
     *
     * @param  string  $name
     * @return void
     */
    public function setName($name)
    {

        $this->name = $name;
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

    /**
     * Flush the session data and regenerate the ID.
     *
     * @return bool
     */
    public function invalidate()
    {
        $this->flush();

        $this->regenerateSessionId();

        $this->regenerateToken();
    }

    /**
     * Remove all of the items from the session.
     *
     * @return void
     */
    public function flush()
    {
        $this->attributes = [];
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
            Arr::set($this->attributes, $arrayKey, $arrayValue);
        }
    }
    /**
     * Get all of the session data.
     *
     * @return array
     */
    public function all()
    {
        return $this->attributes;
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
            $value = Arr::get($this->attributes['_flash'], $key, $default);
            unset($this->attributes['_flash'][$key]);
            return $value;
        }

        return Arr::get($this->attributes, $key, $default);
    }

    public function getOldValue($key, $default = null)
    {
        if ($this->flashHas('old')) {
            $value = Arr::get($this->attributes['_flash']['old'], $key, $default);
            unset($this->attributes['_flash']['old'][$key]);
            return $value;
        }
    }

    public function flashHas($key)
    {
        return isset($this->attributes['_flash'][$key]);
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
        return Arr::exists($this->attributes, $key);
    }

    /**
     * Remove one or many items from the session.
     *
     * @param  array  $keys
     * @return void
     */
    public function forget(array $keys)
    {
        Arr::forget($this->attributes, $keys);
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

        Arr::forget($this->attributes, $key);
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
     * Generate a new session identifier.
     *
     * @param  bool  $destroy
     * @return bool
     */
    public function regenerate()
    {
        $this->regenerateSessionId();

        $this->regenerateToken();
    }
    /**
     * Get a new, random session ID.
     *
     * @return string
     */
    protected function regenerateSessionId()
    {
        return  session_regenerate_id(true);
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

    public function __destruct()
    {

        $_SESSION = $this->attributes;
    }
}
