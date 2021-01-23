<?php


namespace MVC\Classes\Storage;


class FileSaveResult
{
    public File $file;
    public string $filename;
    public int $filesize;
    public string $result;



    /**
     * FileSaveResult constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->filesize = filesize($this->filename);
        $this->result = FileStorageResults::UPLOAD_EMPTY_RESPONSE;
    }

    /**
     * return this as an array
     * @return array
     */
    public function asArray(): array
    {
        unset($this->file);
        return (array)$this;
    }

    /**
     * set file as uploaded file
     * @param File $file
     */
    public function setFile(File $file): void
    {
        $this->file = $file;
    }

    /**
     * set new filename after move
     * @param string $filename
     */
    public function setFileName(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * set result for file save result
     * @param string $result
     */
    public function setResult(string $result): void
    {
        $this->result = $result;
    }
}