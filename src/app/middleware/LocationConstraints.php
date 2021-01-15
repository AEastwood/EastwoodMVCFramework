<?php

namespace MVC\App\Middleware;

use MVC\Classes\App;
use MVC\Classes\Cookie;
use MVC\Classes\Controller;
use MVC\Classes\Middleware;

class LocationConstraints extends Middleware{

    /**
    *   Refuses access to blacklisted IP addresses
    */
    public static function locationBlacklisted()
    {
        $blacklist = explode(',', APP::body()->env['LOCATION_BLACKLIST']);
        $cuc = Cookie::getAndDecryptCookie('_cuc') ?? App::body()->request->client->get()->geoplugin_countryName;

        if(in_array($cuc, $blacklist)) {
            return Controller::view('errors.error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website from your country.'
            ]);
        }

        return self::next();
    }
    
    /**
    *   Only allows whitelisted IP addresses to continue
    */
    public static function locationWhitelisted()
    {
        $whitelist = explode(',', APP::body()->env['LOCATION_WHITELIST']);
        $cuc = Cookie::getAndDecryptCookie('_cuc') ?? App::getCountry();

        if(!in_array($cuc, $whitelist)) {
            return Controller::view('errors.error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website from your country.'
            ]);
        }

        return self::next();
    }

}