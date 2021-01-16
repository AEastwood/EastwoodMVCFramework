<?php

namespace MVC\Classes;

use MVC\Classes\TemplateEngine\TemplateEngine;

class Controller
{
    /**
     *  Create and compile view
     * @param string $view
     * @param array $variables
     * @return void
     */
    public static function view(string $view, array $variables = [])
    {
        return (new TemplateEngine($view))->init($variables)->render();
    }

}