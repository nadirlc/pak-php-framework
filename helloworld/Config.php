<?php

declare(strict_types=1);

namespace HelloWorld;

/**
 * Application configuration class
 *
 * @category   Config
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class Config extends \Framework\Config\Config
{
    /**
     * Used to determine if the application request should be handled by the current module
     *
     * It returns true if the host name contains example.pakjiddat.pk
     * Otherwise it returns false
     *
     * @param array $parameters the application parameters
     *
     * @return boolean $is_valid indicates if the application request is valid
     */
    public static function IsValid(array $parameters) : bool
    {
    	/** The request is marked as not valid by default */
    	$is_valid     = false;
    	
    	/** If the application is being run from command line */
        if (php_sapi_name() == "cli") {
            /** If the application name is "helloworld" */
            if ($parameters['application'] == "helloworld") {
                $is_valid = true;
            }
        }
        /** If the application is being run from browser */
        else {
            /** If the host name is www.pakjiddat.pk or dev.pakjiddat.pk */
            if ($_SERVER['HTTP_HOST'] == "example.pakjiddat.pk") {        
            	$is_valid = true;
            }
        }            

        return $is_valid;
    }
}
