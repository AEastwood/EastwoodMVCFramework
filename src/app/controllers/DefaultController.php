<?php

namespace MVC\App\Controllers;

use MVC\Classes\Email;
use MVC\Classes\Response;
use MVC\Classes\Controller;
use MVC\Classes\Storage\MimeTypes;
use MVC\Classes\Storage\Storage;

class DefaultController extends Controller
{
    /**
     *  default index callback function for default route
     */
    public static function index()
    {
        return Controller::view('index');
    }

    /**
     * upload
     */
    public static function upload()
    {
        return Controller::view('upload');
    }

    /**
     * process file upload
     */
    public static function uploadFile()
    {
        var_dump(Storage::publicUpload(MimeTypes::image()));
    }

    /**
     *  send email to me
     */
    public static function sendMessage()
    {
        if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message']) || empty($_POST['csrf'])) {
            return Response::json(['code' => 400, 'message' => 'Please complete all form fields and try again'], 200);
        }

        $email = new Email(
            $_POST['email'],
            $_ENV['APP_EMAIL'],
            $_POST['name'],
            'New message from ' . $_POST['name'] . '(' . $_POST['email'] . ')',
            $_POST['message']
        );

        $email->setTemplate('emails/message.txt');

        if($email->sendable() && $email->send()) {
            return Response::json(['code' => 200, 'message' => 'Message has been sent'], 200);
        }

        return Response::json(['code' => 500, 'message' => 'An unknown error occurred, your message was not sent'], 200);
    }

}
