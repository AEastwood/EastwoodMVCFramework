<?php

namespace MVC\App\Controllers\App;

use MVC\Classes\App;
use MVC\Classes\Controller;

class LocationConstraints {

    /*
    *   Refuses access to blacklisted IP addresses
    */
    public function locationBlacklisted(): void
    {
        $blacklist = explode(',', APP::body()->env['LOCATION_BLACKLIST']);

        if(in_array(App::body()->request->headers['CF-IPCountry'], $blacklist)) {
            Controller::error('error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website from your country.'
            ]);
            exit;
        }
        
    }
    
    /*
    *   Only allows whitelisted IP addresses to continue
    */
    public function locationWhitelisted(): void
    {
        $whitelist = explode(',', APP::body()->env['LOCATION_WHITELIST']);

        if(!in_array(App::body()->request->headers['CF-IPCountry'], $whitelist)) {
            Controller::error('error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website from your country.'
            ]);
            exit;
        }
        
    }

}