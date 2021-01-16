<?php 

namespace MVC\App\Middleware;

use MVC\Classes\App;
use MVC\Classes\Middleware;
use MVC\Classes\Response;

class Auth extends Middleware {

    /**
    *   returns authorisation status
    */
    public function authenticatedAPI()
    {
        if(!App::body()->request->headers['Authorization']) {
            return Response::json([
                'code' => 500,
                'message' => 'Invalid authentication token'
            ], 500);
        }
    }

    /**
    *   returns authorisation status
    */
    public function authenticatedWeb()
    {
        if(App::user()->authed) {
            header('Location: /login');
        }
    }

}