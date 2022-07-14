<?php

declare(strict_type=1);

namespace Firestorm\LiquidOrm\DataMapper;

interface DataMapperInterface
{
    /**
     * Prepare the query string.
     *
     * @param  string $sqlQuery
     * @return self
     */
    public function prepare(string $sqlQuery): self;

    /**
     * Explicit data type for the parameter using the PDO::PARAM_* constants. 
     *
     * @param  mixed $value
     * @return int;
     */
    public function bind($value): int;

    /**
     * Combination method which combines both methods above.
     * One of which is optimized for binding search queries.
     * Once the second argument $type is set to search.
     *
     * @param  array   $fields
     * @param  boolean $isSearch
     * @return self
     */
    public function bindParameters(array $fields, bool $isSearch = false): self;

    /**
     * Returns the number of rows affected by DELETE, INSERT or UPDATE statement.
     *
     * @return integer
     */
    public function rowCount(): int;

    /**
     * Execute function which will execute the prepared statement.
     *
     * @return void
     */
    public function execute(): void;

    /**
     * Returns a single database row as an object.
     *
     * @return object
     */
    public function result(): object;

    /**
     * Returns all the rows whithin the database as an array.
     *
     * @return array
     */
    public function results(): array;

    /**
     * Returns the last inserted row ID from database table.
     *
     * @return integer
     * @throws Throwable
     */
    public function getLastId(): int;
}
