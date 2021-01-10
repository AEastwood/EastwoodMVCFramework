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

    /**
     *  returns array if session[$key] exists
     * @param $key
     * @return array
     */
    private function getSession(string $key): array
    {
        if ($this->hasSession($key)) {
            return $_SESSION[$key];
        }
    }

    /**
     *  returns true if user has valid session
     * @param string $key
     * @return bool
     */
    private function hasSession(string $key = ''): bool {
        if (isset($_SESSION[$key])) {
            return true;
        }

        return false;
    }

    /**
     *  validates user session
     */
    private function validateSession(): void
    {
        $session = null;
        $key = 'EMVC.validity';

        if($this->hasSession($key)) {
            $session = $this->getSession($key);
        }

        if($session !== null) {
            $endOfSession = $session['EMVC.validity'];
        }
    }

    /**
     *  set user from session
     */
    private function setUser()
    {
        $user = $this->getSession('EMVC.user');

        $this->id            = $user->id;
        $this->name          = $user->name;
        $this->email         = $user->email;
        $this->password_hash = $user->password_hash;
        $this->token         = $user->token;
        $this->active        = $user->active;
        $this->created_at    = $user->created_at;
        $this->updated_at    = $user->updated_at;
    }

}