<?php

namespace MVC\App\ServiceProviders;

use App\MVC\Classes\Middleware;
use MVC\App\Controllers\App\IPConstraints;
use MVC\App\Controllers\Auth\Web;

class AppServiceProvider {
    
    public array $providers = array();

    /*
    *   constructor
    */
    public function __construct()
    {
        $this->providers = [
            'auth:api' => [Web::class, 'authenticated_api'],
            'auth:web' => [Web::class, 'authenticated_web'],
            'ip:blacklist' => [IPConstraints::class, 'blacklisted'],
            'ip:whitelist' => [IPConstraints::class, 'whitelisted'],
        ];
    }

}