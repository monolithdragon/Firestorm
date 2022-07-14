<?php

declare(strict_type=1);

namespace Firestorm\LiquidOrm\DataMapper;

use Firestorm\DatabaseConnection\DatabaseInterface;
use Firestorm\LiquidOrm\DataMapper\Exception\DataMapperException;

class DataMapperFactory
{
    public function create(string $databaseClass, string $dataMapperEnvironmentConfiguration): DataMapperInterface
    {
        $credentials = (new $dataMapperEnvironmentConfiguration([]))->getDatabaseCredentials('mysql');
        $database = new $databaseClass($credentials);
        if (!$database instanceof DatabaseInterface) {
            throw new DataMapperException($databaseClass . ' is not valid database connection object.');
        }

        return new DataMapper($database);
    }
}
