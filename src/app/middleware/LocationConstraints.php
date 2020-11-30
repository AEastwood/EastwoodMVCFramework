<?php

namespace MVC\App\Middleware;

use MVC\Classes\App;
use MVC\Classes\Cookie;
use MVC\Classes\Controller;

class LocationConstraints {

    /*
    *   Refuses access to blacklisted IP addresses
    */
    public static function locationBlacklisted(): void
    {
        $blacklist = explode(',', APP::body()->env['LOCATION_BLACKLIST']);
        $cuc = Cookie::getCookie('_cuc') ?? App::getCountry();

        if(in_array($cuc, $blacklist)) {
            Controller::error('error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website from your country.'
            ]);
        }
        
    }
    
    /*
    *   Only allows whitelisted IP addresses to continue
    */
    public static function locationWhitelisted(): void
    {
        $whitelist = explode(',', APP::body()->env['LOCATION_WHITELIST']);
        $cuc = Cookie::getCookie('_cuc') ?? App::getCountry();

        if(!in_array($cuc, $whitelist)) {
            Controller::error('error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website from your country.'
            ]);
        }
        
    }

}