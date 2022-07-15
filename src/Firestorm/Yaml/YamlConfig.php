<?php

declare(strict_types=1);

namespace Firestorm\Yaml;

use Exception;
use Symfony\Component\Yaml\Yaml;

class YamlConfig
{
    /**
     * Load a yaml configuration
     *
     * @param  string $yamlFile
     * @return void
     */
    public function getYaml(string $yamlFile)
    {
        foreach (glob(CONFIG_PATH . '*.yaml') as $file) {
            $this->isFileExists($file);

            $parts = parse_url($file);
            $path = $parts['path'];
            if (strpos($path, $yamlFile)) {
                return Yaml::parseFile($file);
            }
        }
    }

    /**
     * Load a yaml configuration into the yaml parser
     *
     * @param  string $yamlFile
     * @return array
     */
    public static function file(string $yamlFile): array
    {
        return (array)(new YamlConfig)->getYaml($yamlFile);
    }

    /**
     * Check whether the specified yaml configuration file exists within
     * the specified directory else throw an exception
     *
     * @param  string  $fileName
     * @return boolean
     * @throws Exception
     */
    private function isFileExists(string $fileName)
    {
        if (!file_exists($fileName)) {
            throw new Exception($fileName . ' does not exixts');
        }
    }
}
