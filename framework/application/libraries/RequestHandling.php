<?php

declare(strict_types=1);

namespace Framework\Application\Libraries;

use \Framework\Config\Config as Config;
use \Framework\Utilities\UtilitiesFramework as UtilitiesFramework;

/**
 * This class provides functions for generating application parameters and handling url requests
 *
 * @category   Libraries
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class RequestHandling
{
    /**
     * All requests to the application are handled by this function
     *
     * @param array $parameters the application parameters
	 *          
     * @throws \Error an exception is thrown if application is in test mode and invalid test type is given in config
     *
     * @return string $response the application response
     */
    public function Main(array $parameters) : string
    {
        /** The application response */
        $response                = "";
        /** If the application is being run from command line */
        if (php_sapi_name() == "cli") {
            /** The unit tests are run */
            if ($parameters['action'] == "Unit Test") 
                Config::GetComponent("unittestrunner")->RunUnitTests();
            /** The test data is loaded from test files to database */
            else if ($parameters['action'] == "Load Test Data") 
                Config::GetComponent("testdatamanager")->LoadTestDataFromFiles();
            /** The test data files are generated from source code */
            else if ($parameters['action'] == "Generate Test Data") 
                Config::GetComponent("testdatamanager")->GenerateTestData();                
            /** If some other test type is given then an exception is thrown */
            else throw new \Error("Invalid test type given in application config");
        }
        /** If the application is not in test mode */
        else {
            /** The application function output */
            $response            = $this->RunApplicationFunction();
        }

        return $response;
    }
    /**
     * Used to run the function for the given action and controller
     *
     * It calls a controller function or a template function for the given action and controller
     * If no function is defined in the application config
     * Then a function name is auto generated and called
     *
     * @return string $string the function response
     */
    public function RunApplicationFunction() : string
    {
        /** The custom validator */
        $validator                = Config::$config["general"]["validator"];        
        /** The application parameters */
        $parameters               = Config::$config["general"]["parameters"];
        /** The action callback is fetched */
        $callback                 = Config::$config["general"]["callback"];
        /** The controller object is fetched */
        $callback_obj             = Config::GetComponent($callback[0]);
        /** If the url mapping is not callable */
        if (!is_callable(array($callback_obj, $callback[1]))) {
            /** The error message */
            $msg  = "Invalid url request sent to application. Object: " . $callback[0] . ". Function: ";
            $msg .= $callback[1] . " is not callable";
            /** An exception is thrown */
            throw new \Error($msg);                
        }
 
        /** If the url request should be logged */
        if (Config::$config["general"]["log_user_access"]) {
            /** The profiling of execution time is started */
            UtilitiesFramework::Factory("profiler")->StartProfiling("execution_time");
        }
        
        /** The class name */
        $class_name           = get_class($callback_obj);
        /** The method to validate */
        $method               = array("class" => $class_name, "method" => $callback[1]);
        /** If the validator object and function are given */
        if (isset($validator[0]) && isset($validator[1])) {
            /** The validator object */
            $obj              = Config::GetComponent($validator[0]);
            /** The validator function */
            $func_name        = $validator[1];
            /** The validator callable is placed in array */
            $validator        = array("callback" => array($obj, $func_name), "params" => array());
        }
        /** The function parameters are validated */
        Config::GetComponent("functionvalidation")->ValidateMethodParameters($method, $parameters, $validator);
        /** The callable method to call */
        $main_method          = array($callback_obj, $callback[1]);
        /** The parameter values are sorted by key */
        ksort($parameters);
        /** The parameter values */
        $arguments            = array_values($parameters);

        /** The callback function is called */
        $response             = call_user_func_array($main_method, $arguments);
        /** The function return value is validated */
        Config::GetComponent("functionvalidation")->ValidateMethodReturnValue($method, $response, $validator);
        
        /** If the application response should be sanitized */
        if (Config::$config["general"]["sanitize_response"]) {
            /** The function return value is sanitized */
            $response             = Config::GetComponent("functionvalidation")->SanitizeData($response);
        }            
        /** If the request data should be saved as test data for user interface tests */
        if (Config::$config["test"]["save_ui_test_data"]) {
            /** The request data is saved to database */
            Config::GetComponent("testdatamanager")->SaveUiTestData();
        }
        
        /** If the url request should be logged */
        if (Config::$config["general"]["log_user_access"]) {
            /** The user request is logged */
            Config::GetComponent("loghandling")->LogUserAccess();
        }
        
        return $response;
    }

    /**
     * Used to return the contents of the file given in the url
     *
     * It reads the template file whoose name is given by the url
     * This function may be called by a user defined function which needs to set template values in the file
     *
     * @return string $file_contents
     */
    public function GetTemplateFile() : string
    {
	    /** The relative path of the template file. It is same as the current url */
        $relative_file_path       = Config::$config["path"]["request_url"];
        /** The html extension is appended to the file path */
        $relative_file_path       .= ".html";
        /** The template file is read */
        $file_contents            = Config::GetComponent("template")->RenderTemplate($relative_file_path);
        
        return $file_contents;
    }
    
    /**
     * Used to generate parameters for the application
     *
     * It parses the current url
     * The $_REQUEST object is saved to application config    
     * This function may be overriden by a child class in order to customize the url parsing
     */
    public function GenerateParameters() : void
    {
        /** The site url */
        $site_url         = Config::$config["general"]["site_url"];
        /** The information submitted by the user */
        $parameters       = Config::$config["general"]["http_request"];
        
        /** The request url is sanitized */
        Config::$config["general"]["request_uri"] = Config::GetComponent("functionvalidation")->SanitizeText(
                                                        Config::$config["general"]["request_uri"],
                                                        "url"
                                                     );        
        /** The current url request */
        $request_url      = Config::$config["general"]["request_uri"];
        /** The site url is removed from the current url */
        $request_url      = str_replace($site_url, "", $request_url);
        /** The url path list */
        $parts            = explode("/", ltrim($request_url, "/"));
        
        /** Each input parameter is checked */
        foreach ($parameters as $key => $value) {
            /** If the type of the input parameter is given in application configuration */
            $type             = Config::$config["general"]['input_types'][$key] ?? "plain text";
            /** The user input is sanitized */
            $parameters[$key] = Config::GetComponent("functionvalidation")->SanitizeText($value, $type);
        }

        /** The page parameters are saved to application config */
        Config::$config["general"]["parameters"]           = $parameters;
    }
}
