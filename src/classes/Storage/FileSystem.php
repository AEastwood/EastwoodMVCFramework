<?php

namespace MVC\Classes\Storage;

use PragmaRX\Random\Random;

class FileSystem
{
    private File $file;
    public string $filepath;
    public array $allowedFileExtensions;

    /**
     * FileSystem constructor.
     * @param array $allowedFileExtensions
     */
    public function __construct(array $allowedFileExtensions)
    {
        $this->filepath = $_ENV['UPLOADS_DIR'];
        $this->allowedFileExtensions = $allowedFileExtensions;
    }

    /**
     * clean uploads that failed
     */
    public function __destruct()
    {
        if(file_exists($this->file->path)) {
            unlink($this->file->path);
        }
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
     * save file
     * @param File $file
     * @return array
     * TODO: Need to implement more validation and more secure checks
     * TODO: Need to implement way of retrieving files and making them publicly accessible in either views or download
     */
    public function save(File $file): array
    {
        if(empty($this->allowedFileExtensions)) $mimetypes = MimeTypes::all();

        $result = new FileSaveResult($file->path);


        $this->file = $file;

        if(!in_array($file->mimetype, $this->allowedFileExtensions)) {
            $result->setResult(FileSaveResult::UPLOAD_REJECTED_MIME_TYPE);
            return $result->asArray();
        }

        rename($file->path, $file->getNewPath());

        $result->setFile($file);
        $result->setFileName($file->getNewPath());
        $result->setResult(FileSaveResult::UPLOAD_SUCCESS);

        Storage::changeOwner($file->getNewPath(), 'www-data');
        Storage::changePermissions($file->getNewPath(), 0644);

        return $result->asArray();
    }

}