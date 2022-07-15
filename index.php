<?php

defined('ROOT_PATH') or define('ROOT_PATH', realpath(dirname(__FILE__)));

$autoload = ROOT_PATH . '/vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
}

use Firestorm\Application\Application;

(new Application(ROOT_PATH))->run()->setSession();