<?php

declare(strict_type=1);

namespace Firestorm\DatabaseConnection;

use Firestorm\DatabaseConnection\Exception\DatabaseException;
use PDO;
use Exception;

class Database implements DatabaseInterface
{
    /**     
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * @var array
     */
    protected array $credentials;

    /**
     * Constructor class
     *
     * @param  array $credentials
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @inheritDoc
     */
    public function open(): PDO
    {
        try {
            $options = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            $this->pdo = new PDO(
                $this->credentials['dsn'],
                $this->credentials['username'],
                $this->credentials['password'],
                $options
            );
        } catch(Exception $ex) {
            throw new DatabaseException($ex->getMessage(), (int)$ex->getCode());            
        }

        return $this->pdo;
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        $this->pdo = null;
    }
}
