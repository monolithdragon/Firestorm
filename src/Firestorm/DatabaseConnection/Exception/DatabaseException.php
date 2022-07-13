<?php

declare(strict_type=1);

namespace Firestorm\DatabaseConnection\Exception;

use PDOException;

class DatabaseException extends PDOException
{
    /**
     * Main constructor class which overrides the parent constructor and set the message
     * and the code properties which is optimal
     *
     * @param  string $message
     * @param  int $code
     * @return void
     */
    public function __construct($message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
