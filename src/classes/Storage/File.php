<?php

namespace MVC\Classes\Storage;

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
        $this->newPath   = '../storage/public/' . FileSystem::getRandomName() . '.' . $this->extension;
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
     * returns if file is valid or not
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

}