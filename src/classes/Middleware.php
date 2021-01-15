<?php

namespace MVC\Classes;

use Closure;
use MVC\App\ServiceProviders\AppServiceProvider;

class Middleware {

    public array $middlewares;
    private AppServiceProvider $appServiceProvider;

    /**
     *   set middleware
     * @param $middlewares
     */
    public function __construct($middlewares) 
    {
        $this->middlewares = $middlewares;
        $this->appServiceProvider = new AppServiceProvider;
    }

    /**
     *  empty function for return
     */
    public static function next(): Closure
    {
        return function() {};
    }

    /**
    *   run middleware on request
    */
    public function run()
    {
        foreach($this->middlewares as $middleware) {
            return $this->runMiddlewareAction($middleware);
        }
    }

    /**
     *   get provider action
     * @param string $index
     * @return
     */
    private function runMiddlewareAction(string $index)
    {
        if(!array_key_exists($index, $this->appServiceProvider->providers)){
            return Controller::view('errors.error',[
                'code' => 500,
                'message' => 'Invalid middleware action provided'
            ]);
        }

        if($this->appServiceProvider->providers[$index]() !== null) {
            return $this->appServiceProvider->providers[$index]();
        }

        return Controller::view('errors.error',[
            'code' => 500,
            'message' => 'An error has occurred whilst trying to run middleware, please check your provider actions'
        ]);
    }

}