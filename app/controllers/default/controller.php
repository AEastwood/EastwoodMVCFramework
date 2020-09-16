<?php

namespace App;

use App\Controller\ViewController;
use Core\Request;
use Core\Templates\TemplateEngine;

class Controller {

    /**
     *  return 404 view
     */
    public function not_found() {
        return self::view('404');
    }

    /**
     *  @return HTML processed by blade engine
     */
    public function GenerateView($view, $params = null) {
        $pageParams = null;

        ob_start();
        include($view);

        if(!is_null($params)){
            foreach($params as $k => $v) {
                $_SESSION[$k] = $v;
            }
        }

        $blade = ob_get_contents();
        $templateVariables = preg_match_all('/\^[A-Z]{1,3}\,(.)*/', $blade, $templateVars);

        foreach($templateVars[0] as $Var) {
            $blade = str_replace($Var, TemplateEngine::ProcessData($Var, $view), $blade);
        }

        ob_end_clean();
        return $blade;
    }

    /**
     *  Throws error for invalid function
     */
    public function invalid_func() {
        return self::view('errors/invalid_function');
    }

    /**
     *  Throws error for invalid method
     */
    public function invalid_method() { 
        Request::httpResponseCode(405);
        return self::view('errors/405', ['expected' => 'POST']);
    }

    /**
     *  returns view
     */
    public function view($view = null, $params = null) {
        $view = self::parseViewPath($view);
        $view = __DIR__."/../../../views/$view";

        $view = (file_exists($view)) 
            ? $view 
            : __DIR__."/../../../views/errors/404.blade.tpl";

        return self::GenerateView($view, $params);
    }

    /**
     *  parses path of view
     */
    private function parseViewPath($view) {
        return $view . '.blade.tpl';
    }

}