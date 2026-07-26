<?php

declare(strict_types=1);

namespace bakery\core;

use bakery\core\traits\Singleton;

class Shop
{
    use Singleton;

    public static $instance;

    protected function __construct()
    {

    }

    public function insert(string $tableName, array $values=[]): void
    {
        Database::update(
            Database::DO_INSERT,
            $tableName,
            $values);
    }

    public function update(string $tableName, array $values=[], string $where=""): void
    {
        Database::update(
            Database::DO_UPDATE,
            $tableName,
            $values,
            $where);
    }

}
