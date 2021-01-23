<?php


namespace MVC\Classes\Storage;


class FileStorageResults
{
    const DOWNLOAD_MAX_BANDWIDTH_PASSED = 'unable to download as the file has surpassed it\'s bandwidth quota';
    const DOWNLOAD_NOT_FOUND = 'the file requested does not exist';
    const DOWNLOAD_ONETIME_FAILED = 'one time download failed';
    const DOWNLOAD_ONETIME_SUCCESS = 'one time download successful, file deleted';
    CONST DOWNLOAD_SECRET_REJECTED = 'download failed, the secret provided was rejected';

    const UPLOAD_EMPTY_FILE = 'No file was provided or max upload exceeded';
    const UPLOAD_EMPTY_RESPONSE = 'no result was set';
    const UPLOAD_FAILED = 'upload failed';
    const UPLOAD_FILE_DOES_NOT_EXIST = 'the uploaded file does not exist';
    const UPLOAD_REJECTED_FILE_SIZE = 'the file provided was rejected as it exceeds the maximum upload size';
    const UPLOAD_REJECTED_MIME_TYPE = 'upload rejected due to incorrect mime type';
    const UPLOAD_SUCCESS = 'upload success';

}