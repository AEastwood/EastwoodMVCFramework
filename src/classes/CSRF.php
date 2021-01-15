<?php

namespace MVC\Classes;

use PragmaRX\Random\Random;

class CSRF {

    /**
     *  CSRF token
     */
    public string $token;
    public string $name;

    /**
     *  constructor
     */
    public function __construct()
    {
        $this->name = 'X-CSRF-TOKEN';
        $this->token = $this->getToken();
    }

    /**
     *  generate new csrf token with 24hour persistance
     */
    private function generate(): string
    {
        $this->purge($this->name);

        $this->token = (new Random)->size(128)->get();
        $expiry = time() + 86400;
        Cookie::setEncrypted($this->name, $this->token, $expiry, '/');

        return $this->token;
    }

    /**
     *  get CSRF token from cookie
     *  Cookie is encrypted, return new csrf token if csrf token is modified in anyway
     */
    private function getToken(): string
    {
        $tokenFromCookie = Cookie::getAndDecrypt($this->name);

        if($tokenFromCookie !== null) {
            return $tokenFromCookie;
        }

        return $this->generate();
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