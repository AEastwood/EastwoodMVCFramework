<?php

namespace MVC\App\Middleware;

use MVC\Classes\App;
use MVC\Classes\Cookie;
use MVC\Classes\Controller;
use MVC\Classes\Middleware;

class IPConstraints extends Middleware {

    /**
    *   Refuses access to blacklisted IP addresses
    */
    public static function ipBlacklisted()
    {
        $blacklist = explode(',', APP::body()->env['IP_BLACKLIST']);
        $cip = Cookie::getAndDecrypt('_cip') ?? App::getIP();

        if(in_array($cip, $blacklist)) {
            return Controller::view('errors.error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website currently.'
            ]);
        }

        return self::next();
    }
    
    /**
    *   Only allows whitelisted IP addresses to continue
    */
    public static function ipWhitelisted()
    {
        $whitelist = explode(',', APP::body()->env['IP_WHITELIST']);
        $cip = Cookie::getAndDecrypt('_cip') ?? App::getIP();

        if(!in_array($cip, $whitelist)) {
            return Controller::view('errors.error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website currently.'
            ]);
        }

        return self::next();
    }

}