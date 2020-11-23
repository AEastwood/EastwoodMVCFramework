<?php


namespace MVC\App\Exceptions;


use Exception;

class InvalidProviderActionException extends Exception
{

    public $message;

    public function __construct($message, $code = 202, Exception $previous = null)
    {
        $this->message = $message;

        parent::__construct('Invalid provider action, please assign a valid action to: ' . $message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}