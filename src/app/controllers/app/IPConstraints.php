<?php

namespace MVC\App\Controllers\App;

use MVC\Classes\App;
use MVC\Classes\Controller;
use MVC\App\Exceptions\NonWhitelistedIPException;

class IPConstraints {

    /*
    *   Refuses access to blacklisted IP addresses
    */
    public function blacklisted(): void
    {
        $blacklist = explode(',', APP::body()->env['IP_BLACKLIST']);

        if(in_array(App::body()->request->headers['CF-Connecting-IP'], $blacklist)) {
            Controller::error('iprejected');
        }
    }
    
    /*
    *   Only allows whitelisted IP addresses to continue
    */
    public function whitelisted(): void
    {
        $whitelist = explode(',', APP::body()->env['IP_WHITELIST']);

        if(!in_array(App::body()->request->headers['CF-Connecting-IP'], $whitelist)) {
            Controller::error('iprejected');
        }
    }

}