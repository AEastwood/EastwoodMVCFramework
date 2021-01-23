<?php

/**
 * return absolute path
 * @param string $path
 * @return string
 */
function asset(string $path): string
{
    return $_ENV['BASE_PROTOCOL'] . '://' . $_ENV['BASE_URL'] . '/' . $path;
}


/**
 * return absolute file path with anti-caching measures
 * @param string $path
 * @return string
 */
function assetFresh(string $path): string
{
    return $_ENV['BASE_PROTOCOL'] . '://' . $_ENV['BASE_URL'] . '/' . $path . '?' . rand(0, getrandmax());
}

/**
 *   Die and Debug
 *   @param  mixed  $data
 */
function dd(mixed $data)
{
    header('Content-Type: application/json');

    $action = function() use ($data) {
        echo print_r($data, true);
        exit;
    };

    return $action();
}

/**
 *  return client IP address
 */
function ipAddress(): string
{
    $keys = [
        'CF-Connecting-IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR',
        'HTTP_CLIENT_IP',
    ];

    foreach($keys as $key) {
        if (!empty($_SERVER[$key])) {
            return $_SERVER[$key];
        }
    }
}

/**
 * return the value of a route parameter using the parameter id set in the route(s) file,
 * duplicate params will return the first matching result
 * @param string $parameter
 * @return string
 */
function routeParam(string $parameter): string
{
    foreach($_SESSION['EMVC.parameters'] as $k => $v) {
        if($k === $parameter) {
            return $v;
        }
    }
}

/**
 * return absolute path for redirect
 * @param string $path
 * @return string
 */
function redirect(string $path): string
{
    return $_ENV['BASE_PROTOCOL'] . '://' . $_ENV['BASE_URL'] . $path;
}