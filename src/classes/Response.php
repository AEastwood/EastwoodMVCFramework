<?php

namespace MVC\Classes;

use MVC\App\Route;
use MVC\App\Exceptions\InvalidRouteException;
use MVC\App\Exceptions\InvalidRouteActionException;
use MVC\App\Exceptions\InvalidRouteMethodException;
use MVC\App\ServiceProviders\AppServiceProvider;
use MVC\Classes\Controller;
use MVC\Classes\Middleware;

class Response
{
    /*
    *   indicates sucessful request
    */
    public bool $successful;

    /*
     *  constructor
     */
    public function __construct()
    {
        $this->successful = false;
    }

    /*
    *   Apply middleware from routes
    *   @param  array   $middleware
    */
    private function applyMiddleware(array $middleware)
    {
        $middleware = new Middleware($middleware);
        $middleware->run();
    }

    /*
    *   Apply parameters from routes
    *   @param  array   $parameters
    */
    private function applyParameters(array $parameters)
    {
        foreach($parameters as $parameter) {
            $app->response->parameters[] = $parameter;
        }
    }
    
    /*
     *  find request in routes and run action  
     *  @param  $action
     */
    private function doAction($action): void
    {
        $this->successful = true;
        $action();
    }

    /*
    *   Runs application
    *   @param  App $app
    *   @returns    Action|Error-view
    */
    public function get(App $app)
    {
        $action = null;

        foreach($app->router->routes as $route) {

            if($route->url !== $app->request->request_url) {
                continue;
            }

            if($route->url === $app->request->request_url && in_array($app->request->method, $route->methods)) {

                if($route->hasMiddleware) {
                    $this->applyMiddleware($route->middleware);
                }

                if($route->hasParameters) {
                    $this->applyParameters($route->parameters);
                }
                
                return $this->doAction($route->action);
            }
        }
       
        return Controller::error('not_found');
    }

    /*
    *   return JSON response
    *   @param  array   $data
    *   @param  int     $code
    */
    public static function json(array $data, int $code = null)
    {
        header('Content-Type: application/json');
        
        if($code !== -1) {
            header('HTTP1.1 ' . $code);
        }

        $data = json_encode($data);
        echo $data;
        exit;
    }

}