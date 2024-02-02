<?php

namespace TurFramework\Database\Contracts;

interface ConnectorInterface
{

    /**
     * connect
     *
     * @return \PDO
     */
    public function connect(): \PDO;
}
