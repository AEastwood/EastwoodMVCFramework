<?php

namespace MVC\Classes;

use MVC\App\Exceptions\ViewDoesntExistException;

class TemplateEngine
{    
    private string $view;
    private string $view_name;
    private string $escapeRegex;
    private string $noneEscapeRegex;

    /*
     *  constructor
     */
    public function __construct($view)
    {
        $this->escapeRegex    = '~\{{\s*(.+?)\s*\}}~is';
        $this->noneEscapeRegex = '~\{!!\s*(.+?)\s*\!!}~is';
        
        $this->view_name = $view;
        $this->view = '../resources/views/' . $view . '.view.php';
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
        if (file_exists($this->view)) {
            $this->view = file_get_contents($this->view);
            $this->view = $this->escape()->nonEscaped()->asString();
            
            ob_start();
            extract($variables, EXTR_SKIP);
            eval('?>'. $this->view);
            $this->view = ob_get_clean();
            ob_flush();
            
            return ($this);
        }

        throw new ViewDoesntExistException($this->view);
    }

    /**
     *  return view as string
     */
    public function asString(): string
    {
        return $this->view;
    }

    /**
     *  echo $var wrapped in htmlspecialchars
     */
    private function escape(): object
    {
        $this->view = preg_replace($this->escapeRegex, '<?php echo htmlspecialchars($1, ENT_QUOTES) ?>', $this->view);
        
        return ($this);
    }
    
    /**
     *  echo $var without wrapping
     */
    private function nonEscaped(): object 
    {
        $this->view = preg_replace($this->noneEscapeRegex, '<?php echo $1 ?>', $this->view);
        
        return ($this);
    }
    
    /**
     *  render the view
     */
    public function render(): void
    {
        $file = '../storage/cache/' . $this->view_name . '.view.php';
        file_put_contents($file, $this->view);
        
        die(include ($file));
    }

}