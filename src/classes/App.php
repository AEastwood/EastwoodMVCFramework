<?php

namespace MVC\Classes;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class App
{
    private static App $app;
    private static Auth $user;

    private Session $session;
    private Auth $auth;
    public Client $client;
    public CSRF $csrf;
    public Database $database;
    public Key $key;
    public Request $request;
    public Response $response;
    public Router $router;

    public array $env;
    public string $locale;

    /*
     *  constructor
     *  creates new instances of all core components of the framework
     *  checks and creates a new session if one doesn't exist
     *  creates two static instances of the APP and USER for external use
     */
    public function __construct()
    {
        require_once '../../Autoloader.php';
        
        $this->setup();        
        $this->csrf->load();
        $this->session->createUserInstanceCookies();
    }

    /*
    *   returns App object
    */
    public static function body()
    {
        return self::$app;
    }

    /*
    *   Die and Debug
    *   @param  mixed  $data
    */
    public static function dd($data)
    {
        header('Content-Type: application/json');
        $action = function() use ($data) {
            echo print_r($data, true);
        };

        return $action();
    }

    /**
     *  return client two digit country code
     */
    public static function getCountry(): string
    {
        $keys = [
            'CF-IPCountry',
        ];

        foreach($keys as $key) {
            if (!empty($_SERVER[$key])) {
                return $_SERVER[$key]; 
            } 
        }

        return self::body()->request->client->geoplugin_countryCode ?? 'GB';
    }

    /**
     *  return client IP address
     */
    public static function getIP(): string
    {
        $keys = [
            'CF-Connecting-IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR',
            'HTTP_CLIENT_IP',
        ];

        foreach($keys as $key) {
            if (!empty($_SERVER[$key])) {
                return $_SERVER[$key];
            } 
        }        
   }

    /**
     *  get decryption key from file
     */
    private function loadKey(): Key
    {
        $key = $_ENV['SECRET'];
        $key = file_get_contents('../../' . $key);
        $key = rtrim($key);
        return Key::loadFromAsciiSafeString($key);
    }

    /*
     *  Runs application
     */
    public function run(): void
    {
        $this->response->get($this)();
    }

    /**
     *  change some initial run time configuration settings
     */
    private function setup(): void
    {
        ini_set('session.use_strict_mode', 1);
        $this->env      = $_ENV;
        $this->locale   = 'en';

        self::$app      = $this;
        $this->key      = $this->loadKey();
        $this->client   = new Client();
        $this->session  = new Session();

        $this->auth     = new Auth(24);
        $this->csrf     = new CSRF();
        $this->database = new Database();
        $this->response = new Response();
        $this->router   = new Router();
        $this->request  = new Request();

        self::$user = $this->auth;
    }

    /*
     *  returns Auth object
     */
    public static function user(): object
    {
        return self::$user;
    }

}