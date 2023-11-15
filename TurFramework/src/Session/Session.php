<?php

namespace TurFramework\src\Session;

class Session
{

    private static $session;

    public static function start()
    {
        if (self::$session === null) {
            self::$session = new Session();
        }

        return self::$session;
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function destroy()
    {
        session_destroy();
        self::$session = null;
    }
}
