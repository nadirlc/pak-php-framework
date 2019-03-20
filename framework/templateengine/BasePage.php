<?php

declare(strict_types=1);

namespace Framework\TemplateEngine;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * Provides functions for generating the html content for the current request based on template files
 *
 * @category   TemplateEngine
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General public License, version 2
 */
final class BasePage
{   
    /**
     * It gets the template handling function details from application config
     * And calls the function. The function should return tag replacement values
     *
     * @param string $tag_name the name of a template tag
     * @throws \Error an exception is thrown if no template handling function was found for the given template and option
     *
     * @return array $template_information
     *    file_name => string template file name
     *    tag_values => array list of tags values that will replace the tags in the given template file
     */
    public function GetTemplateInfo(string $tag_name) : array
    {
        /** The application url mapping config */
        $url_mappings                              = Config::$config["general"]["url_mappings"];
        /** The action is fetched from application config */
        $action                                    = Config::$config["general"]["action"];
        /** The controller is fetched from application config */
        $controller                                = Config::$config["general"]["controller"];
        /** If no url mapping is defined for the current url option then an exception is thrown */
        if (!isset($url_mappings[$controller][$action]))
            throw new \Error("Invalid url request sent to application");
        /** If the function type is template then application url template mapping is fetched */
        $url_templates                             = $url_mappings[$controller][$action]["templates"];
        /** The tag replacement array. It contains the tag replacement values */
        $tag_replacement_arr                       = array();
        /** Each template mapping is checked */
        for ($count = 0; $count < count($url_templates); $count++) {
            /** If the tag name was found in the url template mapping then the callback function is called */
            if ($url_templates[$count]["tag"] == $tag_name) {
                /** The name of the callback object */
                $ui_object_name                    = $url_templates[$count]['object'];
                /** The callback function name */
                $function_name                     = $url_templates[$count]['function'];
                /** The template file name */
                $template_file_name                = $url_templates[$count]['file'];
            }
        }
        /** If the tag name was not found in the url template mapping */
        if (!isset($template_file_name)) {
            /** The name of the callback object */
            $ui_object_name                        = "application";
            /** The callback function name */
            $function_name                         = "HandleIndex";
            /** The template file name */
            $template_file_name                    = $tag_name . ".html";
        }

        /** The callback object */
        $ui_object                                 = Config::GetComponent($ui_object_name);
        /** The parameters that are passed to the callback function */
        $template_parameters                       = array();
        /** The template callback function is defined */
        $template_callback                         = array($ui_object, $function_name);
        /** If the function is callable then it is called */
        if (is_callable($template_callback)) {
            $tag_replacement_arr                   = call_user_func_array(
                                                         $template_callback, 
                                                         array($template_file_name, $template_parameters)
                                                     );
        }                                                                
        /** If it is not callable then the tag replacement array is initialized */
        else $tag_replacement_arr                  = array();
         
        /** The required template information */
        $template_information                      = array(
                                                         "file_name" => $template_file_name,
                                                         "tag_values" => $tag_replacement_arr
                                                     );
        
        return $template_information;
    }
    /**
     * Used to render an application template
     *
     * It displays the given template. It reads the template file from the given path
     * It then extracts all the tags from the template file. It then calls the object function defined in the template tag mapping
     * This mapping is defined in application config file. The object function is then called
     * This function returns an array of tag replacement values. These values are used to replace the tags
     * A tag can be handled by a function defined in the template tag mapping
     *
     * @param string $tag_name template tag name. name of the tag that needs to be replaced with a template
     *
     * @return string $template_contents the contents of the template file with all the tags replaced. suitable for diplaying in browser
     */
    public function GenerateBasePage(string $tag_name) : string
    {
        /** The template handling function is called with given option and parameters */
        $template_info              = $this->GetTemplateInfo($tag_name);
        /** The path to the framework template folder is fetched */
        $fw_template_path           = $this->GetTemplateFolderPath();
        /** The path to the application template folder is fetched */
        $app_template_path          = Config::$config["path"]["app_template_path"];
        /** The folders to search */
        $search_folders             = array(
                                          $app_template_path,
                                          $fw_template_path
                                      );
                              
        /** The folders are searched for the given template file */
        $template_file_path         = UtilitiesFramework::Factory("filemanager")->SearchFile(
                                          $search_folders,
                                          $template_info['file_name']
                                      );
        
        /** If the template file does not exist, then an Exception is thrown */
        if (!is_file($template_file_path)) 
            throw new \Error("Template file: " . $template_info['file_name'] . " could not be found");
            
        /** The tags in the template file are extracted */
        $template_tags_info         = UtilitiesFramework::Factory("templateutils")->ExtractTemplateTags(
                                          $template_file_path
                                      );
        /** The template file contents */
        $template_contents          = $template_tags_info['contents'];
        /** For each extracted template tag the value for that tag is fetched */
        for ($count = 0; $count < count($template_tags_info['tag_list']); $count++) {
            /** First the tag value is checked in the tag replacement array returned by the template handling function */
            $tag_name               = $template_tags_info['tag_list'][$count];
            /** If the tag name contains a space, then loop continues */
            if (strpos($tag_name, " ") !== false) continue;
            
            /** if the array key exists */
            if (array_key_exists($tag_name, $template_info["tag_values"])) {
                /** The tag value is set */
                $tag_value          = $template_info["tag_values"][$tag_name];
            }
            else {
                $tag_value          = '!not found!';
            }
            
            /** If the tag value is an array then the array is processed. This array can contain further template tags */
            if (is_array($tag_value)) 
                $tag_value          = UtilitiesFramework::Factory("templateutils")->ReplaceTagWithArray(
                                          $tag_name,
                                          $tag_value
                                      );
            /** If the tag value was not found then the function is called recursively */
            else if ($tag_value == '!not found!') 
                $tag_value          = $this->GenerateBasePage($tag_name);
                
            /** If the tag value was not found then an exception is thrown */
            if ($tag_value == '!not found!')
                throw new \Error("Tag value for tag: " . $tag_name . " could not be found");
            /** The tag name is replaced with the tag contents */
            $template_contents      = str_replace("{" . $tag_name . "}", $tag_value, $template_contents);
        }
        
        return $template_contents;
    }

    /**
     * Used to get the absolute path to the framework template folder for the current template library
     *
     * @param string $template_library the library to use
     *     
     * @return string $fw_template_path the absolute path to the framework template folder
     */
    public function GetTemplateFolderPath(string $template_library = "") : string
    {
        /** The path to the framework template folder is fetched */
        $fw_template_path  = Config::$config["path"]["fw_template_path"];
        /** If the template library name is not given */
        if ($template_library == "") {
            /** The template library name is fetched from application configuration */
            $template_library  = Config::$config["general"]["template_library"];
        }
        
        /** The framework template folder path for the template library is determined */
        $fw_template_path       = str_replace("{template_library}", $template_library, $fw_template_path);

        return $fw_template_path;
    }
}
