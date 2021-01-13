<?php

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use MVC\Classes\ErrorHandler;

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

include_once 'src/global/Functions.php';

if($_ENV['RELEASE_MODE'] === 'debug') {
    $whoops = new Run;
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();
}