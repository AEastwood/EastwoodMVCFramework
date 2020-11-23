<?php

namespace MVC\Classes;

class Cookie {

    /*
     *  gets cookie
     */
    public static function getCookie($name)
    {
        if(isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
    }

    /*
    *   sets cookie
    */
    public static function setCookie(string $name, $value, int $expiration, $accessor = '/')
    {
        setcookie($name, $value, $expiration);
    }

}