<?php

declare(strict_types=1);

namespace Firestorm\Application;

class Application
{
    protected string $appRoot;

    public function __construct(string $appRoot)
    {
        $this->appRoot = $appRoot;
    }

    public function run(): self
    {
        $this->constants();
        
        if (version_compare($phpVersion = PHP_VERSION, $minVersion = Config::FIRESTORM_MIN_VERSION, '<')) {
            die(sprintf('You are running PHP %s, but the core framework requires at least PHP %$', $phpVersion, $minVersion));
        }

        $this->enviroment();
        $this->errorHandler();
        
        return $this;
    }

    private function constants(): void
    {
        define('DS', DIRECTORY_SEPARATOR);
        define('ROOT', $this->appRoot . DS);
        define('CONFIG_PATH', ROOT . 'Config' . DS);
        define('TEMPLATE_PATH', ROOT . 'App/templates' . DS);
        define('LOG_DIR', ROOT . 'tmp/log' . DS);
    }

    private function enviroment(): void
    {
        ini_set('default_charset', 'UTF-8');
    }

    private function errorHandler(): void
    {
        error_reporting(E_ALL | E_STRICT);
        set_error_handler('Firestorm\ErrorHandling\ErrorHandling::errorHandler');
        set_exception_handler('Firestorm\ErrorHandling\ErrorHandling::exceptionHandler');
    }
}
