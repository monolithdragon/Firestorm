<?php

declare(strict_types=1);

namespace Firestorm\GlobalManager;

use Firestorm\GlobalManager\Exception\GlobalManagerException;
use Throwable;

class GlobalManager implements GlobalManagerInterface
{
    public static function set(string $key, $value): void
    {
        $GLOBALS[$key] = $value;
    }

    public static function get(string $key)
    {
        try {
            return $GLOBALS[$key];
        } catch (Throwable $th) {
            throw new GlobalManagerException('An exception was thrown trying to retrive the data.'. $th);
        }
    }
}
