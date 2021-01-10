<?php

namespace MVC\Classes;

use MVC\Classes\App;
use Carbon\Carbon;

class Session {

    public string $id;
    public array $session;
    private string $state;
    private bool $valid;

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
        $this->id = $_COOKIE['PHPSESSID'] ?? 'CREATED_SESSION';

        $this->session = $_SESSION;
        $this->state = 'has_session';

        if($this->state === 'has_session') {
            $_SESSION['EMVC.app.expires']       = Carbon::now()->addDay()->toDateTimeString();
            $_SESSION['EMVC.app.valid_country'] = App::getCountry();
            $_SESSION['EMVC.app.valid_ip']      = App::getIP();
            $_SESSION['EMVC.auth']              = [];
            $_SESSION['EMVC.validity']          = true;
            $_SESSION['EMVC.parameters']        = array();

            $this->valid = $_SESSION['EMVC.validity'];
        }
    }

    /**
     *  create user instance cookies
     */
    public function createUserInstanceCookies(): void
    {
        $expiry = time() + 86400;

        Cookie::setAndEncryptCookie('_cip', App::getIP(), $expiry);
        Cookie::setAndEncryptCookie('_cuc', App::getCountry(), $expiry);
        Cookie::setAndEncryptCookie('_cloc', App::body()->locale, $expiry);
    }

    /**
     *  used to prevent session fixation attacks
     */
    public static function preventFixation(): void
    {
        $initiated = 'EMVC.initiated';

        if (!isset($_SESSION[$initiated])) {
            session_regenerate_id();
            $_SESSION[$initiated] = true;
         }
    }

}
