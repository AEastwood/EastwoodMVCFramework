<?php

namespace MVC\Classes\Storage\Downloads;

use MVC\Classes\Storage\File;

class FileDownload
{
    private File $file;
    private bool $resumable;

    /**
     * FileDownload constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->file = new File($filename);
        $this->resumable = false;
    }

    /**
     * initiate a file download
     */
    public function init(): void
    {

    }

    /**
     * track bandwidth of downloaded file
     * @return FileDownload
     */
    public function logBandwidthUsage(): FileDownload
    {

    }

    /**
     * rate limited the download
     * @return FileDownload
     */
    public function rateLimit(): FileDownload
    {

        return $this;
    }

    /**
     * make download resumable
     * @return $this
     */
    public function resumable(): FileDownload
    {
        $this->resumable = true;

        return $this;
    }

    /**
     * download a file
     */
    public function startDownload(): void
    {

    }

}