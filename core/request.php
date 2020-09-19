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
        $httpStatusCode = (isset($httpStatusCode)) ? $httpStatusCode : http_response_code();
        self::addHeader("HTTP/1.1 $httpStatusCode", true, $httpStatusCode);
        return $httpStatusCode;
    }

    /**
     *  return the data as JSON
     *  optional http response code
     *  @param $data
     *  @param @httpStatusCode
     */
    public function json($data, $httpStatusCode = null) {

        if(isset($httpStatusCode)){ 
            self::httpResponseCode($httpStatusCode);
        } 

        return json_encode($data);
    }

    public function resetRouteParameters(){
        $_SESSION['routeParameters'] = array();
    }

    /**
     *  returns specified route parameter
     */
    public function routeParameter($requestedParam) {

        $parameters = $_SESSION['routeParameters'];
        
        if(count($parameters) === 0){
            return 0;
        }
        else {
            foreach($parameters as $parameter => $value) {
                
                if($parameter == $requestedParam){
                    return $value;
                }
            }
        }
    }

}
