<?php

namespace MVC\Classes;

use MVC\App\Exceptions\ViewDoesntExistException;

class TemplateEngine
{    
    private string $view;
    private string $view_cache;
    private string $view_name;
    private string $escapeRegex;
    private string $noneEscapeRegex;

    /*
     *  constructor
     */
    public function __construct($view)
    {
        $this->escapeRegex     = '~\{{\s*(.+?)\s*\}}~is';
        $this->noneEscapeRegex = '~\{!!\s*(.+?)\s*\!!}~is';
        $this->view_name       = $view;
        $this->view            = '../resources/views/' . $view . '.view.php';
        $this->view_cache      = '../storage/cache/' . $view . '.view.php';
    }

    /**
     *  return view as string
     */
    public function asString(): string
    {
        return ($this->view);
    }

    /**
     *  echo $var wrapped in htmlspecialchars
     */
    private function escape(): object
    {
        $this->view = preg_replace($this->escapeRegex, '<?php echo htmlspecialchars($1, ENT_QUOTES) ?>', $this->view);
        
        return ($this);
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

    /**
     *  generate fresh version of the template
     *  create cache file if cache is enabled
     */
    private function generateNew(array $variables = []): object
    {
        if (file_exists($this->view)) {
            $this->view = file_get_contents($this->view);
            $this->view = $this->escape()->nonEscaped()->asString();
            
            ob_start();
            extract($variables, EXTR_SKIP);
            eval('?>'. $this->view);
            $this->view = ob_get_clean();
            ob_flush();

            file_put_contents($this->view_cache, $this->view);
            
            return ($this);
        }

        throw new ViewDoesntExistException($this->view);
    }

    /**
     *  check age of a file in the cache repository, if file is older than RENDER_CACHE then it will generate
     *  a new version
     */
    private function hasValidCacheFile(): bool {

        $maxCache = $_ENV['RENDER_MAX'] * 60;

        if(file_exists($this->view_cache)){
            
            if(time() - filemtime($this->view_cache) < $maxCache) {
                return (true);
            }
            else {
                return (false);
            }
        }

        return (false);
    }

    /*
     *  run template engine against view contents
     */
    public function init(array $variables = []): object
    {
        if($_ENV['RENDER_CACHE'] == true && $this->hasValidCacheFile()) {
            $this->loadCacheFile();

            return ($this);
        }

        $this->generateNew($variables);

        return ($this);
    }

    /**
     *  load file from cache
     */
    private function loadCacheFile(): void
    {
        $this->view = file_get_contents($this->view_cache);
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
        echo $this->view;
    }

}