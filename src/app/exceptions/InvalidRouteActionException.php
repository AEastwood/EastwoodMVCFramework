<?php

namespace MVC\App\Exceptions;

use Exception;

class InvalidRouteMethodException extends Exception
{

    public $message;

    public function __construct($message, string $method, $code = 203, Exception $previous = null)
    {
        $this->message = $message;

        parent::__construct($message . ' is unable to handle requests via this method, ' . $message . ' requires: ' . $method);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}