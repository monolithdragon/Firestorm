<?php

declare(strict_types=1);

namespace Firestorm\LiquidOrm\QueryBuilder;

interface QueryBuilderInterface
{
    public function selectQuery(): string;
    public function insertQuery(): string;
    public function updateQuery(): string;
    public function deleteQuery(): string;
    public function rawQuery(): string;
}
