<?php 

namespace MVC\App\Middleware;

use MVC\Classes\App;
use MVC\Classes\Response;

class Auth {

    /*
    *   returns authorisation status
    */
    public function authenticated_api()
    {
        if(!App::body()->request->headers['Authorization']) {
            return Response::json([
                'code' => 500,
                'message' => 'Invalid authentication token'
            ], 500);
        }
    }

    /*
    *   returns authorisation status
    */
    public function authenticated_web()
    {
        if(App::user()->authed) {
            header('Location: /login');
        }
    }

}