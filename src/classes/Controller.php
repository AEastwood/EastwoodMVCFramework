<?php

namespace MVC\Classes;

use Closure;

class Controller
{
    /*
     *  Create and compile view
     */
    public static function view(string $view, array $variables = []): Closure
    {
        $templateEngine = new TemplateEngine($view);
        return $templateEngine->init($variables)->render();
    }

}