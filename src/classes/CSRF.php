<?php

namespace MVC\Classes;

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
        $this->csrf_token = $this->getCSRF();
    }

    /**
     *  get CSRF token from cookie
     *  Cookie is encrypted, return new csrf token if csrf token is modified in anyway
     */
    private function getCSRF(): string
    {
        $tokenFromCookie = Cookie::getAndDecryptCookie($this->name);

        if($tokenFromCookie !== null) {
            return $tokenFromCookie;
        }

        return $this->generateNewToken();
    }

    /**
     *  generate new csrf token with 24hour persistance
     */
    private function generateNewToken(): string
    {
        $this->purge($this->name);

        $this->csrf_token = (new Random)->size(128)->get();
        $expiry = time() + 86400;
        Cookie::setAndEncryptCookie($this->name, $this->csrf_token, $expiry, '/');
        
        return $this->csrf_token;
    }

    /**
     *  purge existing CSRF cookies
     * @param $name
     */
    private function purge($name): void
    {
        Cookie::delete($name);
    }


}