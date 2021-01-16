<?php

namespace MVC\App\ServiceProviders;

use App\MVC\Classes\Middleware;
use MVC\App\Middleware\Auth;
use MVC\App\Middleware\CSRF;
use MVC\App\Middleware\IPConstraints;
use MVC\App\Middleware\LocationConstraints;

class AppServiceProvider {
    
    public array $providers = array();

    /**
    *   constructor
    */
    public function __construct()
    {
        $this->providers = [
            'auth:api'      => [Auth::class, 'authenticatedApi'],
            'auth:web'      => [Auth::class, 'authenticatedWeb'],
            'csrf'          => [CSRF::class, 'validCSRF'],
            'ip:blacklist'  => [IPConstraints::class, 'ipBlacklisted'],
            'ip:whitelist'  => [IPConstraints::class, 'ipWhitelisted'],
            'location:blacklist'  => [LocationConstraints::class, 'locationBlacklisted'],
            'location:whitelist'  => [LocationConstraints::class, 'locationWhitelisted'],
        ];
    }

}