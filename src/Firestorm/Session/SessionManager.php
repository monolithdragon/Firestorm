<?php

declare(strict_types=1);

namespace Firestorm\Session;

use Firestorm\Session\Storage\NativeSessionStorage;
use Firestorm\Yaml\YamlConfig;

class SessionManager
{
    /**
     * Create an instance of our session factory and pass in the default session storage
     * we will fetch the session name and array of options from the core configuration
     * files
     *
     * @return SessionInterface
     */
    public static function initialize(): SessionInterface
    {
        $sessionFactory = new SessionFactory;
        return $sessionFactory->create('firestorm', NativeSessionStorage::class, YamlConfig::file('session'));
    }
}
