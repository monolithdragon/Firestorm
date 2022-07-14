<?php

declare(strict_types=1);

namespace Firestorm\Session;

interface SessionInterface
{
    /**
     * Sets a specific value to a specific key of the session
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     * @throws SessionInvalidArgumentException
     */
    public function set(string $key, $value): void;

    /**
     * Sets the specific value to a specific array key of the session
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     * @throws SessionInvalidArgumentException
     */
    public function setArray(string $key, $value): void;

    /**
     * Gets the value of a specific key of the session
     *
     * @param  string $key
     * @param  mixed $default
     * @return void
     * @throws SessionInvalidArgumentException
     */
    public function get(string $key, $default = null);

    /**
     * Removes the value for the specified key from the session
     *
     * @param  string $key
     * @return void
     * @throws SessionInvalidArgumentException
     */
    public function delete(string $key): void;

    /**
     * Destroy the session. Along with session cookies
     *
     * @return void
     */
    public function invalidate(): void;

    /**
     * Returns the requested value and remove it from the session
     *
     * @param  string $key
     * @param  mixed $value
     * @return mixed
     */
    public function flush(string $key, $value = null);

    /**
     * Determines whether an item is present in the session.
     *
     * @param  string  $key
     * @return boolean
     * @throws SessionInvalidArgumentException
     */
    public function has(string $key): bool;
}
