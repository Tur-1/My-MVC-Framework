<?php

namespace TurFramework\Database\Concerns;

trait HasRelationships
{
    /**
     * The loaded relationships for the model.
     *
     * @var array
     */
    protected $relations = [];
}
