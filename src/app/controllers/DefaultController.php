<?php

namespace MVC\App\Controllers;

use MVC\Classes\App;
use MVC\Classes\Controller;
use MVC\Classes\Response;

class DefaultController extends Controller
{
    /*
     *  default index callback function for default route
     */
    public static function index()
    {
        Controller::view(
            'index',
            [
                'name' => 'adam',
                'age' => '28',
            ]
        );
    }

    /**
     *  echo debug information
     */
    public static function debug()
    {
        App::dd(App::body());
    }

    /**
     *  send email to me
     */
    public static function sendMessage()
    {
        $posts = ['name', 'email', 'message'];

        foreach($posts as $post) {
            if(empty($_POST[$post])) {
                Response::json(['code' => 400, 'message' => 'Please complete all form fields and try again'], 200);
            }
        }        

        $name    = htmlspecialchars($_POST['name']);
        $email   = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        $Headers = "From: noreply@adameastwood.com\r\n";
        $Headers .= "MIME-Version: 1.0\r\n";
        $Headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $Headers .= 'Reply-To: "'.$email.'"<'.$email.'>';

        try{
            $emailContents = file_get_contents('../storage/emails/message.txt');

            $emailContents = str_replace("{{ name }}", $name, $emailContents);
            $emailContents = str_replace("{{ email }}", $email, $emailContents);
            $emailContents = str_replace("{{ message }}", $message, $emailContents);

            mail($_ENV['APP_EMAIL'], "New message from $name ($email)", $emailContents, $Headers);
            Response::json(['code' => 200, 'message' => 'Message has been sent'], 200);
        }
        catch(Exception $Error) {
            Response::json(['code' => 500, 'message' => 'Unknown error occurred. Your message has not been sent'], 200);
        }
    }

}
