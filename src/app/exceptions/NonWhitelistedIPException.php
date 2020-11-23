<?php


namespace MVC\App\Exceptions;


use Exception;

class NonWhitelistedIPException extends Exception
{

    public $message;

    public function __construct($message = '', $code = 202, Exception $previous = null)
    {
        $this->message = $message;

        parent::__construct('Sorry, but your IP address is not allowed to view this content.', $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}