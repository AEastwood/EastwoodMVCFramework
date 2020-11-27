<?php

namespace MVC\Models;

use MVC\Classes\Router;


class Route extends Router
{
    public $action;
    public bool $hasMiddleware;
    public bool $hasParameters;
    public array $methods;
    public array $middleware;
    public array $parameters;
    public string $target;
    public string $url;

    /*
     *  constructor
     */
    public function __construct()
    {
        $this->hasMiddleware = false;
        $this->middleware = array();
        $this->hasParameters = false;
        $this->parameters = array();
        $this->target = 'web';
    }

    /*
     *  serialisation
     */
    public function __serialize(): array
    {
        $data = [
            'url' => $this->url,
            'hasMiddleware' => $this->hasMiddleware,
            'hasParameters' => $this->hasParameters,
            'methods' => $this->methods,
            'middleware' => $this->middleware,
            'parameters' => $this->parameters,
        ];

        return ($data);
    }

}