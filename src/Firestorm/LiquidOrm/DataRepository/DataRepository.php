<?php

declare(strict_types=1);

namespace Firestorm\LiquidOrm\DataRepository;

use Firestorm\LiquidOrm\DataRepository\Exception\DataRepositoryException;
use Firestorm\LiquidOrm\DataRepository\Exception\DataRepositoryInvalidArgumentException;
use Firestorm\LiquidOrm\EntityManager\EntityManagerInterface;
use Throwable;

class DataRepository implements DataRepositoryInterface
{
    /** @var EntityManagerInterface */
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): array
    {
        $this->isEmpty($id);

        try {
            return  $this->findOneBy(['id' => $id]);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        try {
            return $this->findBy();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $selectors = [], 
                           array $conditions = [], 
                           array $params = [], 
                           array $optional = []): array
    {
        try {
            return $this->em->getCrud()->read($selectors, $conditions, $params, $optional);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function findOneBy(array $conditions): array
    {
        $this->isArray($conditions);

        try {
            return $this->em->getCrud()->read([], $conditions);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function findObjectBy(array $selectors = [], array $conditions = []): Object
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function findBySearch(array $selectors = [], 
                                 array $conditions = [], 
                                 array $params = [], 
                                 array $optional = []): array
    {
        $this->isArray($conditions);

        try {
            return $this->em->getCrud()->search($selectors, $conditions, $params, $optional);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function findByIdAndDelete(array $conditions): bool
    {
        $this->isArray($conditions);

        try {
            $result = $this->findOneBy($conditions);
            if ($result != null && count($result) > 0) {
                return $this->em->getCrud()->delete($conditions);
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function findByIdAndUpdate(int $id, array $fields = []): bool
    {
        $this->isArray($fields);

        try {
            $result = $this->findOneBy([$this->em->getCrud()->getSchemaID() => $id]);
            if ($result != null && count($result) > 0) {
                $conditions = (!empty($fields)) ? array_merge([$this->em->getCrud()->getSchemaID() => $id], $fields) : $fields;
                return $this->em->getCrud()->update($conditions, $this->em->getCrud()->getSchemaID());
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function findWithSearchAndPaging(array $args, Object $request): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function findAndReturn(int $id, array $selectors = []): self
    {
        return $this;
    }

    /**
     * Check the incoming $valis isn't empty else throw an exception
     * 
     * @param mixed $value
     * @param string|null $errorMessage
     * @return void
     * @throws DataRepositoryInvalidArgumentException
     */
    private function isEmpty(int $id): void
    {
        if (empty($id)) {
            throw new DataRepositoryInvalidArgumentException('Argument should not be empty.');            
        }
    }

    /**
     * Check the incoming argument $conditions is an array else throw an exception
     * 
     * @param array $conditions
     * @return void
     * @throws DataRepositoryInvalidArgumentException
     */
    private function isArray(array $conditions): void
    {
        if (!is_array($conditions)) {
            throw new DataRepositoryInvalidArgumentException('This argument needs to be array');            
        }
    }
}
