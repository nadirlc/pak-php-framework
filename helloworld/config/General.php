<?php

declare(strict_types=1);

namespace HelloWorld\Config;

/**
 * This class general application configuration
 *
 * @category   Config
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class General
{
    /**
     * It returns an array containing general configuration data
     *
     * @param array $parameters the application parameters
     *
     * @return array $config the custom configuration
     */
    public function GetConfig(array $parameters) : array
    {
      	/** The required application configuration */
    	$config                       = array();

        /** The path to the pear folder */
        $config['app_name']           = "Hello World";
        /** Indicates if application is in development mode */
        $config['dev_mode']           = true;        
        /** Indicates that user access should be logged */
        $config['log_user_access']    = true;
        
   		/** The list of custom css files */
        $config['custom_css_files']   = array(
                                            array("url" => "{ui}/css/page.css")
                                        );
        																																
        return $config;
    }
}
