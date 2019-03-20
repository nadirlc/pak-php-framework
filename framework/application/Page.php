<?php

declare(strict_types=1);

namespace Framework\Application;

use \Framework\Application\Web as WebApplication;
use \Framework\Config\Config as Config;

/**
 * This class provides the base class for developing browser based applications
 *
 * It extends the WebApplication class 
 * It adds functions that allow parsing template files
 *
 * @category   Application
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
abstract class Page extends WebApplication
{    
    /**
     * It generates the html for the home page
     *
     * @return string $page_html the html for the page
     */
    public function Generate() : string
    {        
		/** The header contents are fetched */
		$header_html = parent::GetHeader();
		/** The footer contents are fetched */
		$footer_html = parent::GetFooter();		
		/** The contents for the scripts is fetched */
		$script_tags = parent::GetScripts();
		/** The html for the page body */
        $body_html   = Config::GetComponent("templateengine")->Generate("body", array());
        /** The template parameters for the table template */
        $tag_values           = array(
                                    "title" => $script_tags['title'],
								    "css_tags" => $script_tags['css'],
									"js_tags" => $script_tags['js'],
									"font_tags" => $script_tags['fonts'],
									"body" => $body_html,
									"header" => $header_html,
									"footer" => $footer_html
							    );
							    
   		/** The html for the page */
        $page_html   = Config::GetComponent("templateengine")->Generate("page", $tag_values);							    
		
		return $page_html;
    }
    /**
     * Used to return the page title
     * 
     * @return string $title the page title
     */
    protected function GetTitle() : string
    {
        /** The page title */
        $title                      = "";
        
        return $title;
    }
   
    /**
     * Used to get the absolute urls to the javascript, css and font files
     * The files are defined in application config
     *
     * @return array $data the required absolute urls
     */
    private function GetFileUrls() : array
    {
        /** The custom css files */
        $custom_css               = Config::$config["general"]["custom_css_files"];
        /** The custom javascript files */
        $custom_javascript        = Config::$config["general"]["custom_js_files"];
        /** The custom font files */
        $custom_fonts             = Config::$config["general"]["custom_font_files"];

        /** The library folder url */
        $fw_lib_url               = Config::$config["path"]["fw_vendors_url"];
        /** The vendor folder url */
        $vendor_folder_url        = Config::$config["path"]["vendor_folder_url"];
        /** The userinterface folder url */
        $ui_folder_url            = Config::$config["path"]["ui_folder_url"];
        /** The custom css and javascript files */
        $file_list                = array(
                                        "css_files" => $custom_css,
                                        "javascript_files" => $custom_javascript,
                                        "font_files" => $custom_fonts
                                    );
        /** All custom files are converted to absolute urls */
        foreach ($file_list as $type => $file_names) {
            /** The application folder path is appended to each css and javascript file */
            for ($count = 0; $count < count($file_names); $count++) {
                /**  If the file name is not an absolute url */
                if (strpos($file_names[$count]["url"], "http://") === false &&
                    strpos($file_names[$count]["url"], "https://") === false
                ) {
                    
                    /** If the url points to user interface folder */
                    if (strpos($file_names[$count]["url"], "{ui}") !== false) {
                        /** The base url is set to user interface folder url */
                        $base_url        =  $ui_folder_url;
                    }
                    
                    /** If the url does not point to user interface folder */
                    else {
                        /** The base url is set to framework library folder url */
                        $base_url        =  $fw_lib_url;
                    }
                    
                    /** The prefix are removed from the custom url */
                    $file_names[$count]["url"] = str_replace("{framework}", "", $file_names[$count]["url"]);
                    /** The prefix are removed from the custom url */
                    $file_names[$count]["url"] = str_replace("{ui}", "", $file_names[$count]["url"]);

                    /** The base url is appended to the custom file name */
                    $file_names[$count]["url"] = $base_url . $file_names[$count]["url"];
                }
            }
            /** The file paths are updated */
            $file_list[$type]                  = $file_names;
        }

        /** The files are placed in an array */
        $data                                  = array(
                                                     "javascript" => $file_list['javascript_files'],
                                                     "css" => $file_list['css_files'],
                                                     "fonts" => $file_list['font_files']
                                                 );

        return $data;
    }
    
    /**
     * Used to return the list of css, javascript and font tags
     * 
     * @return array $header_tags the list of css, javascript and font tags
     */
    protected function GetHeaderTags() : array
    {       
        /** The javascript, css and font tags are generated */
        $urls                         = $this->GetFileUrls();
        /** The css, javascript and font tags */
        $header_tags                  = Config::GetComponent("templateengine")->Generate("headertags", $urls);
		/** The css and javascript tags are json decoded */
        $header_tags                  = json_decode($header_tags, true);
        
        return $header_tags;
    }

    /**
     * It provides the html for the page body
     *
     * @param array $params the parameters for generating the body
     *
     * @return string $body_html the html for the body
     */
    protected function GetBody(?array $params = null) : string
    {
    	$body_html = "";
    	
    	return $body_html;
    }
    
    /**
     * It provides the html for the page header
     *
     * @param array $params the parameters for generating the header
     *
     * @return string $header_html the html for the header
     */
    protected function GetHeader(?array $params = null) : string
    {
    	$header_html = "";
    	
    	return $header_html;
    }
    
    /**
     * It sets the custom JavaScript, CSS and Font files to application configuration depending on the current page
     */
    protected function UpdateScripts() : void
    {
        
    }
    
    /**
     * It provides the html for the page footer
     *
     * @param array $params optional the parameters for generating the footer
     *
     * @return string $footer_html the html for the page footer
     */
    protected function GetFooter(?array $params = null) : string
    {
    	$footer_html = "";
    	
    	return $footer_html;
    }
 
     /**
     * It provides the code for the page scripts
     *
     * @param array $params the parameters for generating the scripts
     *
     * @return array $tag_values the script code
     *    title => string the page title
     *    css => string the css tags
     *    js => string the js tags
     *    fonts => string the font tags
     */
    protected function GetScripts(?array $params = null) : array
    {
        /** The JavaScript, CSS and Font tags are set for the current page */
        $this->UpdateScripts();
        /** The JavaScript, CSS and Font tags are generated */
        $header_tags          = $this->GetHeaderTags();       
        /** The template parameters for the table template */
        $tag_values           = array(
                                    "title" => "",
								    "css" => $header_tags['css'],
									"js" => $header_tags['javascript'],
									"fonts" => $header_tags['fonts']
							    );

        return $tag_values;  
    }   
    /**
     * Used to handle requests for which no matching url routing rules were found
     * It returns http status code of 404
     * It displays a custom 404 error page
     * This function may be overriden by child classes
     */
    public function HandlePageNotFound() : void
    {
        /** The http status header is sent with code 404 */
        http_response_code(404);
        /** The page title */
        $title         = Config::$config["general"]["app_name"] . " - Page not found";
        /** The contents of 404 page is fetched */
        $page_contents = Config::GetComponent("templateengine")->Generate("404", array("title" => $title));
        /** The page contents are displayed */
        $this->DisplayOutput($page_contents);
        /** The script ends */
        die();
    }
}
