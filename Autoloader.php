<?php

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require_once 'vendor/autoload.php';

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();