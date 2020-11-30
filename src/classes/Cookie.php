<?php

namespace MVC\Classes;

use Defuse\Crypto\Crypto;
use MVC\Classes\App;

class Cookie {

    public static function delete(string $name): void
    {
        setCookie($name, '', -3600);
    }

    /*
     *  gets cookie
     */
    public static function getCookie($name)
    {
        if(isset($_COOKIE[$name])) {
            try {
                return Crypto::decrypt($_COOKIE[$name], App::body()->key);
            }
            catch(\Exception $e) {
                return null;
            }
        }
    }

    /*
    *   sets cookie
    */
    public static function setCookie(string $name, string $value, int $expiration, string $accessor = '/')
    {
        setcookie($name, Crypto::encrypt($value, App::body()->key), $expiration, $accessor);
    }

}