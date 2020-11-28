<?php 

namespace MVC\App\Middleware;

use MVC\Classes\App;

class Auth {

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