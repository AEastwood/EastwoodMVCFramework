<?php

namespace MVC\Classes;

use Defuse\Crypto\Key;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use MVC\Classes\Database\Database;
use MVC\Classes\Http\Request;
use MVC\Classes\Http\Response;
use MVC\Classes\Routes\Router;

class App
{
    private static App $app;
    private static Auth $user;

    public Session $session;
    private Auth $auth;
    public CSRF $csrf;
    public Database $database;
    public Key $key;
    public Logger $logger;
    public Request $request;
    public Response $response;
    public Router $router;

    public array $env;
    public string $locale;

    /**
     *  constructor
     *  creates new instances of all core components of the framework
     *  checks and creates a new session if one doesn't exist
     *  creates two static instances of the APP and USER for external use
     */
    public function __construct()
    {
        ini_set('session.use_strict_mode', 1);

        $this->logger = new Logger('APP');
        $this->logger->pushHandler(new StreamHandler('../storage/logs/app.log', Logger::WARNING));

        $this->env      = $_ENV;
        $this->locale   = $_ENV['APP_LOCALE'];

        self::$app      = $this;

        $this->key      = $this->getKey();
        $this->request  = new Request();
        $this->session  = new Session();

        $this->auth     = new Auth(24);
        $this->csrf     = new CSRF();
        $this->database = new Database();
        $this->response = new Response();
        $this->router   = new Router();

        self::$user = $this->auth;

        $this->session->createUserInstanceCookies();
    }

    /**
    *   returns App object
    */
    public static function body(): App
    {
        return self::$app;
    }

    /**
     *  get decryption key from file
     */
    private function getKey(): Key
    {
        $key = $_ENV['SECRET'];
        $key = file_get_contents('../../' . $key);
        $key = rtrim($key);

        try {
            return Key::loadFromAsciiSafeString($key);
        }
        catch (\Exception $e) {
            $this->logger->error('Unable to load encryption key from file, Error: ' . $e->getMessage());
        }
    }

    /**
     *  Runs application
     */
    public function run(): void
    {
        $this->response->get($this->request, $this->router)();
    }

}