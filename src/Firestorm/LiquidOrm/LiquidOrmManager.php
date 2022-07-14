<?php

declare(strict_types=1);

namespace Firestorm\LiquidOrm;

use Firestorm\DatabaseConnection\Database;
use Firestorm\LiquidOrm\DataMapper\DataMapperEnvironmentConfiguration;
use Firestorm\LiquidOrm\DataMapper\DataMapperFactory;
use Firestorm\LiquidOrm\EntityManager\Crud;
use Firestorm\LiquidOrm\EntityManager\EntityManagerFactory;
use Firestorm\LiquidOrm\QueryBuilder\QueryBuilder;
use Firestorm\LiquidOrm\QueryBuilder\QueryBuilderFactory;

class LiquidOrmManager
{
    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var array */
    protected array $options;

    /** @var DataMapperEnvironmentConfiguration */
    protected DataMapperEnvironmentConfiguration $environmentConfig;

    /**
     * Main class constructor
     *
     * @param  DataMapperEnvironmentConfiguration $environmentConfig
     * @param  string                             $tableSchema
     * @param  string                             $tableSchemaID
     * @param  array                              $options
     */
    public function __construct(DataMapperEnvironmentConfiguration $environmentConfig, string $tableSchema, string $tableSchemaID, array $options = [])
    {
        $this->environmentConfig = $environmentConfig;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->options = $options;
    }

    /**
     * Initialize method which glues all the components together and inject the necessary dependency within the 
     * respective object
     *
     * @return Object
     */
    public function initialize(): Object
    {
        $dataMapperFactory = new DataMapperFactory();
        $dataMapper = $dataMapperFactory->create(Database::class, $this->environmentConfig);
        if ($dataMapper) {
            $queryBulderFactory = new QueryBuilderFactory();
            $queryBulder = $queryBulderFactory->create(QueryBuilder::class);
            if ($queryBulder) {
                $entityManagerFactory = new EntityManagerFactory($dataMapper, $queryBulder);
                return $entityManagerFactory->create(Crud::class, $this->tableSchema, $this->tableSchemaID, $this->options);
            }
        }
    }
}
