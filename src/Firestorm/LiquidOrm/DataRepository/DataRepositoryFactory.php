<?php

declare(strict_types=1);

namespace Firestorm\LiquidOrm\DataRepository;

use Firestorm\LiquidOrm\DataMapper\DataMapperEnvironmentConfiguration;
use Firestorm\LiquidOrm\DataRepository\Exception\DataRepositoryException;
use Firestorm\LiquidOrm\EntityManager\EntityManager;
use Firestorm\LiquidOrm\LiquidOrmManager;

class DataRepositoryFactory
{
    protected string $tableSchema;
    protected string $tableSchemaID;
    protected string $crudIndentifier;

    public function __construct(string $crudIndentifier, string $tableSchema, string $tableSchemaID)
    {
        $this->crudIndentifier = $crudIndentifier;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    public function create(string $dataRepositoryClass): DataRepositoryInterface
    {
        $entityManager = $this->initializeLiquidOrmManager();
        $dataRepository = new $dataRepositoryClass($entityManager);
        if (!$dataRepository instanceof DataRepositoryInterface) {
            throw new DataRepositoryException($dataRepositoryClass .  ' is not a valid data repository.');
        }

        return $dataRepository;
    }

    private function initializeLiquidOrmManager()
    {
        $environmentConfiguration = new DataMapperEnvironmentConfiguration(['database']);
        $ormManager = new LiquidOrmManager($environmentConfiguration, $this->tableSchema, $this->tableSchemaID);
        return $ormManager->initialize();
    }
}
