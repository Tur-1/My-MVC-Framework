<?php

namespace TurFramework\src\Support;

use ArrayAccess;

class Arr
{
    public static function only($array, array $keys)
    {
        return array_intersect_key($array, (array) array_flip($keys));
    }

    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}
