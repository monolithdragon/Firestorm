<?php

declare(strict_types=1);

namespace Firestorm\Application;

use Firestorm\Traits\System;

class Application
{
    use System;

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

    public function setSession(): self
    {
        $this->sessionInit(true);
        return $this;
    }

    private function constants(): void
    {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
        defined('ROOT') or define('ROOT', $this->appRoot . DS);
        defined('CONFIG_PATH') or define('CONFIG_PATH', ROOT . 'Config' . DS);
        defined('TEMPLATE_PATH') or define('TEMPLATE_PATH', ROOT . 'App/templates' . DS);
        defined('LOG_DIR') or define('LOG_DIR', ROOT . 'tmp/log' . DS);
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
