<?php

namespace MVC\App\Controllers;

use Exception;
use MVC\Classes\Response;
use MVC\Classes\Controller;

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
     *  send email to me
     */
    public static function sendMessage()
    {
        if(!isset($_POST['name'], $_POST['email'], $_POST['message'])) {
            return Response::json(['code' => 400, 'message' => 'Please complete all form fields and try again'], 200);
        }

        $name    = htmlspecialchars($_POST['name']);
        $email   = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        $headers = "From: noreply@adameastwood.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $headers .= 'Reply-To: "' . $email . '"<' . $email . '>';

        $emailContents = file_get_contents('../storage/emails/message.txt');

        $emailContents = str_replace("{{ name }}", $name, $emailContents);
        $emailContents = str_replace("{{ email }}", $email, $emailContents);
        $emailContents = str_replace("{{ message }}", $message, $emailContents);

        try {
            mail($_ENV['APP_EMAIL'], "New message from $name ($email)", $emailContents, $headers);
            return Response::json(['code' => 200, 'message' => 'Message has been sent'], 200);
        }
        catch(Exception $e) {
            return Response::json(['code' => 500, 'message' => 'An unknown error occurred, your message was not sent'], 200);
        }
    }

}
