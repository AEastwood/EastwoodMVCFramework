<?php

namespace App\Exceptions;

class RouteAlreadyExistsException extends \Exception {

    /**
     *  User provided error message
     */
    private $errorMessage;

    /**
     *  Method type(s) of duplicated route
     */
    private $methodType;

    /**
     *  Custom error code
     */
    private $errorCode;

    /**
     *  Construct method
     */
    public function __construct($message, $methodType, $code = 0, Exception $previous = null) {
        $this->errorMessage = $message;
        $this->methodType = $methodType;
        $this->errorCode = $code;
    }

    /**
     *  @return $error
     */
    public function __toString() {
        $error = "Warning [{$this->code}]: Route ({$this->errorMessage}) already has an existing route with the HTTP method: {$this->methodType}\n";
        return $error;
    }

}