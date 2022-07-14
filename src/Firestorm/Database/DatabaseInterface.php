<?php

declare(strict_type=1);

namespace Firestorm\Database;

use PDO;

interface DatabaseInterface
{
    /**
     * Create a new database connection
     *
     * @return PDO
     */
    public function open(): PDO;

    /**
     * Close database connection
     *
     * @return void
     */
    public function close(): void;
}
