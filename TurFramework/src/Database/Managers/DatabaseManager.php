<?php

namespace TurFramework\Database\Managers;

use TurFramework\Database\Contracts\DatabaseManagerInterface;

class DatabaseManager
{
    protected DatabaseManagerInterface $manager;

    public function __construct(DatabaseManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getManager()
    {
        return $this->manager;
    }
}
