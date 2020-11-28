<?php

namespace MVC\App\Controllers\App;

use MVC\Classes\App;
use MVC\Classes\Controller;

class IPConstraints {

    /*
    *   Refuses access to blacklisted IP addresses
    */
    public function ipBlacklisted(): void
    {
        $blacklist = explode(',', APP::body()->env['IP_BLACKLIST']);

        if(in_array(App::body()->request->headers['CF-Connecting-IP'], $blacklist)) {
            Controller::error('error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website currently.'
            ]);
            exit;
        }

    }
    
    /*
    *   Only allows whitelisted IP addresses to continue
    */
    public function ipWhitelisted(): void
    {
        $whitelist = explode(',', APP::body()->env['IP_WHITELIST']);

        if(!in_array(App::body()->request->headers['CF-Connecting-IP'], $whitelist)) {
            Controller::error('error', [
                'code' => 401,
                'message' => 'Sorry, you are unable to visit this website currently.'
            ]);
            exit;
        }
    }

}