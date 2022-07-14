<?php

declare(strict_types=1);

namespace Firestorm\LiquidOrm\EntityManager;

use Firestorm\LiquidOrm\DataMapper\DataMapper;
use Firestorm\LiquidOrm\QueryBuilder\QueryBuilder;
use Throwable;

class Crud implements CrudInterface
{
    /** @var DataMapper */
    protected DataMapper $dataMapper;

    /** @var QueryBuilder */
    protected QueryBuilder $queryBuilder;

    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /**
     * Main constructor
     *
     * @param DataMapper $dataMapper
     * @param QueryBuilder $queryBuilder
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(DataMapper $dataMapper, QueryBuilder $queryBuilder, string $tableSchema, string $tableSchemaID)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * @inheritDoc
     */
    public function getSchema(): string
    {
        return $this->tableSchema;
    }

    /**
     * @inheritDoc
     */
    public function getSchemaID(): string
    {
        return $this->tableSchemaID;
    }

    /**
     * @inheritDoc
     */
    public function lastID(): int
    {
        return $this->dataMapper->getLastId();
    }

    /**
     * @inheritDoc
     */
    public function create(array $fields = []): bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'insert', 'fields' => $fields];
            $query = $this->queryBuilder->buildQuery($args)->insertQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ( $this->dataMapper->rowCount() == 1) {
                return true;
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function read(array $selectors = [], 
                         array $conditions = [], 
                         array $parameters = [], 
                         array $optionals = []): array
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions, 'params' => $parameters, 'extras' => $optionals];
            $query = $this->queryBuilder->buildQuery($args)->selectQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
            if ($this->dataMapper->rowCount() > 0) {
                return $this->dataMapper->results();
            }
        } catch (Throwable $th) {
            throw $http_response_header;
        }
    }

    /**
     * @inheritDoc
     */
    public function update(array $fields = [], string $primaryKey): bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'update', 'fields' => $fields, 'primary_key' => $primaryKey];
            $query = $this->queryBuilder->buildQuery($args)->updateQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ( $this->dataMapper->rowCount() == 1) {
                return true;
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(array $conditions = []): bool
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'delete', 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ( $this->dataMapper->rowCount() == 1) {
                return true;
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function search(array $selectors = [], array $conditions = []): array
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'search', 'selectors' => $selectors, 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->searchQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->rowCount() > 0) {
                return $this->dataMapper->results();
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function rawQuery(string $rawQuery, array $conditions = [])
    {
        try {
            $args = ['table' => $this->getSchema(), 'type' => 'raw', 'raw' => $rawQuery, 'conditions' => $conditions];
            $query = $this->queryBuilder->buildQuery($args)->rawQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->rowCount() > 0) {
                return $this->dataMapper->result();
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
