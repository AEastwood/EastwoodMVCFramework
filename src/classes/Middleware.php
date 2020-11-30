<?php

namespace MVC\Classes;

use MVC\App\Exceptions\InvalidProviderActionException;
use MVC\App\ServiceProviders\AppServiceProvider;

class Middleware {

    public array $middlewares;
    private AppServiceProvider $appServiceProvider;

    /*
    *   set middleware
    */
    public function __construct($middlewares) 
    {
        $this->middlewares = $middlewares;
        $this->appServiceProvider = new AppServiceProvider;
    }

    /*
    *   run middleware on request
    */
    public function run(): void
    {
        foreach($this->middlewares as $middleware) {
            $this->runMiddlewareAction($middleware);
        }
    }

    /*
    *   get provider action
    */
    private function runMiddlewareAction(string $index): void
    {
        if(!array_key_exists($index, $this->appServiceProvider->providers)){
            throw new InvalidProviderActionException($index);
        }

        $this->appServiceProvider->providers[$index]();
    }

}