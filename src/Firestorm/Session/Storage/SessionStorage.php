<?php

declare(strict_types=1);

namespace Firestorm\Session\Storage;

abstract class SessionStorage implements SessionStorageInterface
{
    protected array $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;
        $this->iniSet();

        if ( $this->isSessionStarted()) {
            session_unset();
            session_destroy();
        }

        $this->start();
    }

    public function setSessionName(string $sessionName): void
    {
        session_name($sessionName);
    }

    public function getSessionName(): string
    {
        return session_name();
    }

    public function setSessionID(string $sessionID): void
    {
        session_id($sessionID);
    }

    public function getSessionID(): string
    {
        return session_id();
    }

    protected function iniSet()
    {
        ini_set('session.gc_maxlifetime', $this->options['gc_maxlifetime']);
        ini_set('session.gc_divisor', $this->options['gc_divisor']);
        ini_set('session.gc_probability', $this->options['gc_probability']);
        ini_set('session.cookie_lifetime', $this->options['cookie_lifetime']);
        ini_set('session.use_cookies', $this->options['use_cookies']);
    }

    public function isSessionStarted(): bool
    {
        return php_sapi_name() !== 'cli' ? $this->getSessionID() !== '' : false;
    }

    public function startSession(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function start()
    {
        $this->setSessionName($this->options['session_name']);
        $domain = (isset($this->options['domain']) 
            ? $this->options['domain'] 
            : isset($_SERVER['SERVER_NAME']));

        $secure = (isset($this->options['secure'])
            ? $this->options['secure']
            : isset($_SERVER['HTTPS']));

        session_set_cookie_params($this->options['lifetime'], $this->options['path'], 
            $domain, $secure, $this->options['httponly']);

        $this->startSession();
    }
}
