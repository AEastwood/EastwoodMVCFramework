<?php

namespace Core\Templates;

use Core\Templates\TemplateFunctions;
use Core\Templates\TemplateVariables;

class TemplateEngine {
    
    private $template;
    private static $templateData;

    public function __construct($template) {
        $this->template = $template;
        $this->templateData = "";
    }

    public function Debug(){
        echo $this;
    }
    
    private function GetDataParams($variable) {
        $templateData = new \stdClass();
        $templateData->full = $variable;

        # data type
        if(preg_match("/^(.*?){1,3},/", $variable, $varMatch) == 1){
            $templateData->dataType = $varMatch[0];
            $templateData->dataType = ltrim($templateData->dataType, "^");
            $templateData->dataType = substr($templateData->dataType, 0, -1);
        }

        # data value
        if(preg_match("/,(.*)/", $variable, $varMatch) == 1){
            $templateData->dataValue = $varMatch[0];
            $templateData->dataValue = ltrim($templateData->dataValue, ",");
            $templateData->dataValue = substr($templateData->dataValue, 0, -1);
        }

        return $templateData;
    }
 
    public function ProcessData($data, $template): string {
        $templateData = self::GetDataParams($data);

        switch($templateData->dataType){
            
            case "B64": # Base64Encode
                return TemplateVariables::TemplateBuilder(
                    $templateData->dataType, 
                    $templateData->dataValue
                );
            
            case "C": # HTML Comment
                return TemplateVariables::TemplateBuilder(
                    $templateData->dataType, 
                    $templateData->dataValue
                );
            
            case "CSS": # CSS Injection
                return TemplateVariables::TemplateBuilder(
                    $templateData->dataType, 
                    $templateData->dataValue
                ); 


            case "CV": # Cookie Value
                return TemplateVariables::CookieVariable(
                    $templateData->dataType, 
                    $templateData->dataValue
                ); 

            case "DT": # Date Time
                return TemplateVariables::DateTime(
                    $templateData->dataValue
                );
            
            case "ECS": # External CSS Injection
                return TemplateVariables::TemplateBuilder(
                    $templateData->dataType, 
                    $templateData->dataValue
                );

            case "EJS": # External JS Injection
                return TemplateVariables::TemplateBuilder(
                    $templateData->dataType, 
                    $templateData->dataValue
                );
                
            case "GD": # Get Data
                return TemplateVariables::RequestVariable(
                    $templateData->dataType, 
                    $templateData->dataValue
                ); 
            
            case "HAS":
                return TemplateVariables::TemplateBuilder(
                    $templateData->dataType, 
                    $templateData->dataValue
                );

            case "JS": # JS Injection
                return TemplateVariables::TemplateBuilder(
                    $templateData->dataType, 
                    $templateData->dataValue
                );

            case "ND": # Normal Data : TODO - SQL Values
                return $templateData->dataValue;
            
            case "PD": # Post Data
                return TemplateVariables::RequestVariable(
                    $templateData->dataType, 
                    $templateData->dataValue
                );

            case "SE": # Session Data
                return TemplateVariables::SessionVariable(
                    $templateData->dataType, 
                    $templateData->dataValue
                );

            case "SV": # Server Variable
                return TemplateVariables::ServerVariable(
                    $templateData->dataType, 
                    $templateData->dataValue
                );

            case "TI": # Title Variable
                return "<title>$templateData->dataValue</title>";
            
            case "TPL":
                return TemplateVariables::TPLBuilder(
                    $templateData->dataType, 
                    $templateData->dataValue
                ); 

            case "URL": # URL
                return TemplateVariables::TemplateBuilder(
                    $templateData->dataType, 
                    $templateData->dataValue
                ); 
            
            default: # Unknown Data Type
                return "<font color='red'>CHECK_VAR_DATA</font>";
        }
    }

    

}