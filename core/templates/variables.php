<?php

namespace Core\Templates;

class TemplateVariables {
    
    public function CookieVariable($datatype, $datavalue): string{
        switch($datatype){
            case "CV":              
                return isset($_COOKIE[$datavalue]) 
                ? htmlspecialchars($_COOKIE[$datavalue])
                : "COOKIE_NULL";
        }
    }

    public function DateTime($dateSequence): string{
        return date($dateSequence);
    }

    public function RequestVariable($datatype, $datavalue): string{
        switch($datatype) {
            
            case "GD":
                return (isset($_GET[$datavalue])) 
                ? htmlspecialchars($_GET[$datavalue]) 
                : "<font color='red'>GET_NULL</font>";

            case "PD":
                return (isset($_POST[$datavalue])) 
                ? htmlspecialchars($_POST[$datavalue]) 
                : "<font color='red'>POST_NULL</font>";
        }
    }

    public function ServerVariable($datatype, $datavalue): string{
        switch($datatype) {
            
            case "SV":
                return $_SERVER[$datavalue];
        }
    } 
    
    public function SessionVariable($datatype, $datavalue): string{
        switch($datatype){
            case "SE":
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                return isset($_SESSION[$datavalue]) 
                ? htmlspecialchars($_SESSION[$datavalue])
                : "SESSION_NULL";
        }
    }
 
    public function TemplateBuilder($datatype, $datavalue): string {
        $path = "";

        switch($datatype){
            
            case "B64":
                return self::TPLBuilder($datavalue);
            
            case "C":
                return "<!-- $datavalue -->";
            
            case "CSS":
                $CSSProperties = explode(',', $datavalue);
                $AntiCache = null;
                
                if(!file_exists(getcwd() . "/public/$CSSProperties[0]")){
                    return "<!-- CSS File \"$CSSProperties[0]\" does not exist. -->";
                }

                if(isset($CSSProperties[1]) && $CSSProperties[1] === "t"){
                    $AntiCache = "?" . random_int(0, getrandmax());
                }

                return isset($CSSProperties[0])
                ? "<link rel=\"stylesheet\" type=\"text/css\" href=\"$path/public/$CSSProperties[0]$AntiCache\">"
                : "INVALID_CSS";

            case "ECS":
                return isset($datavalue)
                ? "<link rel=\"stylesheet\" type=\"text/css\" href=\"$datavalue\" >"
                : "INVALID_ECS";
            
            case "JS":
                $JSProperties = explode(',', $datavalue);
                $AntiCache = null;

                if(isset($JSProperties[1]) && $JSProperties[1] === "t"){
                    $AntiCache = "?" . random_int(0, getrandmax());
                }

                if(!file_exists(getcwd() . "/public/$JSProperties[0]")){
                    return "<!-- CSS File \"$JSProperties[0]\" does not exist. -->";
                }

                return isset($JSProperties[0])
                ? "<script src=\"$path/public/$JSProperties[0]?$AntiCache\"></script>"
                : "INVALID_JS"; 

            case "EJS":
                return isset($datavalue)
                ? "<script src=\"$datavalue\"></script>"
                : "INVALID_EJS";
            
            case "URL":
                $protection = '';
                $link = explode(',', $datavalue);
                $URL = "";

                if(isset($link[3]) && $link[3] === "t"){
                    $protection = "RedirectionPrevention=\"true\"";
                    $URL = "https://adameastwood.com/redirect?l=$link[0]";
                } else {
                    $URL = $link[0];
                }
                
                return isset($link[0])
                ? "<a href=\"$URL\" $protection target=\"_$link[1]\">$link[2]</a>"
                : "<b>Invalid Link</a>";
        }
    }

    // Work In Progress
    public function TPLBuilder($datatype, $datavalue): string {
        $base64type = explode(",", $datavalue);

        switch($base64type[1]){

            case "en":
                return base64_encode($base64type[0]);

            case "de":
                return base64_decode($base64type[0]);
        }
    } 
}