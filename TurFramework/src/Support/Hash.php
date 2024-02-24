<?php

namespace TurFramework\Support;

class Hash
{
    public static function password($value)
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    public static function make($value)
    {
        return sha1($value . time());
    }

    public static function verify($value, $hashedValue)
    {
        return password_verify($value, $hashedValue);
    }

    /**
     * Determine if a given string is already hashed.
     *
     * @param  string  $value
     * @return bool
     */
    public static function isHashed($value)
    {
        return password_get_info($value)['algo'] !== null;
    }
}
