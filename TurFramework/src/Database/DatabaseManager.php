<?php

namespace TurFramework\Database;

use TurFramework\Database\Contracts\ConnectorInterface;

class DatabaseManager
{
    protected ConnectorInterface $connector;

    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    public function makeConnection()
    {
        return $this->connector->connect();
    }
}
