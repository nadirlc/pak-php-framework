<?php

declare(strict_types=1);

namespace HelloWorld\Config;

/**
 * This class provides required objects application configuration
 *
 * @category   Config
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class RequiredObjects
{
    /**
     * It returns an array containing requiredobjects configuration data
     *
     * @param array $parameters the application parameters
     *
     * @return array $config the custom configuration
     */
    public function GetConfig(array $parameters) : array
    {
      	/** The required application configuration */
    	$config                                    = array();
      	/** The database parameters */
       	$dbparams                                  = array(
		                                                    "dsn" => "mysql:host=localhost;dbname=pakjiddat_pakphp;charset=utf8",
				                                            "user_name" => "nadir",
				                                            "password" => "kcW5eFSCbPXb#7LHvUGG8T8",
				                                            "use_cache" => false,
				                                            "debug" => 2,
				                                            "app_name" => "Hello World"
	                                                    );
        /** The framework database parameters */
        $fwdbparams                                = $dbparams;													    	
	   	
	   	/** The framework database object parameters */
        $config['dbinit']['parameters']            = $dbparams;		
        /** The mysql database access class is specified with parameters for the pakjiddat_com database */
        $config['frameworkdbinit']['parameters']   = $fwdbparams;

	   	$config['application']['class_name']       = '\HelloWorld\Ui\Pages\Home';   		   	
	   	        
        return $config;
    }
}
