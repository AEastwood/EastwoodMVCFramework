<?php

namespace MVC\Classes;

class Storage {

    private static string $storageLocation = '../storage/public/';

    /**
     *  get file from local storage
     * @param string $file
     * @return string
     */
    public static function get(string $file): string
    {
        if(!file_exists(self::$storageLocation . $file)) {
            App::body()->logger->info('Unable to commit to file "' . $file .'" as it doesn\'t exist.');

            return 'Error: Unable to get file "' . $file .'" as it does not exist.';
        }

        if(file_exists($file)) {
            try {
                return file_get_contents(self::$storageLocation . $file);
            }
            catch (\Exception $e) {
                App::body()->logger->error('Unable to read file "' . $file . '", Error: ' . $e->getMessage());
            }
        }
    }

    /**
     *  put contents in file in local storage
     * @param string $file
     * @param string $contents
     * @param array $config
     */
    public static function put(string $file, string $contents, array $config = []): void
    {
        if(!file_exists(self::$storageLocation . $file)) {
            App::body()->logger->info('Unable to commit to file "' . $file .'" as it does not exist.');
            return;
        }

        try {
            file_put_contents(self::$storageLocation . $file, $contents);
            return;
        }
        catch (Exception $e) {
            App::body()->logger->error('Unable to commit to file "' . $file .'", Error: ' . $e->getMessage());
        }
    }

    /**
     *  put contents in file in local storage if the file does not exist already
     * @param string $file
     * @param string $contents
     * @param array $config
     */
    public static function putIfNotExists(string $file, string $contents, array $config = []): void
    {
        if(file_exists(self::$storageLocation . $file)) {
            App::body()->logger->info('Unable to commit to file "' . $file .'" as it already exists.');
            return;
        }

        try {
            file_put_contents(self::$storageLocation . $file, $contents);
            return;
        }
        catch (Exception $e) {
            App::body()->logger->error('Unable to commit to file "' . $file .'", Error: ' . $e->getMessage());
        }

        
    }

}
