<?php

namespace Core\Templates;

class TemplateFunctions {
    
    private static $cachePath;
    private static $cacheable;
    private static $cacheTime;

    public function __construct(){
        $this->cachePath = "/home/adameastwood/Cache";
        $this->cacheable = false;
        $this->cacheTime = 0;
    }

    public function CacheManager(string $datavalue, string $template): string{
        $data = explode(":", $datavalue);
        $cachePath = str_replace("/Templates", "/Cache", $template);
        $time = date("H:i:s");
        
        if(filter_var($data[0], FILTER_VALIDATE_BOOLEAN)){
            self::$cacheable = $data[0];
        }

        if($data[0]){
            if(filter_var($data[1], FILTER_VALIDATE_INT)){
                self::$cacheTime = $data[1];
            }
        }        
              
        if(self::$cacheable && self::$cacheTime > 0){
            if(!file_exists($cachePath)){
                file_put_contents($cachePath, $template);
            }

            return "<!-- CP: true -> Rendered: $time, File: $cachePath -->\n". self::RenderCacheTemplate($cachePath, $data[1]);
        }

        return "<!-- CP: false -> Rendered: $time -->";
    }

    private $templateCacheTime;
    private function RenderCacheTemplate(string $templateCacheFile, int $cacheTime) {
        $cacheTime *= 60;
        $templateCache = "/home/adameastwood/Cache/" . $templateCacheFile . ".tpl";
        $templateCacheTime = stat($templateCacheFile);

        if(file_exists($templateCacheFile) && ($templateCacheTime['mtime'] + $cacheTime)){
            die(file_get_contents($templateCacheFile));
        }
        else {
            return "fail";
        }
    }
}