<?php

namespace MVC\Classes\Storage;

use MVC\Classes\App;
use MVC\Classes\Storage\Uploads\FileUpload;

class Storage
{
    private static string $storageLocation = '../storage/';

    /**
     *  change owner of file
     * @param string $filename
     * @param string $owner
     */
    public static function changeOwner(string $filename, string $owner = 'www-data')
    {
        if (!file_exists($filename)) {
            App::body()->logger->info("Tried to change owner of '$filename' but it does not exist.");
            return;
        }

        try {
            chown($filename, $owner);
        } catch (\Exception) {
        }

    }

    /**
     * delete file
     * @param $filename
     */
    public static function delete($filename): void
    {
        if (!self::exists($filename)) {
            App::body()->logger->info("Unable to delete file '$filename' as it does not exist");
            return;
        }

        unlink($filename);
    }

    /**
     *  change permissions of file
     * @param string $filename
     * @param int $permissions
     */
    public static function changePermissions(string $filename, int $permissions)
    {
        if (!file_exists($filename)) {
            App::body()->logger->info('Tried to change permissions of ' . $filename . ' but it does not exist.');
            return;
        }

        chmod($filename, $permissions);
    }

    /**
     * check file exists
     * @param $filename
     * @return bool
     */
    public static function exists($filename): bool
    {
        return file_exists($filename);
    }

    /**
     * check file exists and is older than $minutes
     * @param string $filename
     * @param int $minutes
     * @return bool
     */
    public static function existsOlderThan(string $filename, int $minutes): bool
    {
        return file_exists($filename) && (filemtime($filename) > (time() - $minutes));
    }

    /**
     * check file exists and is younger than $minutes
     * @param string $filename
     * @param int $minutes
     * @return bool
     */
    public static function existsYoungerThan(string $filename, int $minutes): bool
    {
        return file_exists($filename) && (filemtime($filename) < (time() - $minutes));
    }

    /**
     *  get file from local storage
     * @param string $file
     * @return string
     */
    public static function get(string $file): string
    {
        if (!file_exists(self::$storageLocation . $file)) {
            App::body()->logger->info('Unable to commit to file "' . $file . '" as it doesn\'t exist.');
            return 'Error: Unable to get file "' . $file . '" as it does not exist.';
        }

        try {
            return file_get_contents(self::$storageLocation . $file);
        } catch (\Exception $e) {
            App::body()->logger->error('Unable to read file "' . $file . '", Error: ' . $e->getMessage());
        }
    }

    /**
     * moves and processes file for upload. This will validate the mime type and return the
     * absolute path of the newly upload file
     * @param string $filename
     * @return string
     */
    public static function initUpload(string $filename): string
    {

        return $filename;
    }

    /**
     * Upload file and return array containing filepath, filesize and the result of the upload
     * @param array $mimetypes
     * @return array
     */
    public static function publicUpload(array $mimetypes): array
    {
        $file = $_FILES['file']['name'] ?? '';

        try {
            $file = '../storage/processing/' . $file;
            move_uploaded_file($_FILES['file']['tmp_name'], $file);
            return (new FileUpload($mimetypes))->save(new File($file));
        } catch (\Exception $e) {
            App::body()->logger->error('[Storage] Unable to upload file, Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * create file
     * @param string $file
     * @param string $contents
     */
    public static function put(string $file, string $contents): void
    {
        try {
            file_put_contents(self::$storageLocation . $file, $contents);
        } catch (\Exception $e) {
            App::body()->logger->info('[Storage] Unable to put contents in file ' . $file . ', Error: ' . $e->getMessage());
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
        if (file_exists(self::$storageLocation . $file)) {
            App::body()->logger->info('Unable to commit to file "' . $file . '" as it already exists.');
            return;
        }

        try {
            file_put_contents(self::$storageLocation . $file, $contents);
            return;
        } catch (Exception $e) {
            App::body()->logger->error('Unable to commit to file "' . $file . '", Error: ' . $e->getMessage());
        }
    }

}
