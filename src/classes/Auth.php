<?php

namespace MVC\Classes;

use MVC\Models\User;

class Auth
{

    public int $id;
    public string $name;
    public string $email;
    public string $password_hash;
    public string $token;
    public bool $active;
    public int $created_at;
    public int $updated_at;

    private int $maxAge;

    /*
    *   constructor
    */
    public function __construct($maxAge)
    {
        $this->id = -1;
        $this->name = '';
        $this->email = '';
        $this->password_hash = '';
        $this->token = '';
        $this->active = false;
        $this->created_at = -1;
        $this->updated_at = -1;

        $this->maxAge = $maxAge;

        if ($this->hasSession()) {
            $this->validateSession();
            $this->setUser();
        }
    }

    private function getSession($key): array
    {
        if($this->hasSession($key)) {
            return $_SESSION[$key];
        }

        throw new NonExistentSessionException($key);
    }

    /*
     *  returns true if user has valid session
     *  @return bool
     */
    private function hasSession($key = ''): bool {
        if (isset($_SESSION[$key])) {
            return true;
        }

        return false;
    }

    /*
     *  returns static instance of the user
     */
    public static function user(): object
    {
        return self::$user;
    }

    /*
     *  validates user session
     */
    private function validateSession(): void
    {
        $key = 'EMVC.validity';

        if($this->hasSession($key)) {
            $session = $this->getSession($key);
        }
    }

    /*
     *  set user from session
     */
    private function setUser()
    {
        $user = $this->getSession('EMVC.user');

        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password_hash = $user->password_hash;
        $this->token = $user->token;
        $this->active = $user->active;
        $this->created_at = $user->created_at;
        $this->updated_at = $user->updated_at;
    }

}