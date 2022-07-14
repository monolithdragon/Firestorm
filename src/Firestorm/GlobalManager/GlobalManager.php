<?php

declare(strict_types=1);

namespace Firestorm\GlobalManager;

use Firestorm\GlobalManager\Exception\GlobalManagerException;
use Firestorm\GlobalManager\Exception\GlobalManagerInvalidArgumentException;
use Throwable;

class GlobalManager implements GlobalManagerInterface
{
    /**
     * @inheritDoc
     */
    public static function set(string $key, $value): void
    {
        $GLOBALS[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public static function get(string $key)
    {
        self::isGlobalValid($key);

        try {
            return $GLOBALS[$key];
        } catch (Throwable $th) {
            throw new GlobalManagerException('An exception was thrown trying to retrive the data.'. $th);
        }
    }

    /**
     * Check if we have valid key and its not empty else throw an exception
     *
     * @param  string $key
     * @return void
     * @throws GlobalManagerInvalidArgumentException
     */
    public static function isGlobalValid(string $key): void
    {
        if (!isset($GLOBALS[$key])) {
            throw new GlobalManagerInvalidArgumentException('Invalid global key. Please ensure set the globel state for '. $key);
        }

        if (empty($GLOBALS[$key])) {
            throw new GlobalManagerInvalidArgumentException('Argument cannot be empty.');
        }
    }
}
