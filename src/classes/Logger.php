<?php

namespace MVC\Classes;

class Logger
{
    /**
     *  data to log
     */
    public object $data;

    /**
     *  path of log file
     */
    public string $log_file;

    /**
     *  constructor
     */
    public function __construct($data, $log_file)
    {
        $this->data = $data;
        $this->log_file = $log_file;
    }

    /**
     *      delete logs older than $hours
     *      @param $scope log path
     *      @param $hours hours to
     */
    public function purge(string $scope, float $hours): object
    {
        $hours = time() - ($hours * 3600);
        $logFiles = glob('../storage/logs/' . $scope . '/*.txt');

        foreach($logFiles as $logfile) {
            $modifiedTime = filemtime($logfile);

            if($hours > $modifiedTime) {
                unlink($logfile);
            }
        }
        
        return ($this);
    }

    /*
     *  saves information to file
     */
    public function log()
    {
        file_put_contents('../storage/logs/' . $this->log_file, serialize($this->data));
        return ($this);
    }

}