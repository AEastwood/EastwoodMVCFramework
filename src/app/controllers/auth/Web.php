<?php 

namespace MVC\App\Controllers\Auth;

use MVC\Classes\App;

class Web {

    /*
    *   returns authorisation status
    */
    public function authenticated_api(): void
    {
        if(!App::body()->request->headers['Authorization']) {
            Controller::error('bearertoken');
        }
    }

    /*
    *   returns authorisation status
    */
    public function authenticated_web(): void
    {
        if(App::user()->authed) {
            header('Location: /login');
        }
    }

}