<?php

namespace MVC\Classes;

use MVC\Classes\TemplateEngine\TemplateEngine;

class Controller
{
    /**
     *  Create and compile view
     * @param string $view
     * @param array $variables
     * @param int $statusCode
     * @return void
     */
    public static function view(string $view, array $variables = [], int $statusCode = 200)
    {
        (new TemplateEngine($view))->init($variables)->render($statusCode);
    }

}