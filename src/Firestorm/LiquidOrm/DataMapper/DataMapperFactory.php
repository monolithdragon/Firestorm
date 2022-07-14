<?php

declare(strict_type=1);

namespace Firestorm\LiquidOrm\DataMapper;

use Firestorm\DatabaseConnection\DatabaseInterface;
use Firestorm\LiquidOrm\DataMapper\Exception\DataMapperException;

class DataMapperFactory
{
    /**
     * Creates the data mapper object and inject the dependency for this object. We are also
     * creating the Database Object
     * $dataMapperEnvironmentConfiguration get instantiated in the DataRepositoryFactory
     *
     * @param  string              $databaseClass
     * @param  Object              $dataMapperEnvironmentConfiguration
     * @return DataMapperInterface
     * @throws DataMapperException
     */
    public function create(string $databaseClass, Object $dataMapperEnvironmentConfiguration): DataMapperInterface
    {
        // Create databaseConnection Object and pass the database credentials in
        $credentials = $dataMapperEnvironmentConfiguration->getDatabaseCredentials('mysql');
        $database = new $databaseClass($credentials);
        if (!$database instanceof DatabaseInterface) {
            throw new DataMapperException($databaseClass . ' is not valid database connection object.');
        }

        return new DataMapper($database);
    }
}
