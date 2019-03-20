<?php

declare(strict_types=1);

namespace Framework\TemplateEngine;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class provides functions for generating content based on templates
 *
 * @category   TemplateEngine
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class Generator
{				
    /**
     * Used to generate html for the required component using the given template parameters
     *
     * @param string $component the component to generate
     * @param array $parameters the parameters used to generate the component
     *
     * @return string $component_html the html string of the component
     */
    public function Generate(string $component, array $parameters) : string
    {
    	/** The required user interface html/json */
        $component_html           = "";
        /** If the header tags need to be generated */        
        if ($component == "headertags") 
            $component_html       = Config::GetComponent("headertags")->GenerateHeaderTags($parameters);        
        /** If the base page needs to be generated */        
        else if ($component == "basepage") 
            $component_html       = Config::GetComponent("basepage")->GenerateBasePage("basepage");        
        /** The html string for the given template file */
        else {
            /** The template file name */
            $template_file_name   = (strpos($component, ".html") === false) ? $component . ".html" : $component;
            /** The general template file is rendered */
            $component_html       = $this->GenerateTemplate($template_file_name, $parameters);
        }
        
        return $component_html;
    }
    /**
     * It renders the template file using the given template parameters
     *
     * @param string $template_file_name the template file name
     * @param array $parameters optional the parameters used to render the given html template file
     * @param string $template_library the library to use
     *
     * @throws \Error an object of type Error is thrown if the file could not be found     
     *
     * @return string $template_html the template html string
     */
    public function GenerateTemplate(
        string $template_file_name,
        ?array $parameters = null,
        string $template_library = ""
    ) : string {
        /** The path to the framework template folder is fetched */
        $fw_template_path   = Config::GetComponent("basepage")->GetTemplateFolderPath($template_library);
        /** The path to the application template folder is fetched */
        $app_template_path  = Config::$config["path"]["app_template_path"];
        /** The folders to search */
        $search_folders     = array($app_template_path, $fw_template_path);
        /** The folders are searched for the given template file */
        $template_file_path = UtilitiesFramework::Factory("filemanager")->SearchFile(
                                  $search_folders,
                                  $template_file_name
                              );
        /** If the file does not exist, then an exception is thrown */
        if (!is_file($template_file_path)) 
            throw new \Error("Template file: " . $template_file_name . " could not be found");
        
        /** If the template parameters are given, then they are applied to the file */
        if (is_array($parameters)) {
		    /** The callback function for fetching missing template parameters */
		    $callback       = function ($tag_name) {
		                          /** An exception is thrown */
		                          throw new \Error("Value for tag: " . $tag_name . " could not be found");
		                          /** The tag name is checked in path config */
		                          //return (Config::$config['path'][$tag_name] ?? "");
		                      };
		                      
		    /** The general template is rendered using the given template parameters */
		    $template_html  = UtilitiesFramework::Factory("templateutils")->GenerateTemplateFile(
		                          $template_file_path,
		                          $parameters,
		                          $callback
		                      );
		}
		/** If the template parameters are not given, then the file contents are fetched */
		else 
		    $template_html  = UtilitiesFramework::Factory("filemanager")->ReadLocalFile($template_file_path);
		               
        return $template_html;
    }
}
