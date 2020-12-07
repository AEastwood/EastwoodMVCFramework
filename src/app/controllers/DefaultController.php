<?php

namespace MVC\App\Controllers;

use Exception;
use MVC\Classes\App;
use MVC\Classes\Response;
use MVC\Classes\Controller;

class DefaultController extends Controller
{
    /*
     *  default index callback function for default route
     */
    public static function index()
    {
        return Controller::view('index',
            [
                'name' => 'adam',
                'age' => '28',
            ]
        );
    }

    public static function debug()
    {
        return Response::json([
            'data' => App::body()
        ], 200);
    }

    /**
     *  send email to me
     */
    public static function sendMessage()
    {
        $posts = ['name', 'email', 'message'];

        foreach($posts as $post) {
            if(empty($_POST[$post])) {
                return Response::json(['code' => 400, 'message' => 'Please complete all form fields and try again'], 200);
            }
        }        

        $name    = htmlspecialchars($_POST['name']);
        $email   = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        $Headers = "From: noreply@adameastwood.com\r\n";
        $Headers .= "MIME-Version: 1.0\r\n";
        $Headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $Headers .= 'Reply-To: "'.$email.'"<'.$email.'>';

        $emailContents = file_get_contents('../storage/emails/message.txt');

        $emailContents = str_replace("{{ name }}", $name, $emailContents);
        $emailContents = str_replace("{{ email }}", $email, $emailContents);
        $emailContents = str_replace("{{ message }}", $message, $emailContents);

        try {
            mail($_ENV['APP_EMAIL'], "New message from $name ($email)", $emailContents, $Headers);
            return Response::json(['code' => 200, 'message' => 'Message has been sent'], 200);
        }
        catch(Exception $e) {
            return Response::json(['code' => 500, 'message' => 'An unknown error occurred, your message was not sent'], 200);
        }
    }

}
