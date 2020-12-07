<?php

namespace MVC\Classes;

use Exception;

class Client {

    public object $client;

    public function __construct()
    {
        $this->client = $this->getClient();
    }
    
    /**
     *  return object containing client
     */
    private function getClient(): object
    {
        try {
            $client = file_get_contents('http://www.geoplugin.net/json.gp');
            $client = json_decode($client);

            if($client->geoplugin_status === 200) {
                return ($client);
            }
        }
        catch(Exception $e) {
            
        }

        return Controller::view('errors.error',[
            'code' => 500,
            'message' => 'unable to initialise application'
        ], 500);
    }
}