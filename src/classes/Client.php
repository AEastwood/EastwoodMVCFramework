<?php

namespace MVC\Classes;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Client {

    public object $client;
    public Logger $logger;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->client = $this->resolve();

        $this->logger = new Logger('Client');
        $this->logger->pushHandler(new StreamHandler('../storage/logs/client.log', Logger::WARNING));
    }

    /**
     * return client
     * @return object
     */
    public function get(): object
    {
        return $this->client;
    }
    
    /**
     *  return object containing client
     */
    private function resolve(): object
    {
        try {
            $client = file_get_contents('http://www.geoplugin.net/json.gp');
            $client = json_decode($client);

            if($client->geoplugin_status === 200) {
                unset($client->geoplugin_credit);
                return ($client);
            }
        }
        catch(Exception $e) {
            $this->logger->error('Unable to get client details from geoplugin.net, Error: ' . $e->getMessage());
        }

        return Controller::view('errors.error',[
            'code' => 500,
            'message' => 'unable to initialise application'
        ]);
    }
}