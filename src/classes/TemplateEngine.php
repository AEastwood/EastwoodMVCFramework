<?php

namespace MVC\Classes;

use Closure;
use MVC\Classes\Cookie;

class TemplateEngine
{    
    private int $cache_length;
    private bool $use_cache;

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
        $this->view_name       = str_replace('.', '/', $view);

        $this->cache_length    = App::body()->env['RENDER_MAX'] * 60;
        $this->use_cache       = (App::body()->env['RENDER_CACHE'] === 'true') ? true : false;
        
        $this->view            = '../resources/views/' . $this->view_name . '.view.php';
        $this->view_cache      = '../storage/cache/' . $this->view_name . '.view.cache';
    }

    /**
     *  return view as string
     */
    public function asString(): string
    {
        return ($this->view);
    }

    /**
     *  set cache file owner to www-data
     */
    private function changeOwner(): object
    {
        chown($this->view_cache, 'www-data');

        return ($this);
    }

    /**
     *  set cache file permissions to 0644
     */
    private function changePermissions(): object
    {
        chmod($this->view_cache, 0600);

        return ($this);
    }

    /**
     *  create cached version of the view
     */
    private function createCache(): object
    {
        if($this->use_cache) {
            file_put_contents($this->view_cache, $this->view);
        }
        
        return ($this);
    }

    private function directives(): object
    {
        $directives = [
            '@csrf' => '<input type="hidden" id="CSRFToken" value="' . App::body()->csrf->csrf_token .'">',
        ];

        foreach($directives as $directive => $value) {
            $this->view = str_replace($directive, $value, $this->view);
        }

        return ($this);
    }

    /**
     *  echo $var wrapped in htmlspecialchars
     */
    private function escape(): object
    {
        $this->view = preg_replace($this->escapeRegex, '<?php echo htmlspecialchars($1, ENT_QUOTES) ?>', $this->view);
        
        return ($this);
    }

    private function extractParameters(array $variables): array
    {
        foreach($_SESSION['EMVC.parameters'] as $k => $v) {
            $variables[$k] = $v;
        }

        foreach($variables as $k => $v) {
            $variables[$k] = str_replace('%20', ' ', $v);
        }

        return ($variables);
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
    private function generateNew(array $variables = [])
    {
        if (file_exists($this->view)) {
            $this->view = file_get_contents($this->view);
            $this->view = $this->directives()->escape()->nonEscaped()->asString();
            
            $variables = $this->extractParameters($variables);
            extract($variables, EXTR_SKIP);

            ob_start();
            eval('?>'. $this->view);
            $this->view = ob_get_clean();
            ob_flush();

            return ($this);
        }
    }

    /**
     *  check age of a file in the cache repository, if file is older than RENDER_CACHE then it will generate
     *  a new version
     */
    private function hasValidCacheFile(): bool
    {
        if(file_exists($this->view_cache) && (filemtime($this->view_cache) > (time() - $$this->cache_length))) {
            return (true);
        }

        return (false);
    }

    /*
     *  run template engine against view contents
     */
    public function init(array $variables = []): object
    {
        if($this->use_cache && $this->hasValidCacheFile()) {
            $this->loadCacheFile();
                
            return ($this);
        }

        $this->generateNew($variables);

        if($this->use_cache) {
            $this->createCache()->changeOwner()->changePermissions();
        }
            
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
    public function render()
    {
        echo $this->view;
    }

}