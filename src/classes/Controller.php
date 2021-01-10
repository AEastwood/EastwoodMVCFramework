<?php

namespace MVC\Classes;

class Controller
{
    /**
     *  Create and compile view
     * @param string $view
     * @param array $variables
     * @return
     */
    public static function view(string $view, array $variables = [])
    {
        $templateEngine = new TemplateEngine($view);
        return $templateEngine->init($variables)->render();
    }

}