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
 * return absolute path for path
 * @param string $path
 * @return string
 */
function redirect(string $path): string
{
    return $_ENV['BASE_PROTOCOL'] . '://' . $_ENV['BASE_URL'] . $path;
}