<?php

declare(strict_types=1);

namespace Firestorm\Core;

use Firestorm\Core\Exception\CoreInvalidArgumentException;
use Firestorm\LiquidOrm\DataRepository\DataRepository;
use Firestorm\LiquidOrm\DataRepository\DataRepositoryFactory;
use Firestorm\LiquidOrm\EntityManager\Crud;

class Modul
{
    /** @var string */
    private string $tableSchema;

    /** @var string */
    private string $tableSchemaID;

     /** @var DataRepository */
    private DataRepository $repository;

    /**
     * Main class constructor
     *
     * @param  string $tableSchema
     * @param  string $tableSchemaID
     * @throws CoreInvalidArgumentException
     */
    public function __construct(string $tableSchema, string $tableSchemaID)
    {
        if (empty($tableSchema) || empty($tableSchemaID)) {
            throw new CoreInvalidArgumentException('These arguments are required.');
        }

        $repositoryFactory = new DataRepositoryFactory('', $tableSchema, $tableSchemaID);
        $this->repository = $repositoryFactory->create(DataRepository::class);
    }

    /**
     * Get the data repository object based on the context model
     * which the repository is being executed from.
     * 
     * @return DataRepository
     */
    public function repository(): DataRepository
    {
        return $this->repository;
    }
}
