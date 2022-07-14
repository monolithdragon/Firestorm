<?php

declare(strict_type=1);

namespace Firestorm\LiquidOrm\DataMapper;

use Firestorm\LiquidOrm\DataMapper\Exception\DataMapperInvalidArgumentException;

class DataMapperEnvironmentConfiguration
{
    /**
     * @var array
     */
    private array $credentials = [];

    /**
     * Mian contruct class
     *
     * @param  array $credentials
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Get the user defined database connection array.
     *
     * @param  string $driver
     * @return array
     */
    public function getDatabaseCredentials(string $driver): array
    {
        $connectionArray = [];
        
        foreach ($this->credentials as $credential) {
            if (array_key_exists($driver, $credential)) {
                $connectionArray = $credential[$driver];
            }

            return $connectionArray;
        }
    }

    /**
     * Check credentials for validity.
     *
     * @param  string $driver
     * @return void
     */
    public function isCredentialsValid(string $driver): void
    {
        if (empty($driver) || !is_string($driver)) {
            throw new DataMapperInvalidArgumentException('Invalid argument. This is either missing or off the invalid data type.');
        }

        if (!is_array($this->credentials)) {
            throw new DataMapperInvalidArgumentException('Invalid credentials.');
        }

        if (!in_array($driver, array_keys($this->credentials[$driver]))) {
            throw new DataMapperInvalidArgumentException('Invalid or unsupport database driver.');
        }
    }
}
