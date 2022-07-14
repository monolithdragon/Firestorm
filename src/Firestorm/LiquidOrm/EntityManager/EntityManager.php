<?php
declare(strict_types=1);

namespace Firestorm\LiquidOrm\EntityManager;

class EntityManager implements EntityManagerInterface
{
    /** @var CrudInterface */
    protected CrudInterface $croud;

    /**
     * Main constructor class
     *
     * @param  CrudInterface $croud
     */
    public function __construct(CrudInterface $croud)
    {
        $this->croud = $croud;
    }

    /**
     * @inheritDoc
     */
    public function getCrud(): object
    {
        return $this->croud;
    }
}
