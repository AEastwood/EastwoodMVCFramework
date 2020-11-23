<?php


namespace MVC\App\Exceptions;


use Exception;

class DuplicateRouteException extends Exception
{

    public $message;

    public function __construct($message, $code = 201, Exception $previous = null)
    {
        $this->message = $message;

        parent::__construct('A route named "' . $message .'" is already in the route collection. Route names must be unique.', $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}