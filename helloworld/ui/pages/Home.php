<?php

declare(strict_types=1);

namespace HelloWorld\Ui\Pages;

use \Framework\Application\Page as Page;
use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class implements the BasePage class
 * It provides common functions that are used to generate the application pages
 *
 * @category   Application
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
class Home extends Page
{
    /**
     * It provides the html for the page footer
     *
     * @param array $params optional the parameters for generating the footer
     *
     * @return string $footer_html the html for the page footer
     */
    protected function GetFooter(?array $params = null) : string
    { 
        /** The tag values for the footer */
		$tag_values  = array("footer-text" => "Congratulations. You have successfully installed the Pak Php Framework");
        /** The footer is generated */		                
        $footer_html = Config::GetComponent("templateengine")->Generate("footer", $tag_values);
		
		return $footer_html;		                
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
        /** The tag values for the header */
		$tag_values   = array("header-text" => "Welcome");
		                        
		/** The footer is generated */		                
        $header_html = Config::GetComponent("templateengine")->Generate("header", $tag_values);
		
		return $header_html;
	}
   
    /**
     * It generates the html for the home page
     *
     * @return string $page_html the html for the page
     */
    public function Generate() : string
    {        
        /** The header contents are fetched */
		$header_html = $this->GetHeader();
		/** The footer contents are fetched */
		$footer_html = $this->GetFooter();
		/** The contents for the scripts is fetched */
		$script_tags = $this->GetScripts();
        /** The template parameters for the table template */
        $tag_values           = array(
                                    "css_tags" => $script_tags['css'],
                                    "title" => "Hello World !",
								    "body" => "<h1>Hello World!</h1>",
									"header" => $header_html,
									"footer" => $footer_html
							    );
							    
   		/** The html for the page */
        $page_html   = Config::GetComponent("templateengine")->Generate("page", $tag_values);							    

		return $page_html;
    }
}
