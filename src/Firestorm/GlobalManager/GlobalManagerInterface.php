<?php

declare(strict_types=1);

namespace Firestorm\GlobalManager;

interface GlobalManagerInterface
{
    /**
     * Set the global variable
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public static function set(string $key, $value): void;

    /**
     * Gets the value of the global variable
     *
     * @param  string $key
     * @return mixed
     * @throws GlobalManagerException
     */
    public static function get(string $key);
}
