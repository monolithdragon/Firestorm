<?php

declare(strict_type=1);

namespace Firestorm\DatabaseConnection\Exception;

use PDOException;

class DatabaseException extends PDOException
{
    protected $message;
    protected $code;

    public function __constructor($message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
