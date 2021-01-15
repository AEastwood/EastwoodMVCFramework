<?php


namespace MVC\App\Middleware;

use MVC\Classes\App;
use MVC\Classes\Middleware;
use MVC\Classes\Response;

class CSRF extends Middleware
{

    /**
     * checks CSRF token provided is valid
     */
    public static function validCSRF()
    {
        $token = $_POST['csrf'];

        if($token !== App::body()->csrf->token) {
            return Response::json(['code' => 500, 'message' => 'Invalid CSRF token was provided, please try again.'], 200);
        }

        return self::next();
    }

}