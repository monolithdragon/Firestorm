<?php

declare(strict_types=1);

namespace Firestorm\LiquidOrm\QueryBuilder;

interface QueryBuilderInterface
{
    /**
     * Select query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function selectQuery(): string;

    /**
     * Insert query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function insertQuery(): string;

    /**
     * Update query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function updateQuery(): string;

     /**
     * Delete query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function deleteQuery(): string;

     /**
     * Search|Select query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function searchQuery(): string;

    /**
     * Raw query string
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function rawQuery(): string;
}
