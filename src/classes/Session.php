<?php

namespace MVC\Classes;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Session
{
    /**
     * @var object|null
     */
    public ?object $client;

    /**
     * @var string
     */
    public string $id;

    /**
     * @var array
     */
    public array $session;

    /**
     * @var string
     */
    private string $state;

    /**
     * @var Logger
     */
    public Logger $logger;

    /**
     *  constructor
     */
    public function __construct()
    {
        $prefix = 'EastwoodMVC-';

        $this->state = 'non_session';

        if (!isset($_SESSION)) {
            session_set_cookie_params(86400);
            session_create_id($prefix);
            session_start();

            $this->create();
            $this->preventHijack();
        }

        $this->logger = new Logger('Client');
        $this->logger->pushHandler(new StreamHandler('../storage/logs/session.log', Logger::WARNING));

        $this->client = $this->resolveClient();
    }

    /**
     *  session builder
     */
    private function create(): void
    {
        $this->id = $_COOKIE['PHPSESSID'] ?? 'CREATED_SESSION';

        $this->session = $_SESSION;
        $this->state = 'has_session';

        $_SESSION['EMVC.app.expires'] = Carbon::now()->addDay()->toDateTimeString();
        $_SESSION['EMVC.app.valid_country'] = country();
        $_SESSION['EMVC.app.valid_ip'] = ipAddress();
        $_SESSION['EMVC.auth'] = [];
        $_SESSION['EMVC.validity'] = true;
        $_SESSION['EMVC.parameters'] = array();
    }

    /**
     * securely wipe and destroy old session
     */
    private function destroy(): void
    {
        session_start();
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(), '', 0, '/');
        session_regenerate_id(true);

        $this->create();
    }

    /**
     *  create user instance cookies
     */
    public function createUserInstanceCookies(): void
    {
        $expiry = time() + 86400;

        Cookie::setEncrypted('_cip', ipAddress(), $expiry);
        Cookie::setEncrypted('_cuc', country(), $expiry);
        Cookie::setEncrypted('_cloc', App::body()->locale, $expiry);
    }

    /**
     * check if the session has been hijacked
     */
    public function preventHijack(): void
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        if ($this->state !== 'has_session') {
            return;
        }

        if (ipAddress() !== $_SESSION['EMVC.app.valid_ip'] || App::body()->request->user_agent !== $userAgent) {
            $this->destroy();
        }
    }

    /**
     * resolve client into object
     * @return object|null
     */
    private function resolveClient(): ?object
    {
        try {
            $client = new Client();
            $guzzle = new Request('GET', 'http://www.geoplugin.net/json.gp');

            $client->sendAsync($guzzle)->then(function ($response) {
                switch ($response) {
                    case 200:
                        return $response->getBody();

                    case 429:
                        $this->logger->error('[Client] Unable to resolve client as rate limiting is being applied');
                        return new \stdClass();

                    default:
                        $this->logger->error('[Client] Unable to resolve client');
                        return new \stdClass();
                }
            });
        } catch (GuzzleException $e) {
            $this->logger->error('[Client] Unable to resolve client, Error: ' . $e->getMessage());
        }

        return null;
    }
}
