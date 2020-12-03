<?php

namespace MVC\Classes;

use Defuse\Crypto\Crypto;
use MVC\Classes\App;
use PragmaRX\Random\Random;

class CSRF {

    /**
     *  CSRF token
     */
    public string $csrf_token;
    public string $name;

    /**
     *  constructor
     */
    public function __construct()
    {
        $this->name = 'X-CSRF-TOKEN';
    }

    /**
     *  get CSRF token from cookie
     *  Cookie is encrypted, return new csrf token if csrf token is modified in anyway
     */
    private function getCSRF(): string
    {
        if(Cookie::getCookie($this->name) !== null) {
            $csrf_token = Cookie::getCookie($this->name);            
            
            return ($csrf_token);
        }

        return $this->generateNewToken();
    }

    /**
     *  generate new csrf token with 24hour persistance
     */
    private function generateNewToken(): string
    {
        $this->purge($this->name);

        $csrf_token = (new Random)->size(128)->get();
        $expiry = time() + 86400;
        Cookie::setCookie($this->name, $csrf_token, $expiry, '/');
        
        return ($csrf_token);
    }

    /**
     *  returns true if CSRF is valid
     */
    public function hasValidCSRF(): bool
    {
        if($_POST['csrf'] === $this->csrf_token) {
            return (true);
        }

        return (false);
    }

    /**
     *  load csrf token
     */
    public function load(): void
    {
        $this->csrf_token = $this->getCSRF();
    }

    /**
     *  purge existing CSRF cookies
     */
    private function purge($name): void
    {
        Cookie::delete($name);
    }


}