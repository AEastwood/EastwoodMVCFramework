<?php

namespace MVC\Classes\Http;

use DateTime;
use MVC\Classes\User;

class Request
{
    public string $accept;
    public string $accept_language;
    public string $accept_encoding;
    public string $connection;
    public string $content_type;
    public int $content_length;
    public array $defined_vars;
    public array $headers;
    public string $host;
    public string $log_file;
    public string $method;
    public int $port;
    public array $parameters;
    public string $request_url;
    public int $timestamp;
    public string $user_agent;
    public int $upgrade_secure_connections;

    /*
     *  constructor
     */
    public function __construct()
    {
        $timestamp          = new DateTime();
        $this->timestamp    = $timestamp->getTimestamp();
        $this->request_url  = $_SERVER['REQUEST_URI'];
        $this->host         = $_SERVER['HTTP_HOST'] ?? $_ENV['BASE_URL'];
        $this->user_agent   = $_SERVER['HTTP_USER_AGENT'];
        $this->port         = $_SERVER['SERVER_PORT'];
        $this->method       = $_SERVER['REQUEST_METHOD'];
        $this->headers      = apache_request_headers();
        $this->defined_vars = get_defined_vars();
    }

}