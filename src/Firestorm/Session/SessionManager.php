<?php

declare(strict_types=1);

namespace Firestorm\Session;

use Firestorm\Session\Storage\NativeSessionStorage;

class SessionManager
{
    public function initialize()
    {
        $sessionFactory = new SessionFactory;
        return $sessionFactory->create('', NativeSessionStorage::class, array());
    }
}
