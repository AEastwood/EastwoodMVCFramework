<?php

namespace MVC\Classes;

use MVC\Models\User;

class Auth {

    private static object $user;
    public int $id;
    public string $name;
    public string $email;
    public string $password_hash;
    public string $token;
    public bool $active;
    public int $created_at;
    public int $updated_at;

    /*
    *   constructor
    */
    public function __construct()
    {
        if(isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
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

    public static function user(): object
    {
        return self::$user;
    }

}