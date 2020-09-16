<?php

namespace Core;

class Request {

    /**
     *  Add headers to a response
     */
    public function addHeader($header) {
        header($header);
    }

    /**
     *  Manually set the http response code
     *  @param $httpStatusCode
     */
    public function httpResponseCode($httpStatusCode) {
        return (isset($httpStatusCode)) ? $httpStatusCode : http_response_code();
    }

    /**
     *  return the data as JSON
     *  optional http response code
     *  @param $data
     *  @param @httpStatusCode
     */
    public function json($data, $httpStatusCode = null) {
        $httpStatusCode = (isset($httpStatusCode)) ? $httpStatusCode : http_response_code();
        return json_encode($data);
    }

}
