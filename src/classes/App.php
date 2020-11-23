<?php

namespace MVC\Classes;

class App
{
    private static App $debug;
    private static Auth $user;

    private Auth $auth;
    public CSRF $csrf;
    public Logger $logger;
    public Request $request;
    public Response $response;
    public Router $router;

    public array $env;
    public string $fragment;
    public string $host;
    public string $locale;
    public string $password;
    public int $port;
    public string $query;
    public string $routePath;
    public string $scheme;
    public array $session;
    public string $username;

    /*
     *  constructor
     *  creates new instances of all core components of the framework
     *  checks and creates a new session if one doesn't exist
     *  creates two static instances of the APP and USER for external use
     */
    public function __construct()
    {
        require_once '../../Autoloader.php';

        $this->auth = new Auth(24);
        $this->csrf = new CSRF();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
        $this->logger = new Logger($this, 'app/' . $this->request->timestamp . '.txt');

        $this->env = $_ENV;
        $this->locale = 'en';
        $this->logger->purge('app', 1)->log();

        if(!isset($_SESSION)) {
            session_start();
        }

        $this->session = $_SESSION;

        self::$debug = $this;
        self::$user = $this->auth;
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

    /*
     *  returns Auth object
     */
    public static function user(): object
    {
        return self::$user;
    }

}