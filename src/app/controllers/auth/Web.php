<?php 

namespace MVC\App\Controllers\Auth;

class Web {

    private static bool $authed = false;

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
        if(!self::$authed) {
            header('Location: /login');
        }
    }

}