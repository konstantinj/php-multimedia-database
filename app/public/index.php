<?php

error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as ConfigIni;

function _log($data)
{
    error_log('[' . date('Y-m-d H:i:s') . '] ' . $data . PHP_EOL, 3, 'php://stdout');
}

try {
    define('APP_PATH', realpath('..') . '/');

    $config = new ConfigIni(APP_PATH . 'app/config/config.ini');

    require APP_PATH . 'app/config/loader.php';

    $application = new Application(
        new Services($config)
    );

    $response = $application->handle();
    $response->send();
} catch (Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
