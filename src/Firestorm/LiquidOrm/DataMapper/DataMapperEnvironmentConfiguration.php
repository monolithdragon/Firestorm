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
     * @throws DataMapperInvalidArgumentException
     */
    public function getDatabaseCredentials(string $driver): array
    {
        $connectionArray = [];
        $this->isCredentialsValid($driver);

        foreach ($this->credentials as $credential) {
            if (!array_key_exists($driver, $credential)) {
                throw new DataMapperInvalidArgumentException('Selected database driver is not supported.');
            }

            $connectionArray = $credential[$driver];            
        }

        return $connectionArray;
    }

    /**
     * Checks credentials for validity.
     *
     * @param  string $driver
     * @return void
     * @throws DataMapperInvalidArgumentException
     */
    public function isCredentialsValid(string $driver): void
    {
        if (empty($driver) || !is_string($driver)) {
            throw new DataMapperInvalidArgumentException('Invalid argument. This is either missing or off the invalid data type.');
        }
    }
}
