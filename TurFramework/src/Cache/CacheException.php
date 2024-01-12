<?php

namespace TurFramework\Cache;

use Exception;

class CacheException extends Exception
{

    public static function fileNotFound($file)
    {
        return new self("File [ $file ] not found", 404);
    }
}
