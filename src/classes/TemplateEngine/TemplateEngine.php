<?php

namespace MVC\Classes\TemplateEngine;

use MVC\Classes\App;
use MVC\Classes\Controller;
use MVC\Classes\Storage\Storage;
use WyriHaximus\HtmlCompress\Factory;

class TemplateEngine
{
    /**
     * @var int|float
     */
    private int $cache_length;

    /**
     * @var bool
     */
    private bool $use_cache;

    /**
     * @var string
     */
    private string $view;

    /**
     * @var string
     */
    private string $view_cache;

    /**
     * @var string|array|string[]
     */
    private string $view_name;

    /**
     * @var string
     */
    private string $escapeRegex;

    /**
     * @var string
     */
    private string $noneEscapeRegex;

    /**
     *  constructor
     * @param $view
     */
    public function __construct($view)
    {
        $this->escapeRegex = '~\{{\s*(.+?)\s*\}}~is';
        $this->noneEscapeRegex = '~\{!!\s*(.+?)\s*\!!}~is';
        $this->view_name = str_replace('.', '/', $view);

        $this->cache_length = App::body()->env['RENDER_MAX'] * 60;
        $this->use_cache = App::body()->env['RENDER_CACHE'] === 'true';

        $this->view = '../resources/views/' . $this->view_name . '.view.php';
        $this->view_cache = '../storage/cache/' . $this->view_name . '.view.cache';
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
     * @return TemplateEngine
     */
    private function directives(): TemplateEngine
    {
        foreach (TemplateDirectives::directives() as $directive => $value) {
            $this->view = str_replace($directive, $value, $this->view);
        }

        return $this;
    }

    /**
     *  echo $var wrapped in htmlspecialchars
     */
    private function escaped(): TemplateEngine
    {
        $this->view = preg_replace($this->escapeRegex, '<?php echo htmlspecialchars($1, ENT_QUOTES) ?>', $this->view);

        return $this;
    }

    /**
     *  generate fresh version of the template
     *  create cache file if cache is enabled
     * @param array $variables
     * @return TemplateEngine|null
     */
    private function generate(array $variables = []): ?TemplateEngine
    {
        if (Storage::exists($this->view)) {
            $this->view = Storage::get($this->view);
            $this->view = $this->directives()->escaped()->nonEscaped()->asString();

            extract($this->parameters($variables), EXTR_SKIP);

            ob_start();
            eval('?>' . $this->view);
            $this->view = ob_get_clean();
            ob_flush();

            return $this;
        }

        $statusCode = 500;

        Controller::view(
            'errors.error',
            [
                'code' => $statusCode,
                'message' => 'The view file specified does not exist'
            ],
            $statusCode
        );
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
        if ($this->use_cache && $this->hasValidCacheFile()) {
            $this->loadCacheFile();

            return $this;
        }

        $this->generate($variables);

        if ($this->use_cache) {
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
        foreach ($_SESSION['EMVC.parameters'] as $k => $v) {
            $variables[$k] = str_replace('%20', ' ', $v);
        }

        return $variables;
    }

    /**
     * apply non cache safe directives to the template before rendering
     */
    private function nonCacheSafeDirectives(): void
    {
        foreach (TemplateDirectives::nonCacheSafe() as $directive => $value) {
            $this->view = str_replace($directive, $value, $this->view);
        }
    }

    /**
     *  echo $var without escaping HTML
     */
    private function nonEscaped(): TemplateEngine
    {
        $this->view = preg_replace($this->noneEscapeRegex, '<?php echo $1 ?>', $this->view);

        return $this;
    }

    /**
     *  render the view
     */
    public function render(int $statusCode)
    {
        $this->nonCacheSafeDirectives();
        $this->setHeaders();

        http_response_code($statusCode);
        echo (Factory::constructSmallest())->compress($this->view);
    }

    /**
     * @return void
     */
    private function setHeaders(): void
    {
        header('Cache-Control: max-age=31536000');
    }

}