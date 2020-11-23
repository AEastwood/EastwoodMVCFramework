<?php

namespace MVC\Classes;

use MVC\Classes\App;

class Controller
{
    /*
     *  Create and compile view
     */
    public function view(string $view, array $variables = []): void
    {
        $templateEngine = new TemplateEngine($view);
        $templateEngine->init($variables)->render();
    }

    /*  
    *   Create and compile error view
    */
    public function error(string $view, array $variables = []): void
    {
        $templateEngine = new TemplateEngine('errors/' . $view);
         $templateEngine->init($variables)->render();
    }

}