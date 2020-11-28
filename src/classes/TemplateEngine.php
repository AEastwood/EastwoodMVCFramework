<?php

namespace MVC\Classes;

use MVC\App\Exceptions\ViewDoesntExistException;

class TemplateEngine
{
    /*
     *  pattern to match
     */
    private string $pattern;

    /*
     *  view contents passed as string
     */
    private string $view;

    /*
     *  DIR of views
     */
    private string $viewPath;

    /*
     *  constructor
     */
    public function __construct($view)
    {
        $this->pattern = '/[{{]{2}(.*?)[}}]{2}/';
        $this->view = $view;
        $this->viewPath = '../resources/views/';
    }

    /*
     *  strip {{}} from dynamic indexes in the active view
     */
    private function format(string $match): string
    {
        $chars = ' ';
        $match = preg_replace('/[{}]/', '', $match);
        $match = ltrim($match, $chars);
        $match = rtrim($match, $chars);

        return ($match);
    }

    /*
     *  run template engine against view contents
     */
    public function init(array $variables = []): object
    {
        $view = $this->viewPath . $this->view . '.view.php';

        if (file_exists($view)) {

            $view = file_get_contents($view);
            $view = $this->process($view);

            ob_start() && extract($variables, EXTR_SKIP);
            eval('?>'. $view);
            $view = ob_get_clean();
            ob_flush();
            
            $this->view = $view;

            return ($this);
        }

        throw new ViewDoesntExistException('View does not exist');
    }

    /*
     *  find and replace all dynamic indexes from the view and replace with
     *  appropriate function call(s)
     */
    private function process($view): string
    {
        if(!preg_match_all($this->pattern, $view, $matches)) {
            return $view;
        }

        if (count($matches) > 0) {
            foreach ($matches[0] as $match) {
                $function = $this->format($match);
                $view = str_replace($match, eval("return $function;"), $view);
            }
        }

        return ($view);
    }

    /*
     *  render view after processing
     */
    public function render(): void
    {
        echo $this->view;
    }

}