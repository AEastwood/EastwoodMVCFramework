<?php


namespace MVC\Classes;

use MVC\Classes\Storage\Storage;

class Email
{
    /**
     * Headers for the email
     * @var string
     */
    private string $headers;

    /**
     * Address to send the email to
     * @var string
     */
    private string $from;

    /**
     * Address the email is sent from
     * @var string
     */
    private string $sender;

    /**
     * Address to send the email to
     * @var string
     */
    private string $to;

    /**
     * Name of sender
     * @var string
     */
    private string $name;

    /**
     * Subject of the email
     * @var string
     */
    private string $subject;

    /**
     * The email message
     * @var string
     */
    private string $message;

    /**
     * Path to email template
     * @var string
     */
    private string $template;

    /**
     * Flag if email is properly formed and is sendable
     * @var bool
     */
    private bool $sendable;

    /**
     * Email constructor.
     * @param string $sender
     * @param string $to
     * @param string $name
     * @param string $subject
     * @param string $message
     */
    public function __construct(string $sender, string $to, string $name, string $subject, string $message)
    {
        $this->from         = $_ENV['EMAIL_FROM'];
        $this->sender       = htmlspecialchars($sender);
        $this->to           = htmlspecialchars($to);
        $this->name         = htmlspecialchars($name);
        $this->subject      = htmlspecialchars($subject);
        $this->message      = htmlspecialchars($message);

        $this->sendable = false;

        $this->setHeaders();
    }

    /**
     * send email
     * @return bool#
     */
    public function send(): bool
    {
        if(!@mail($this->to, $this->subject, $this->template, $this->headers)) {
            App::body()->logger->error('[Email] Unable to send email to ' . $this->to);
            return false;
        }

        return true;
    }

    /**
     * Check if the email is fully formed and sendable
     * @return bool
     */
    public function sendable(): bool
    {
        return $this->sendable;
    }

    /**
     * set email headers
     */
    private function setHeaders(): void
    {
        $this->headers = "From: " . $this->from . "\r\n";
        $this->headers .= "MIME-Version: 1.0\r\n";
        $this->headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $this->headers .= 'Reply-To: "' . $this->sender . '"<' . $this->sender . '>';
    }

    /**
     * set email template
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = Storage::get($template);

        if($this->template !== null) {
            $this->template = str_replace("{{ name }}", $this->name, $this->template);
            $this->template = str_replace("{{ email }}", $this->sender, $this->template);
            $this->template = str_replace("{{ message }}", $this->message, $this->template);

            $this->sendable = true;
        }
    }
}