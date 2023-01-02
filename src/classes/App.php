<?php

namespace MVC\Classes;

use Defuse\Crypto\Key;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use MVC\Classes\Database\Database;
use MVC\Classes\Http\Request;
use MVC\Classes\Http\Response;
use MVC\Classes\Routes\Router;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class App
{
    /**
     * @var App
     */
    private static App $app;

    /**
     * @var Auth
     */
    private static Auth $user;

    /**
     * @var Session
     */
    public Session $session;

    /**
     * @var Auth
     */
    private Auth $auth;

    /**
     * @var CSRF
     */
    public CSRF $csrf;

    /**
     * @var Database
     */
    public Database $database;

    private array $debugBacktrace;

    /**
     * @var Key
     */
    public Key $key;

    /**
     * @var Logger
     */
    public Logger $logger;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * @var Response
     */
    public Response $response;

    /**
     * @var string
     */
    private string $rootPath;

    /**
     * @var Router
     */
    public Router $router;

    /**
     * @var array
     */
    public array $env;

    /**
     * @var string|mixed
     */
    public string $locale;

    /**
     *  constructor
     *  creates new instances of all core components of the framework
     *  checks and creates a new session if one doesn't exist
     *  creates two static instances of the APP and USER for external use
     */
    public function __construct()
    {
        $this->rootPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

        $dotenv = Dotenv::createImmutable($this->rootPath);
        $dotenv->load();

        if ($_ENV['RELEASE_MODE'] === 'debug') {
            $whoops = new Run;
            $whoops->pushHandler(new PrettyPageHandler);
            $whoops->register();
            $this->debugBacktrace = debug_backtrace();
        }

        $this->logger = new Logger('APP');
        $this->logger->pushHandler(new StreamHandler('../storage/logs/app.log', Logger::WARNING));
        $this->env = $_ENV;
        $this->locale = env('APP_LOCALE');
        self::$app = $this;
        $this->key = $this->getKey();
        $this->request = new Request();
        $this->session = new Session();
        $this->auth = new Auth(24);
        $this->csrf = new CSRF();
        $this->database = new Database();
        $this->response = new Response();
        $this->router = new Router();
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
        $key = file_get_contents("{$this->rootPath}{$key}");
        $key = rtrim($key);

        try {
            return Key::loadFromAsciiSafeString($key);
        } catch (\Exception $e) {
            $this->logger->error('Unable to load encryption key from file, Error: ' . $e->getMessage());
        }
    }

    /**
     *  Runs application
     */
    public function run(): void
    {
        $this->response->get($this->router)();
    }

}