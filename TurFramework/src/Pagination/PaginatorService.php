<?php

namespace TurFramework\Pagination;

class PaginatorService
{



    public static function resolveCurrentPage($pageName, $default = 1)
    {
        return (int) request()->get($pageName) ?: $default;
    }
}
