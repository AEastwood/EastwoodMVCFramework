<?php

require_once 'vendor/autoload.php';

$required = [
    // App
    'src/app/serviceproviders/AppServiceProvider.php',

    // classes
    'src/classes/App.php',
    'src/classes/Cookie.php',
    'src/classes/Controller.php',
    'src/classes/CSRF.php',
    'src/classes/Logger.php',
    'src/classes/Middleware.php',
    'src/classes/Model.php',
    'src/classes/Request.php',
    'src/classes/Response.php',
    'src/classes/Router.php',

    // Controllers
    'src/app/controllers/DefaultController.php',
    'src/app/controllers/app/IPConstraints.php',
    'src/app/controllers/auth/Web.php',

    // Exceptions
    'src/app/exceptions/DuplicateRouteException.php',
    'src/app/exceptions/InvalidProviderActionException.php',
    'src/app/exceptions/InvalidRouteException.php',
    'src/app/exceptions/InvalidRouteActionException.php',
    'src/app/exceptions/InvalidRouteMethodException.php',
    'src/app/exceptions/NonWhitelistedIPException.php',
    'src/app/exceptions/ViewDoesntExistException.php',
];

foreach ($required as $require) {
    require_once $require;
}

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();