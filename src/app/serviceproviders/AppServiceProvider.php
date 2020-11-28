<?php

namespace MVC\App\ServiceProviders;

use App\MVC\Classes\Middleware;
use MVC\App\Middleware\IPConstraints;
use MVC\App\Middleware\LocationConstraints;
use MVC\App\Controllers\Auth\Web;

class AppServiceProvider {
    
    public array $providers = array();

    /*
    *   constructor
    */
    public function __construct()
    {
        $this->providers = [
            'auth:api'      => [Web::class, 'authenticated_api'],
            'auth:web'      => [Web::class, 'authenticated_web'],
            'ip:blacklist'  => [IPConstraints::class, 'ipBlacklisted'],
            'ip:whitelist'  => [IPConstraints::class, 'ipWhitelisted'],
            'location:blacklist'  => [LocationConstraints::class, 'locationBlacklisted'],
            'location:whitelist'  => [LocationConstraints::class, 'locationWhitelisted'],
        ];
    }

}