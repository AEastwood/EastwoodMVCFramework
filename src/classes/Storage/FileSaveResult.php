<?php


namespace MVC\Classes\Storage;


class FileSaveResult
{
    public File $file;
    public string $filename;
    public int $filesize;
    public string $result;

    const UPLOAD_EMPTY_FILE = 'no file provided';
    const UPLOAD_EMPTY_RESPONSE = 'no result was set';
    const UPLOAD_FILE_DOES_NOT_EXIST = 'the uploaded file does not exist';
    const UPLOAD_REJECTED_MIME_TYPE = 'upload rejected due to incorrect mime type';
    const UPLOAD_FAILED = 'upload failed';
    const UPLOAD_SUCCESS = 'upload success';

    /**
     * FileSaveResult constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->filesize = filesize($this->filename);
        $this->result = self::UPLOAD_EMPTY_RESPONSE;
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