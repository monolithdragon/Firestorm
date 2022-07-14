<?php

declare(strict_types=1);

namespace Firestorm\Session;

use Firestorm\Session\Storage\SessionStorageInterface;
use Firestorm\Session\Exception\SessionInvalidArgumentException;
use Firestorm\Session\Exception\SessionException;
use Throwable;

class Session implements SessionInterface
{
    /** @var SessionStorageInterface */
    protected SessionStorageInterface $storage;

    /** @var string */
    protected string $sessionName;

     /** @var const */
    protected const SESSION_PATTERN = '/^[a-zA-Z0-9_\.]{1,64}$/';

    /**
     * Constructor class
     *
     * @param  string                       $sessionName
     * @param  SessionStorageInterface|null $storage
     * @throws SessionInvalidArgumentException
     */
    public function __construct(string $sessionName, SessionStorageInterface $storage = null)
    {
        if (!$this->isSessionKeyValid($sessionName)) {
            throw new SessionInvalidArgumentException($sessionName . ' is not a valid session name.');
        }

        $this->sessionName = $sessionName;
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
    public function invalidate(): void
    {
        $this->storage->invalidate();
    }

    /**
     * @inheritDoc
     */
    public function flush(string $key, $value = null)
    {
        $this->ensureSessionKeyIsValid($key);

        try {
            $this->storage->flush($key, $value);
        } catch (Throwable $th) {
            throw new SessionException('An exception was throw in retrieving the key
                from the session storage.' . $th);
        }
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        $this->ensureSessionKeyIsValid($key);
        return $this->storage->hasSession($key);        
    }

    /**
     * Checks whether our session key is valid according the defined regular expression
     *
     * @param  string  $key
     * @return boolean
     */
    protected function isSessionKeyValid(string $key): bool
    {
        return (preg_match(self::SESSION_PATTERN, $key) === 1);
    }

    /**
     * Checks whether we have session key 
     *
     * @param  string $key
     * @return void
     */
    protected function ensureSessionKeyIsValid(string $key): void
    {
        if (!$this->isSessionKeyValid($key)) {
            throw new SessionInvalidArgumentException($key . ' is not a valid session key.');
        }
    }
}
