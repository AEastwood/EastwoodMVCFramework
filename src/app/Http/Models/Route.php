<?php

namespace MVC\App\Http\Models;

use MVC\Classes\Routes\Router;

class Route extends Router
{
    public mixed $action;
    public bool $hasMiddleware;
    public bool $hasParameters;
    public array $methods;
    public string $methodsAsString;
    public array $middleware;
    public $name;
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
        $this->target = 'web';
    }

    /*
     *  serialisation
     */
    public function __serialize(): array
    {
        $data = [
            'url'           => $this->url,
            'hasMiddleware' => $this->hasMiddleware,
            'hasParameters' => $this->hasParameters,
            'methods'       => $this->methods,
            'middleware'    => $this->middleware,
            'parameters'    => $this->parameters,
        ];

        return ($data);
    }

}