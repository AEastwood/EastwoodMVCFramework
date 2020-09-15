<?php

namespace Core;

class Request {

    /**
     *  Add headers to a response
     */
    public function addHeader($header) {
        header($header);
    }

}
