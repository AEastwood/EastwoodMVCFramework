<?php

class RouteAlreadyExistsException {

    public $error;

    public function __construct($error) {
        $this->error = $error;
    }

}