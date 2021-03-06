<?php

declare(strict_types=1);

namespace Firestorm\Session\Storage;

class NativeSessionStorage extends SessionStorage
{
    /**
     * Main class constructor
     *
     * @param  array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    /**
     * @inheritDoc
     */
    public function setSession(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function getSession(string $key, $default = null)
    {
        if ($this->hasSession($key)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    /**
     * @inheritDoc
     */
    public function setArraySession(string $key, $value): void
    {
        $_SESSION[$key][] = $value;
    }

    /**
     * @inheritDoc
     */
    public function deleteSession(string $key): void
    {
        if ($this->hasSession($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @inheritDoc
     */
    public function invalidate(): void
    {
        $_SESSION = array();
        if (ini_set('session.use_cookies', $this->options['use_cookies'])) {
            $params = session_get_cookie_params();
            setcookie($this->getSessionName(), '', time() - $params['lifetime'], 
            $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_unset();
        session_destroy();
    }

    /**
     * @inheritDoc
     */
    public function flush(string $key, $default = null)
    {
        if ($this->hasSession($key)) {
            $value = $_SESSION[$key];
            $this->deleteSession($key);
            return $value;
        }

        return $default;
    }

    /**
     * @inheritDoc
     */
    public function hasSession(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
}
