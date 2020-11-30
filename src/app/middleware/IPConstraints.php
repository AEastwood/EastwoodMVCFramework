<?php

namespace MVC\App\Middleware;

use MVC\Classes\App;
use MVC\Classes\Cookie;
use MVC\Classes\Controller;

class IPConstraints {

    /*
    *   Refuses access to blacklisted IP addresses
    */
    public static function ipBlacklisted(): void
    {
        $blacklist = explode(',', APP::body()->env['IP_BLACKLIST']);
        $cip = Cookie::getCookie('_cip') ?? App::getIP();

        if(in_array($cip, $blacklist)) {
            Controller::error('error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website currently.'
            ]);
        }

    }
    
    /*
    *   Only allows whitelisted IP addresses to continue
    */
    public static function ipWhitelisted(): void
    {
        $whitelist = explode(',', APP::body()->env['IP_WHITELIST']);
        $cip = Cookie::getCookie('_cip') ?? App::getIP();

        if(!in_array($cip, $whitelist)) {
            Controller::error('error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website currently.'
            ]);
        }
    }

}