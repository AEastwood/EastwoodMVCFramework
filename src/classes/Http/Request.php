<?php

namespace MVC\Classes\Http;

use DateTime;

class Request
{
    /**
     * @var string
     */
    public string $accept;

    /**
     * @var string
     */
    public string $accept_language;

    /**
     * @var string
     */
    public string $accept_encoding;

    /**
     * @var string
     */
    public string $connection;

    /**
     * @var string
     */
    public string $content_type;

    /**
     * @var int
     */
    public int $content_length;

    /**
     * @var array
     */
    public array $defined_vars;

    /**
     * @var array
     */
    public array $headers;

    /**
     * @var string|mixed
     */
    public string $host;

    /**
     * @var string|mixed
     */
    public string $method;

    /**
     * @var int|mixed
     */
    public int $port;

    /**
     * @var array
     */
    public array $parameters;

    /**
     * @var string|mixed
     */
    public string $request_url;

    /**
     * @var int
     */
    public int $timestamp;

    /**
     * @var string|mixed
     */
    public string $user_agent;

    /**
     * @var int
     */
    public int $upgrade_secure_connections;


    /*
     *  constructor
     */
    public function __construct()
    {
        $timestamp = new DateTime();
        $this->timestamp = $timestamp->getTimestamp();
        $this->request_url = $_SERVER['REQUEST_URI'] ?? '';
        $this->host = $_SERVER['HTTP_HOST'] ?? $_ENV['BASE_URL'];
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $this->port = $_SERVER['SERVER_PORT'] ?? 80;
        $this->method = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->headers = (function_exists('apache_request_headers')) ? apache_request_headers() : [];
        $this->defined_vars = get_defined_vars();
    }

}