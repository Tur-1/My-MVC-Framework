<?php

namespace TurFramework\Core\Session;

class SessionStore
{
    /**
     * Start the session.
     */
    public function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Put a value in the session.
     *
     * @param string $key
     * @param mixed $value
     * 
     * @return void
     */
    public function put(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a value from the session.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (isset($_SESSION['_flash'][$key])) {
            $sestion = $_SESSION['_flash'][$key];
            self::remove('_flash');
            return $sestion;
        }
        return $_SESSION[$key] ?? $default;
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
            return (bool) isset($_SESSION['_flash'][$key]);
        }
        return (bool) isset($_SESSION[$key]);
    }

    /**
     * Remove one or many items from the session.
     *
     * @param  array  $keys
     * @return void
     */
    public function forget(array $keys)
    {

        if (count($keys) === 0) {
            return;
        }
        foreach ($keys as $value) {
            unset($_SESSION[$value]);
        }
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
        unset($_SESSION[$key]);
    }


    /**
     * Make the value available for the next request.
     * (Flash message)
     * 
     * @param string $key
     * @param string|null $value
     * @return mixed
     */
    public function flash(string $key, $value = null)
    {
        $_SESSION['_flash'][$key] = $value;
    }
}
