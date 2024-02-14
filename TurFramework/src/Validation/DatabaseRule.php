<?php

namespace TurFramework\Validation;

use TurFramework\Database\Model;

trait DatabaseRule
{

    public function resolveTable($table)
    {

        if (str_contains('.', $table)) {
            $exploded = explode('.', $table);
            $connection = $exploded[0];
            $table = end($exploded);

            return  [$connection, $table];
        }

        return  [$connection = null, $table];
    }


    public function resolveCloumn($field, $cloumn)
    {
        return is_null($cloumn) ? $field : $cloumn;
    }
}
