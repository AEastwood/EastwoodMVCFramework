<?php

namespace MVC\App\Exceptions;


use Exception;

class InvalidRouteException extends Exception
{

    public $message;

    public function __construct($message, $code = 203, Exception $previous = null)
    {
        $this->message = $message;

        parent::__construct($message . ' is not a valid route.');
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}