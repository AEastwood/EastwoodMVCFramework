<?php

namespace MVC\Classes;

class Storage {

    private static string $storageLocation = '../storage/public/';

    /**
     *  get file from local storage
     */
    public static function get(string $file): void
    {
        if(!filter_var($file, FILTER_SANITIZE_STRING)) {
            throw new NonValidFileStringException($file);
        }

        if(file_exists($file)) {
            $file = file_get_contents(self::$storageLocation . $file);
            echo $file;
            return;
        }

        throw new FileDoesNotExistException($file);
    }

    /**
     *  put contents in file in local storage
     */
    public static function put(string $file, string $contents, array $config = []): void
    {
        if(!filter_var($file, FILTER_SANITIZE_STRING) && !isset($file, $contents)) {
            throw new UnableToCommitFileContentsException($file, $contents);
        }

        try {
            file_put_contents(self::$storageLocation . $file, $contents);
            return;
        }
        catch (Exception $e)
        {

        }
    }

    /**
     *  put contents in file in local storage
     */
    public static function putIfNotExists(string $file, string $contents, array $config = []): void
    {
        if(!filter_var($file, FILTER_SANITIZE_STRING) && !isset($file, $contents)) {
            throw new UnableToCommitFileContentsException($file, $contents);
        }

        if(!file_exists(self::$storageLocation . $file)) {
            try {
                file_put_contents(self::$storageLocation . $file, $contents);
                return;
            }
            catch (Exception $e)
            {

            }
        }

        
    }

}
