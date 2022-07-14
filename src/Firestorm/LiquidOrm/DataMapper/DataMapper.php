<?php

declare(strict_type=1);

namespace Firestorm\LiquidOrm\DataMapper;

use Firestorm\DatabaseConnection\DatabaseInterface;
use PDO;
use PDOStatement;
use Firestorm\LiquidOrm\DataMapper\Exception\DataMapperException;
use Throwable;

class DataMapper implements DataMapperInterface
{
    /**     
     * @var DatabaseInterface
     */
    private DatabaseInterface $db;

    /**
     * @var PDOStatement
     */
    private PDOStatement $statement;

    /**
     * Main constructor class
     *
     * @param DatabaseInterface $db
     */
    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            throw new DataMapperException($errorMessage);            
        }
    }

    private function isArray(array $value)
    {
        if (!is_array($value)) {
            throw new DataMapperException('This argument needs to be array');            
        }
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $sqlQuery): self
    {
        $this->statement = $this->db->open()->prepare($sqlQuery);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bind($value): int
    {
        try {
            switch ($value) {
                case is_bool($value):
                case intval($value):
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($value):
                    $dataType = PDO::PARAM_NULL;
                    break;
                default:
                    $dataType = PDO::PARAM_STR;
                    break;
            }

            return $dataType;
        } catch (DataMapperException $ex) {
           throw $ex;
        }
    }

    /**
     * @inheritDoc
     */
    public function bindParameters(array $fields, bool $isSearch = false): self
    {
        if (is_array($fields)) {
            $type = (!$isSearch) 
                ? $this->bindValues($fields) 
                : $this->bindSearchValues($fields);
                
            if ($type) {
                return $this;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function execute(): void
    {
        if ($this->statement) {
            return $this->statement->execute();
        }
    }

    /**
     * @inheritDoc
     */
    public function rowCount(): int
    {
        if ($this->statement){
            return $this->statement->rowCount();
        }
    }

    /**
     * @inheritDoc
     */
    public function result(): object
    {
        if ($this->statement){
            return $this->statement->fetch(PDO::FETCH_OBJ);
        }
    }

    /**
     * @inheritDoc
     */
    public function results(): array
    {
        if ($this->statement){
            return $this->statement->fetchAll();
        }
    }

    /**
     * @inheritDoc
     */
    public function getLastId(): int
    {        
        try {
            if ($this->db->open()) {
                $lastID = $this->db->open()->lastInsertId();
                if (!empty($lastID)) {
                    return intval($lastID);
                }
            }
        } catch (Throwable $throwable) {
            throw $throwable;
        }        
    }

    /**
     * Bind a value to a corresponding name or question mark placeholder in the SQL statement
     * that was used to prepare the statement.
     *
     * @param  array $fields
     * @return PDOStatement
     * @throws DataMapperException
     */
    protected function bindValues(array $fields): PDOStatement
    {
        $this->isArray($fields);

        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, $value, $this->bind($value));
        }

        return $this->statement;
    }
    
    protected function bindSearchValues(array $fields): PDOStatement
    {
        $this->isArray($fields);

        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, '%' . $value . '%', $this->bind($value));
        }
 
        return $this->statement;
    }
}