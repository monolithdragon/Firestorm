<?php

declare(strict_types=1);

namespace Firestorm\Session\Storage;

interface SessionStorageInterface
{
    /**
     * Session name wrapper with explicit arguppment to set a session_name
     *
     * @param  string $sessionName
     * @return void
     */
    public function setSessionName(string $sessionName): void;

    /**
     * Session name wrapper returns the name of the session
     *
     * @return string
     */
    public function getSessionName(): string;

    /**
     * Session id wrapper with explicit argument to set a session_id
     *
     * @param  string $sessionID
     * @return void
     */
    public function setSessionID(string $sessionID): void;

    /**
     * Session id wrapper which returns the current session id
     *
     * @return string
     */
    public function getSessionID(): string;

    /**
     * Sets a specific value to a specific key of the session
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     * @throws SessionInvalidArgumentException
     */
    public function setSession(string $key, $value): void;

    /**
     * Gets the value of a specific key of the session
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function getSession(string $key, $default = null);

    /**
     * Sets the specific value to a specific array key of the session
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function setArraySession(string $key, $value): void;

    /**
     * Removes the value for the specified key from the session
     *
     * @param  string $key
     * @return void
     */
    public function deleteSession(string $key): void;

    /**
     *  Destroy the session. Along with session cookies
     *
     * @return void
     */
    public function invalidate(): void;

    /**
     * Returns the requested value and remove it from the session
     *
     * @param  string $key
     * @param  mixed $default
     * @return void
     */
    public function flush(string $key, $default = null);

    /**
     * Determines whether an item is present in the session
     *
     * @param  string  $key
     * @return boolean
     */
    public function hasSession(string $key): bool;
}
