<?php


namespace MVC\App\Exceptions;


use Exception;

class DatabaseConnectionError extends Exception
{

    public $message;

    public function __construct($message, $code = 301, Exception $previous = null)
    {
        $this->message = $message;

        parent::__construct('Unable to connect to database: ' . $message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}