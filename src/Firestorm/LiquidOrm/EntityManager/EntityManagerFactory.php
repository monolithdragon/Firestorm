<?php

declare(strict_types=1);

namespace Firestorm\LiquidOrm\EntityManager;

use Firestorm\LiquidOrm\EntityManager\Exception\CrudException;
use Firestorm\LiquidOrm\DataMapper\DataMapperInterface;
use Firestorm\LiquidOrm\QueryBuilder\QueryBuilderInterface;

class EntityManagerFactory
{
    /** @var DataMapperInterface */
    protected DataMapperInterface $dataMapper;

    /** @var QueryBuilderInterface */
    protected QueryBuilderInterface $queryBuilder;

    public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
    }

    public function create(string $crudClass, string $tableSchema, string $tabSchemaID): EntityManagerInterface
    {
        $crud = new $crudClass($this->dataMapper, $this->queryBuilder, $tableSchema, $tabSchemaID);
        if (!$crud instanceof CrudInterface) {
            throw new CrudException($crudClass . ' is not a valid crud object.');
        }

        return new EntityManager($crud);
    }
}
