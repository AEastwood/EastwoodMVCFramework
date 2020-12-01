<?php

namespace MVC\Classes;

use MVC\Classes\Controller;

class Error {

    /**
     *  handle error
     */
    public static function handle(Exception $exception): void
    {
        Controller::error('fatal', [
            'code'      => $exception->code,
            'message'   => $exception->message,
        ]);
    }

}