<?php

namespace MVC\Classes\Storage\Uploads;

use MVC\Classes\Storage\File;
use MVC\Classes\Storage\FileSaveResult;
use MVC\Classes\Storage\FileStorageResults;
use MVC\Classes\Storage\Storage;
use PragmaRX\Random\Random;

class FileUpload
{
    private File $file;
    public string $filepath;
    public array $allowedMimeTypes;
    private int $maxUploadSize;

    /**
     * FileUpload constructor.
     * @param array $allowedFileExtensions
     */
    public function __construct(array $allowedFileExtensions)
    {
        $this->filepath = $_ENV['UPLOADS_DIR'];
        $this->allowedMimeTypes = $allowedFileExtensions ?? MimeTypes::all();
        $this->maxUploadSize = (int)ini_get('upload_max_filesize') * 1024 * 1024;
    }

    /**
     * clean uploads that failed
     */
    public function __destruct()
    {
        if(is_file($this->file->path)) {
            unlink($this->file->path);
        }
    }

    /**
     * save file
     * @param File $file
     * @return array
     * TODO: Need to implement more validation and more secure checks
     */
    public function save(File $file): array
    {
        $this->file = $file;

        $fileSaveResult = new FileSaveResult($this->file->path);

        if($file->size > $this->maxUploadSize) {
            $fileSaveResult->setResult(FileStorageResults::UPLOAD_REJECTED_FILE_SIZE);
            return $fileSaveResult->asArray();
        }

        if(!file_exists($file->path) || empty($_FILES['file']['name'])) {
            $fileSaveResult->setResult(FileStorageResults::UPLOAD_EMPTY_FILE);
            return $fileSaveResult->asArray();
        }

        if(!in_array($file->mimetype, $this->allowedMimeTypes)) {
            $fileSaveResult->setResult(FileStorageResults::UPLOAD_REJECTED_MIME_TYPE);
            return $fileSaveResult->asArray();
        }

        rename($file->path, $file->getNewPath());

        $fileSaveResult->setFile($file);
        $fileSaveResult->setFileName($file->getNewPath());
        $fileSaveResult->setResult(FileStorageResults::UPLOAD_SUCCESS);

        Storage::changeOwner($file->getNewPath(), 'www-data');
        Storage::changePermissions($file->getNewPath(), 0644);

        return $fileSaveResult->asArray();
    }

}