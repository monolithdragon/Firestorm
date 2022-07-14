<?php

declare(strict_types=1);

namespace Firestorm\Session;

use Firestorm\Session\Exception\SessionStorageInvalidArgumentException;
use Firestorm\Session\Storage\SessionStorageInterface;

class SessionFactory
{
    public function create(string $sessionName, string $storageClass, array $options = []): SessionInterface
    {
        $storage = new $storageClass($options);
        if (!$storage instanceof SessionStorageInterface) {
            throw new SessionStorageInvalidArgumentException($storageClass . ' is not a valid session storage object.');
        }

        return new Session($sessionName, $storage);
    }
}
