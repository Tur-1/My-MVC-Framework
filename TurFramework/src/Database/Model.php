<?php

namespace TurFramework\Database;

class Model
{
    /**
     * @var mixed instance
     */
    protected static $instance;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;
    /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = 'updated_at';

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance =  new static;
        }

        return self::$instance;
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        $ins = explode('\\', get_class(self::$instance));
        $this->table = strtolower(end($ins));

        if (str_ends_with($this->table, 'y')) {
            $this->table = $this->table . 'ies';
        } else {

            $this->table .= 's';
        }

        return $this->table;
    }
}
