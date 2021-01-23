<?php

namespace MVC\Classes\Storage;

use MVC\Classes\Storage\Uploads\FileUpload;
use PragmaRX\Random\Random;

class File
{
    public string $path;
    private string $newPath;
    private string $extension;
    public string $mimetype;
    public int $size;
    public bool $valid;

    /**
     * File constructor.
     * @param string $fileTmpName
     */
    public function __construct(string $fileTmpName)
    {
        $this->extension = '';
        $this->path = $fileTmpName;
        $this->valid = false;

        $this->getInformation();
    }

    /**
     * get file info
     */
    private function getInformation(): void
    {
        $this->mimetype  = mime_content_type($this->path);
        $this->size      = filesize($this->path) ?? -1;
        $this->extension = pathinfo($this->path, PATHINFO_EXTENSION) ?? '';
        $this->newPath   = '../storage/public/' . self::getRandomName() . '.' . $this->extension;
    }

    /**
     * return new path of the file
     * @return string
     */
    public function getNewPath(): string
    {
        return $this->newPath;
    }

    /**
     * get random name
     * @return string
     */
    public static function getRandomName(): string
    {
        $filename = (new Random)->size(32)->get();

        if(file_exists('../storage/public/' . $filename)) {
            self::getRandomName();
        }

        return $filename;
    }

    /**
     * returns if file is valid or not
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

}