<?php

namespace MVC\Classes;

use MVC\Classes\App;
use MVC\Classes\Cookie;
use Carbon\Carbon;

class Session {

    public string $id;
    public array $session;
    private string $state;
    private bool $valid;
    private string $valid_ip;

    /**
     *  constructor
     */
    public function __construct()
    {
        $this->state = 'non_session';
        $this->valid = false;

        if(!isset($_SESSION)) {
            session_set_cookie_params(86400);
            session_start();

            $this->createSession();
            self::preventFixation();
        }
    }

    /**
     *  session builder
     */
    private function createSession(): void
    {
        $this->id = Cookie::getCookie('PHPSESSID') ?? 'NON_VALID_SESSION';

        $this->session = $_SESSION;
        $this->state = 'has_session';

        if($this->state === 'has_session') {
            $_SESSION['EMVC.app.expires'] = Carbon::now()->addDay()->toDateTimeString();
            $_SESSION['EMVC.app.valid_country'] = $_SERVER["HTTP_CF_IPCOUNTRY"];
            $_SESSION['EMVC.app.valid_ip'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SESSION['EMVC.auth'] = [];
            $_SESSION['EMVC.validity'] = true;

            $this->valid = $_SESSION['EMVC.validity'];
        }
    }

    /**
     *  used to prevent session fixation attacks
     */
    public static function preventFixation(): void
    {
        $initiated = 'EMVC.initiated';

        if (!isset($_SESSION[$initiated])) {
            session_regenerate_id();
            $_SESSION[$initiated] = TRUE;
         }
    }

}
