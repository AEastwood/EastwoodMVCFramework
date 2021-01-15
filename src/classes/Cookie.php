<?php

namespace MVC\Classes;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;

class Cookie {

    /**
     * delete cookie
     * @param string $name
     */
    public static function delete(string $name): void
    {
        setCookie($name, '', -3600);
    }

    /**
     *  gets cookie
     * @param $cookieName
     * @return string|null
     */
    public static function getAndDecrypt($cookieName): ?string
    {
        if(!isset($_COOKIE[$cookieName])) {
            App::body()->logger->info('[Cookie] Attempting to get cookie "' . $cookieName . '" but it does not exist.');
            return;
        }

        try {
            return Crypto::decrypt($_COOKIE[$cookieName], App::body()->key);
        }
        catch(EnvironmentIsBrokenException $e) {
            App::body()->logger->error('[Cookie] Environment is broken, Error: ' . $e->getMessage());
            return null;
        }
        catch (WrongKeyOrModifiedCiphertextException $e) {
            App::body()->logger->error('[Cookie] Decryption of cookie failed, potentially modified by user, Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     *   sets cookie
     * @param string $cookieName
     * @param string $value
     * @param int $expiration
     * @param string $accessor
     */
    public static function setEncrypted(string $cookieName, string $value, int $expiration, string $accessor = '/'): void
    {
        try {
            setcookie($cookieName, Crypto::encrypt($value, App::body()->key), $expiration, $accessor);
        }
        catch(EnvironmentIsBrokenException $e) {
            App::body()->logger->error('[Cookie] Unable to set cookie, Error: ' . $e->getMessage());
        }
    }

}