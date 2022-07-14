<?php

declare(strict_types=1);

namespace Firestorm\LiquidOrm\QueryBuilder;

use Firestorm\LiquidOrm\QueryBuilder\Exception\QueryBuilderException;

class QueryBuilderFactory
{
    public function create(string $queryBuilderClass): QueryBuilderInterface
    {
        $queryBuilder = new $queryBuilderClass;
        if (!$queryBuilder instanceof QueryBuilderInterface) {
            throw new QueryBuilderException($queryBuilderClass . ' is not a valid Query builder object');
        }

        return $queryBuilder;
    }
}
