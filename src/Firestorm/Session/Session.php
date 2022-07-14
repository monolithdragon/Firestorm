<?php

declare(strict_types=1);

namespace Firestorm\Session;

use Firestorm\Session\Storage\SessionStorageInterface;
use Firestorm\Session\Exception\SessionInvalidArgumentException;
use Firestorm\Session\Exception\SessionException;
use Throwable;

class Session implements SessionInterface
{
    protected SessionStorageInterface $storage;
    protected string $sessionName;
    protected const SESSION_PATTERN = '/^[a-zA-Z0-9_\.]{1,64}$/';

    public function __construct(string $sessionName, SessionStorageInterface $storage = null)
    {
        if (!$this->isSessionKeyValid($sessionName)) {
            throw new SessionInvalidArgumentException($sessionName . ' is not a valid session name.');
        }

        $this->sessionName = $sessionName;
        $this->storage = $storage;
    }

    public function set(string $key, $value): void
    {
        $this->ensureSessionKeyIsValid($key);

        try {
            $this->storage->setSession($key, $value);
        } catch (Throwable $th) {
            throw new SessionException('An exception was throw in retrieving the key
                from the session storage.' . $th);
        }
    }

    public function setArray(string $key, $value): void
    {
        $this->ensureSessionKeyIsValid($key);

        try {
            $this->storage->setArraySession($key, $value);
        } catch (Throwable $th) {
            throw new SessionException('An exception was throw in retrieving the key
                from the session storage.' . $th);
        }
    }

    public function get(string $key, $default = null)
    {
        $this->ensureSessionKeyIsValid($key);

        try {
            return $this->storage->getSession($key, $default);
        } catch (Throwable $th) {
            throw new SessionException('An exception was throw in retrieving the key
                from the session storage.' . $th);
        }
    }

    public function delete(string $key): void
    {
        $this->ensureSessionKeyIsValid($key);

        try {
            $this->storage->deleteSession($key);
        } catch (Throwable $th) {
            throw new SessionException('An exception was throw in retrieving the key
                from the session storage.' . $th);
        }
    }

    public function invalidate(): void
    {
        $this->storage->invalidate();
    }

    public function flush(string $key, $value)
    {
        $this->ensureSessionKeyIsValid($key);

        try {
            $this->storage->flush($key, $value);
        } catch (Throwable $th) {
            throw new SessionException('An exception was throw in retrieving the key
                from the session storage.' . $th);
        }
    }

    public function has(string $key): bool
    {
        $this->ensureSessionKeyIsValid($key);
        return $this->storage->hasSession($key);        
    }

    protected function isSessionKeyValid(string $key): bool
    {
        return (preg_match(self::SESSION_PATTERN, $key) === 1);
    }

    protected function ensureSessionKeyIsValid(string $key): void
    {
        if (!$this->isSessionKeyValid($key)) {
            throw new SessionInvalidArgumentException($key . ' is not a valid session key.');
        }
    }
}
