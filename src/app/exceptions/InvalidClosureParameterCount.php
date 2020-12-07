<?php


namespace MVC\App\Exceptions;


use Exception;

class InvalidClosureParameterCount extends Exception
{

    public $message;
    public $code;

    public function __construct($message = '', $code = 202, Exception $previous = null)
    {
        $this->message = $message;
        $this->code = $code;

        parent::__construct('All dynamic route parameters must have an associated function argument', $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}