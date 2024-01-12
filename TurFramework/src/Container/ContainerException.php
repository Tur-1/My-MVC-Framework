<?php

namespace TurFramework\Container;

use Exception;

class ContainerException extends Exception
{

    public static function invalidParam($abstract, $name)
    {
        return new self('Failed to resolve class "' . $abstract . '" because invalid param "' . $name . '"');
    }
    public static function unionType($abstract, $name)
    {
        return new self('Failed to resolve class "' . $abstract . '" because of union type for param "' . $name . '"');
    }

    public static function missingTypeHint($abstract, $name)
    {
        return new self('Failed to resolve class "' . $abstract . '" because param "' . $name . '" is missing a type hint');
    }
}
