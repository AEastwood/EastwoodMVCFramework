<?php

namespace MVC\Classes\TemplateEngine;

use MVC\Classes\App;

class TemplateDirectives
{
    /**
     * default directive closer for the template engine
     * @var string
     */
    private static string $closer = '<?php endif; ?>';

    /**
     * build directive
     * @param string $directive
     * @return string
     */
    private static function comparator(string $directive): string
    {
        return '<?php if(' . $directive . '): ?>';
    }

    /**
     * return all available directives
     * TODO: Ascertain directives that are considered cache safe
     * @return array
     */
    public static function directives(): array
    {
       return [
           '@debug'     => self::comparator('$_ENV["RELEASE_MODE"] === "debug"'),
           '@enddebug'  => self::$closer,
           '@prod'      => self::comparator('$_ENV["RELEASE_MODE"] === "prod"'),
           '@endprod'   => self::$closer,
       ];
    }

    /**
     * set directives after the view is cached so they aren't affecting other visitors
     * @csrf is last as it causes a weird bug if alphabetical
     * TODO: Fix gdpr directives
     * @return array
     */
    public static function nonCacheSafe(): array
    {
        return [
            '@auth'      => self::comparator('MVC\Classes\App::user()->authed'),
            '@endauth'   => self::$closer,
            '@csrf'     => '<input type="hidden" id="CSRFToken" value="' . App::body()->csrf->token .'">',
//          '@gdpr'     => self::comparator('MVC\Classes\App::body()->request->client->isEuropean()'),
//          '@endgdpr'  => self::$closer,
            '@guest'     => self::comparator('!MVC\Classes\App::user()->authed'),
            '@endguest'  => self::$closer,
        ];
    }

}