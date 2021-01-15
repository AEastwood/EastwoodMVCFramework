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
        $prefix = 'EastwoodMVC-';

        $this->state = 'non_session';
        $this->valid = false;

        if(!isset($_SESSION)) {
            session_set_cookie_params(86400);
            session_create_id($prefix);
            session_start();

            $this->create();
            $this->preventHijack();
        }
    }

    /**
     *  session builder
     */
    private function create(): void
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
     * securely wipe and destroy old session
     */
    private function destroy(): void
    {
        session_start();
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(),'',0,'/');
        session_regenerate_id(true);

        $this->create();
    }

    /**
     *  create user instance cookies
     */
    public function createUserInstanceCookies(): void
    {
        $expiry = time() + 86400;

        Cookie::setEncrypted('_cip', App::getIP(), $expiry);
        Cookie::setEncrypted('_cuc', App::getCountry(), $expiry);
        Cookie::setEncrypted('_cloc', App::body()->locale, $expiry);
    }

    /**
     * check if the session has been hijacked
     */
    public function preventHijack(): void
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        if($this->state !== 'has_session') {
            return;
        }

        if(App::getIP() !== $_SESSION['EMVC.app.valid_ip'] || App::body()->request->user_agent !== $userAgent) {
            $this->destroy();
        }
    }
}
