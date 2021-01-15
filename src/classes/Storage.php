<?php

namespace MVC\Classes;

class Storage {

    private static string $storageLocation = '../storage/public/';

    /**
     *  change owner of file
     * @param string $filename
     * @param string $owner
     */
    public static function changeOwner(string $filename, string $owner = 'www-data')
    {
        if(!file_exists($filename)){
            App::body()->logger->info('Tried to change owner of ' . $filename . ' but it does not exist.');
            return;
        }

        chown($filename, $owner);
    }

    /**
     *  change permissions of file
     * @param string $filename
     * @param int $permissions
     */
    public static function changePermissions(string $filename, int $permissions)
    {
        if(!file_exists($filename)){
            App::body()->logger->info('Tried to change permissions of ' . $filename . ' but it does not exist.');
            return;
        }

        chmod($filename, $permissions);
    }

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
