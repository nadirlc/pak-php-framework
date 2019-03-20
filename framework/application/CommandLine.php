<?php

declare(strict_types=1);

namespace Framework\Application;

/**
 * This class provides the base class for applications that require command line interface
 *
 * It provides functions that are commonly used by command line applications
 *
 * @category   Application
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
abstract class CommandLine extends \Framework\Application\Application
{
    /**
     * Used to echo the text in the given color
     * The text may be formatting using attribute tags     
     * For example use <bold>test</bold> to make text bold
     * Supported formatting attributes are given assigned to $set_codes
     *
     * @param string $text the text to echo
     * @param string $color the text color. the $color_codes variable contains list of all supported colors
     */
    final public static function DisplayOutput($text, $color = "white") : void 
    {
        /** The color codes */
        $color_codes        = array(
                                  "default" => 39,
                                  "black" => 30,
                                  "red" => 31,
                                  "green" => 32,
                                  "yellow" => 33,
                                  "blue" => 34,
                                  "magenta" => 35,
                                  "cyan" => 36,
                                  "lightgray" => 37,
                                  "darkgray" => 90,
                                  "lightred" => 91,
                                  "lightgreen" => 92,
                                  "lightyellow" => 93,
                                  "lightblue" => 94,
                                  "lightmagenta" => 95,
                                  "lightcyan" => 96,
                                  "white" => 97
                            );
        /** The codes for setting formatting attributes */
        $set_codes          = array(
                                  "bold" => 1,
                                  "dim" => 2,
                                  "underline" => 4,
                                  "blink" => 5,
                                  "reverse" => 7,
                                  "hidden" => 8
                              );
                        
        /** The codes for resetting formatting attributes */
        $reset_codes        = array(
                                  "all" => 0,
                                  "bold" => 21,
                                  "dim" => 22,
                                  "underline" => 24,
                                  "blink" => 25,
                                  "reverse" => 27,
                                  "hidden" => 28 
                              );
                        
        /** If the color is not supported, then an exception is thrown */
        if (!isset($color_codes[$color]))
            throw new \Error("Invalid color: " . $color);                        
                        
        /** The color code for the given color */
        $color_code         = $color_codes[$color];
        /** The formatted text */
        $formatted_text     = "\e[" . $color_code . "m" . $text . "\e[0m";
        
        /** Each tag is replaced by its formatting code */
        foreach ($set_codes as $attribute => $code) {
            /** The reset code */
            $reset_code     = $reset_codes[$attribute];
            /** The attribute opening tag is replaced by its code */
            $formatted_text = str_replace("<" . $attribute . ">", "\e[" . $code . "m", $formatted_text);
            /** The attribute closing tag is replaced by its reset code */
            $formatted_text = str_replace("</" . $attribute . ">", "\e[0m", $formatted_text);            
        }
        
        /** Each tag is replaced by its color code */
        foreach ($color_codes as $attribute => $code) {
            /** The attribute opening tag is replaced by its code */
            $formatted_text = str_replace("<" . $attribute . ">", "\e[" . $code . "m", $formatted_text);
            /** The attribute closing tag is replaced by its reset code */
            $formatted_text = str_replace("</" . $attribute . ">", "\e[0m", $formatted_text);            
        }
        
        /** The code for resetting all formatted text is appended */
        $formatted_text     .= "\e[" . $reset_codes["all"] . "m";
        
        echo $formatted_text;
    } 
    
	/**
     * Used to display the basic usage of the framework
     *
     * It outputs usage information to the console
     */
    public static function HandleUsage() : void
    {
        /** The application usage */
        $usage       = <<< EOT
        
        
  Usage: php index.php --application="[app-name]" --action="[action]" [--parameter-name="parameter-value"]

  Applications: 
EOT;

        /** The application usage */
        $usage       .= "";
        
        /** The list of all applications */
        $app_names   = self::GetApplicationList();
        
        /** The application list is appended to the usage information */
        $usage       .= implode(", ", $app_names); 
        
        /** The usage information is updated */
        $usage       .= <<< EOT
\n\n  Default Actions: \n
  1. Unit Test (run unit tests. specify type of test. i.e unit or ui in app config)
  2. Generate Test Data (generates test data files from method comments)\n\n
EOT;
        
        die($usage);
    }
    /**
     * Used to return the list of all applications supported by the current framework installation
     *
     * It returns an array containing the folder names of all the applications
     *
     * @return array $app_names the list of all application names
     */
    private static function GetApplicationList() : array
    {        
        /** The path to the framework parent folder */
        $folder_path         = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "..");
        /** The contents of the framework folder */
        $dir_list            = scandir($folder_path);
        /** The list of application folder names */
        $app_names           = array();
        /** Each application name is checked */
        for ($count = 0; $count < count($dir_list); $count++) {
        	/** If the application name is 'framework', '.' or '..' or it is a file, then the loop continues */
        	if ($dir_list[$count] == 'framework' || 
        	    $dir_list[$count] == '.' || 
        	    $dir_list[$count] == '..' || 
        	    is_file($folder_path . DIRECTORY_SEPARATOR . $dir_list[$count])
        	) 
        	    continue;
        	/** The application name */
        	$app_names[]     = $dir_list[$count];
        }
        
        return $app_names;
    }
    /**
     * Used to validate the default command line arguments
     *
     * It checks if the command line arguments are correct
     * If the arguments are not correct, then the default script usage is shown and application ends
     *
     * @param array $parameters the application parameters
     */
    private static function CheckCommandLineParameters(array $parameters) : void
    {
    	/** The list of valid test types */
    	$default_actions      = array("Unit Test", "Load Test Data", "Generate Test Data");
	    /** The list of all applications */
        $app_names            = self::GetApplicationList();
        /** If the application name was not given, then the default usage information is shown and application ends */
    	if (!isset($parameters['application'])) {
          	/** Warning message is shown */
           	self::DisplayOutput("\n  <bold>Application name was not given</bold>", "red");
           	self::HandleUsage();
       	}
       	/** If the wrong application name was given, then the default usage information is shown and application ends */
    	if (!in_array(strtolower($parameters['application']), $app_names)) {
          	/** Warning message is shown */
           	self::DisplayOutput("\n  <bold>Invalid application name !</bold>", "red");
           	self::HandleUsage();
       	}
       	/** If the action was not given, then the default usage information is shown and application ends */
   		else if (!isset($parameters['action'])) {
         	/** Warning message is shown */
           	self::DisplayOutput("\n  <bold>Application action was not given !</bold>", "red");
           	self::HandleUsage();
        }
        /** If the action was not correctly given, then the default usage information is shown and application ends */
   		else if (isset($parameters['action']) && !in_array($parameters['action'], $default_actions)) {
         	/** Warning message is shown */
           	self::DisplayOutput("\n  <bold>Invalid action !</bold>", "red");
           	self::HandleUsage();
        }
    }
    
    /**
     * It parses the command line arguments and saves the data to application config
     * The application is terminated if a command line argument is not of the form --key=value
     *
     * @param array $parameters the command line arguments
     *
     * @return array $updated_parameters the parsed command line arguments in key, value format
     */
    public static function ParseCommandLineArguments(array $parameters) : array
    {
        /** The updated application parameters in key, value format */
        $updated_parameters               = array();
        /** The application parameters are determined */
        for ($count = 1; $count < count($parameters) && isset($parameters[1]); $count++) {
            /** Single command line argument */
            $command                      = $parameters[$count];
            /** If the command does not contain equal sign then an exception is thrown. only commands of the form --key=value are accepted */
            if (strpos($command, "--") !== 0 || strpos($command, "=") === false) 
                die("Invalid command line argument was given. Command line arguments: " . var_export($parameters, true));
            else {
                /** The '--' is removed from the command line parameter */
                $command                  = str_replace("--", "", $command);
                /** The command line parameters is split on '=' character' */
                list($key, $value)        = explode("=", $command);
                /** The name and value of the parameters are saved */
                $updated_parameters[$key] = $value;
            }
        }
        /** The parameters are set */
        $parameters                       = $updated_parameters;
        /** The command line parameters are checked */
        self::CheckCommandLineParameters($parameters);
        /** If the application is being tested and xdebug extension has been enabled */
	    if ($parameters['action'] == 'Test' && function_exists("xdebug_start_code_coverage")) {
            /** The code coverage is started */
    	    \xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
        }
        
        return $updated_parameters;
    }
    
    /**
     * Used to determine if the given cli application is valid
     * It parses the command line arguments
     * It checks if the application name given in command line matches the application name
     *
     * @param string $config_class_name the class name for the Config class
     * @param array $parameters the command line parameters given by the user
     *
     * @return bool $is_valid indicates if the application name is valid
     */
    public static function IsValidCliApplication(string $config_class_name, array $parameters) : bool
    {
        /** Indicates if the application name is valid */
        $is_valid                        = false;
        
        /** If the application is not being run from command line */
        if (php_sapi_name() != "cli") {
            /** The function returns */
            return $is_valid;
        }
        
        /** The General config class name */
        $general_class_name              = $config_class_name . "\General";
        /** The class object */
    	$class_obj                       = new $general_class_name();
        /** The general class config is fetched */
        $general_config                  = $class_obj->GetConfig($parameters);
        /** The application name */
        $app_name                        = strtolower(str_replace(" ", "", $general_config['app_name']));
        /** If the application name matches, then the script given by $config_class_name is valid */
        $is_valid                        = ($app_name == strtolower($parameters['application'])) ? true : false;

        return $is_valid;
    }
    
    /**
     * Used to initialize the application
     *
     * It generates application parameters from the command line arguments given by the user
     *
     * @param array $parameters the application parameters     
     */
    public function InitializeApplication($parameters) : void 
    {              
        /** The current class name */
        $class_name         = get_class();
        /** The command line arguments are parsed */
        $updated_parameters = $class_name::ParseCommandLineArguments($parameters);
        /** The updated parameters are set to application config */
        $this->SetArrayConfig("general", "parameters", $updated_parameters);
    }
}
