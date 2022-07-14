<?php

declare(strict_types=1);

namespace Firestorm\LiquidOrm\QueryBuilder;

use Firestorm\LiquidOrm\QueryBuilder\Exception\QueryBuilderInvalidArgumentException;

class QueryBuilder implements QueryBuilderInterface
{
    protected array $key;
    protected string $sqlQuery = '';

    protected const SQL_DEFAULT = [
        'selectors' => [],
        'replace' => false,
        'distinct' => false,
        'from' => [],
        'where' => null,
        'and' => [],
        'or' => [],
        'orderBy' => [],
        'fields' => [],
        'conditions' => [],
        'primary_key' => '',
        'table' => '',
        'type' => '',
        'raw' => ''
    ];

    protected const QUERY_TYPES = ['select', 'insert', 'update', 'delete', 'raw'];

    public function buildQuery(array $args): self
    {
        if (count($args) < 0) {
            throw new QueryBuilderInvalidArgumentException();
        }

        $arg = array_merge(self::SQL_DEFAULT, $args);
        $this->key = $arg;

        return $this;
    }

    public function selectQuery(): string
    {
        if ($this->isQueryTypeValid('select')) {
            $selectors = (!empty($this->key['selectors'])) ? implode(', ', $this->key['selectors']) : '*';
            $this->sqlQuery = "SELECT {$selectors} FROM {$this->key['table']}";
            $this->sqlQuery = $this->hasConditions();
            return $this->sqlQuery;            
        }

        return false;
    }

    public function insertQuery(): string
    {
        if ($this->isQueryTypeValid('insert')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                $index = array_keys($this->key['fields']);
                $value = array(implode(', ', $index), ":" . implode(', :', $index));
                $this->sqlQuery = "INSERT INTO {$this->key['table']} ({$value[0]}) VALUES ({$value[1]})";
                return $this->sqlQuery;
            }
        }

        return false;
    }

    public function updateQuery(): string
    {
        $values = '';

        if ($this->isQueryTypeValid('update')) {
            if (is_array($this->key['fields']) && count($this->key['fields']) > 0) {
                foreach ($this->key['fields'] as $field) {
                    if ($field !== $this->key['primary_key']) {
                        $values .= $field . " = :" . $field . ", ";
                    }
                }
                $values = substr_replace($values, '', -2);

                if (count($this->key['fields']) > 0) {
                    $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values} WHERE  = :{$this->key['primary_key']} LIMIT 1";
                    if (isset($this->key['primary_key']) && $this->key['primary_key'] === '0') {
                        unset($this->key['primary_key']);
                        $this->sqlQuery = "UPDATE {$this->key['table']} SET {$values}";
                    }
                }

                return $this->sqlQuery;
            }
        }

        return false;
    }

    public function deleteQuery(): string
    {
        if ($this->isQueryTypeValid('delete')) {
            $index = array_keys($this->key['conditions']);
            $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$index[0]} = :{$index[0]} LIMIT 1";
            $bulkDelete = array_values($this->key['fields']);
            if (is_array($bulkDelete) && count($bulkDelete) > 1) {
                for ($i=0; $i < count($bulkDelete); $i++) { 
                    $this->sqlQuery = "DELETE FROM {$this->key['table']} WHERE {$index[0]} = :{$index[0]}";
                }
            }
            return $this->sqlQuery;
        }

        return false;
    }

    public function rawQuery(): string
    {
        return $this->sqlQuery;
    }

    private function isQueryTypeValid(string $type): bool
    {
        if (in_array($type, self::QUERY_TYPES)) {
            return true;
        }

        return false;
    }

    private function hasConditions(): string
    {
        if (isset($this->key['conditions']) && !empty($this->key['conditions'])) {
            if (is_array($this->key['conditions'])) {
                $sort = [];
                foreach (array_keys($this->key['conditions']) as $whereKey => $where) {
                    if (isset($where) && $where != '') {
                        $sort[] = $where . " = :" . $where;
                    }
                }

                if (count($sort) > 0) {
                    $this->sqlQuery .= " WHERE " . implode(" AND ", $sort);
                }
            }
        } elseif (empty($this->key['conditions'])) {
            $this->sqlQuery .= " WHERE 1";
        }

        if (isset($this->key['orderBy']) && !empty($this->key['orderBy'])) {
            $this->sqlQuery .= " ORDER BY " . $this->key['orderBy'] .  " ";
        }

        if (isset($this->key['limit']) && $this->key['offset'] != -1) {
            $this->sqlQuery .= " LIMIT :offset, :limit";
        }

        return $this->sqlQuery;
    }
}
