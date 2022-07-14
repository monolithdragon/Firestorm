<?php
declare(strict_types=1);

namespace Firestorm\LiquidOrm\EntityManager;

interface EntityManagerInterface
{
    /**
     * Get the crud object which will expose all the method within our crud class
     *
     * @return object
     */
    public function getCrud(): object;
}
