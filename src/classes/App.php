<?php

namespace MVC\Classes;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use MVC\Classes\CSRF;

class App
{
    public CSRF $csrf;
    public static App $debug;
    public array $env;
    public string $scheme;
    public string $host;
    public string $username;
    public string $password;
    public int $port;
    public string $routePath;
    public string $query;
    public string $fragment;
    public Request $request;
    public Response $response;
    public array $session;
    public Router $router;
    public string $locale;
    public Logger $logger;

    /*
     *  constructor
     */
    public function __construct()
    {
        require_once '../../Autoloader.php';
        
        $this->csrf = new CSRF();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
        
        $this->env = $_ENV;
        $this->locale = 'en';
        $this->logger = new Logger($this, 'app/' . $this->request->timestamp . '.txt');
        $this->logger->purge('app', 1)->log();

        if(!isset($_SESSION)) {
            session_start();
        }

        $this->session = $_SESSION;
        self::$debug = $this;
    }

    /*
    *   returns App object
    */
    public static function body()
    {
        return self::$debug;
    }

    /*
    *   dd: Die and Debug
    *   @param  dynamic  $data
    */
    public static function dd($data)
    {
        die('<pre>' . print_r($data, true) . '</pre>');
    }

    /*
     *  Runs application
     */
    public function run(): void
    {
        $this->response->get($this);
    }

}