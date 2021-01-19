<?php

use MVC\Classes\App;

/**
 * return country as string
 * @return string
 */
function country(): string
{
    $keys = [
        'CF-IPCountry',
    ];

    foreach($keys as $key) {
        if (!empty($_SERVER[$key])) {
            return $_SERVER[$key];
        }
    }

    return App::body()->session->client->geoplugin_countryCode ?? '';
}

/**
 * return continent as string
 * @return string
 */
function continent(): string
{

}