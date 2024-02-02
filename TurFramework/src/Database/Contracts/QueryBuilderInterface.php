<?php

namespace TurFramework\Database\Contracts;


interface QueryBuilderInterface
{

    public function all();

    public function create(array $fields);

    public function update(array $fields);

    public function select($columns = ['*']);
}
