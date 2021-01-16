<?php

namespace MVC\Classes\TemplateEngine;

use MVC\Classes\App;
use MVC\Classes\Storage;

class TemplateEngine
{    
    private int $cache_length;
    private bool $use_cache;

    private string $view;
    private string $view_cache;
    private string $view_name;

    private string $escapeRegex;
    private string $noneEscapeRegex;

    /**
     *  constructor
     * @param $view
     */
    public function __construct($view)
    {
        $this->escapeRegex     = '~\{{\s*(.+?)\s*\}}~is';
        $this->noneEscapeRegex = '~\{!!\s*(.+?)\s*\!!}~is';
        $this->view_name       = str_replace('.', '/', $view);

        $this->cache_length    = App::body()->env['RENDER_MAX'] * 60;
        $this->use_cache       = App::body()->env['RENDER_CACHE'] === 'true';
        
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
     * replace all directives with their respective values
     * @return void
     */
    private function directives(): TemplateEngine
    {
        foreach(TemplateDirectives::directives() as $directive => $value) {
            $this->view = str_replace($directive, $value, $this->view);
        }

        return $this;
    }

    /**
     *  echo $var wrapped in htmlspecialchars
     */
    private function escape(): TemplateEngine
    {
        $this->view = preg_replace($this->escapeRegex, '<?php echo htmlspecialchars($1, ENT_QUOTES) ?>', $this->view);
        
        return ($this);
    }

    /**
     *  generate fresh version of the template
     *  create cache file if cache is enabled
     * @param array $variables
     * @return TemplateEngine
     */
    private function generate(array $variables = []): TemplateEngine
    {
        if (Storage::exists($this->view)) {
            $this->view = Storage::get($this->view);
            $this->view = $this->directives()->escape()->nonEscaped()->asString();

            extract($this->parameters($variables), EXTR_SKIP);

            ob_start();
            eval('?>'. $this->view);
            $this->view = ob_get_clean();
            ob_flush();

            return $this;
        }
    }

    /**
     *  check age of a file in the cache repository, if file is older than RENDER_CACHE then it will generate
     *  a new version
     */
    private function hasValidCacheFile(): bool
    {
        return Storage::existsYoungerThan($this->view_cache, $this->cache_length);
    }

    /**
     *  run template engine against view contents
     * @param array $variables
     * @return TemplateEngine
     */
    public function init(array $variables = []): TemplateEngine
    {
        if($this->use_cache && $this->hasValidCacheFile()) {
            $this->loadCacheFile();
                
            return $this;
        }

        $this->generate($variables);

        if($this->use_cache) {
            Storage::put($this->view_cache, $this->view);
            Storage::changeOwner($this->view_cache);
            Storage::changePermissions($this->view_cache, 0600);
        }
            
        return $this;
    }

    /**
     *  load file from cache
     */
    private function loadCacheFile(): void
    {
        $this->view = Storage::get($this->view_cache);
    }

    /**
     * extract all route parameters so they can be used inside the template code
     * @param array $variables
     * @return array
     */
    private function parameters(array $variables): array
    {
        foreach($_SESSION['EMVC.parameters'] as $k => $v) {
            $variables[$k] = $v;
        }

        foreach($variables as $k => $v) {
            $variables[$k] = str_replace('%20', ' ', $v);
        }

        return $variables;
    }
    
    /**
     *  echo $var without wrapping
     */
    private function nonEscaped(): TemplateEngine
    {
        $this->view = preg_replace($this->noneEscapeRegex, '<?php echo $1 ?>', $this->view);
        
        return $this;
    }
    
    /**
     *  render the view
     */
    public function render()
    {
        $this->security();
        echo $this->view;
    }

    /**
     * apply security to the template before rendering
     */
    private function security(): void
    {
        foreach(TemplateDirectives::nonCacheSafe() as $directive => $value) {
            $this->view = str_replace($directive, $value, $this->view);
        }
    }

}