<?php

declare(strict_types=1);

namespace Firestorm\Flash;

interface FlashInterface
{
    /**
     * Add a fash message stored with the session
     *
     * @param  string $message
     * @param  string $type
     * @return void
     */
    public static function add(string $message, string $type = FlashTypes::SUCCESS): void;

    /**
     * Get all message within the session
     *
     * @return void
     */
    public static function get();

}