<?php

namespace MVC\Classes;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use PragmaRX\Random\Random;

class CSRF {

    /**
     *  CSRF token
     */
    public string $csrf_token;

    /**
     *  App secret, decryption key
     */
    public Key $key;

    /**
     *  constructor
     */
    public function __construct()
    {        
        $key = $_ENV['SECRET'];
        $key = file_get_contents('../../' . $key);
        
        $this->key = Key::loadFromAsciiSafeString($key);
        $this->csrf_token = $this->getCSRF();
    }

    /**
     *  get CSRF token from cookie
     *  Cookie is encrypted
     */
    private function getCSRF(): string
    {
        if(Cookie::getCookie('X-CSRF-TOKEN') !== null) {
            $cookie = Cookie::getCookie('X-CSRF-TOKEN');
            $csrf_token = Crypto::decrypt($cookie, $this->key);
            
            return ($csrf_token);
        }

        return $this->generateNewToken();
    }

    /**
     *  generate new csrf token with 24hour persistance
     */
    private function generateNewToken(): string
    {
        $csrf_token = (new Random)->size(128)->get();
        $expiry = time() + 86400;
        Cookie::setCookie('X-CSRF-TOKEN', Crypto::encrypt($csrf_token, $this->key), $expiry, '/');
        
        return ($csrf_token);
    }


}