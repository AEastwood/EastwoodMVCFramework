<?php


namespace MVC\App\Exceptions;


use Exception;

class ViewDoesntExistException extends Exception
{

    public $message;

    public function __construct($message, $code = 202, Exception $previous = null)
    {
        $this->message = $message;

        parent::__construct('The specified view "' . $message . '" doesn\'t exist', $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}