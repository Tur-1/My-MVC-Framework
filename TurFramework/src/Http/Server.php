<?php

namespace TurFramework\Http;

class Server
{
    /**
     * Retrieve a specific value from the server array.
     *
     * @param string $key The key to retrieve from $_SERVER.
     * @return mixed|null The value if found, otherwise null.
     */
    public static function get($key): mixed
    {
        return static::has($key) ? $_SERVER[$key] : null;
    }

    /**
     * Check if a specific key exists in the server array.
     *
     * @param string $key The key to check.
     * @return bool True if the key exists, otherwise false.
     */
    public static function has($key): bool
    {
        return isset($_SERVER[$key]);
    }

    /**
     * Get the entire server array.
     *
     * @return array The $_SERVER array.
     */
    public static function all(): array
    {
        return $_SERVER;
    }
}
