<?php

declare(strict_types=1);

namespace Framework\Application;

use \Framework\Config\Config as Config;

/**
 * This class provides the base class for developing browser based applications
 *
 * @category   Application
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
abstract class Web extends \Framework\Application\Application
{
    /**
     * It redirects the user by sending http location header
     *
     * @param string $url the redirect url
     * @throws \Error an exception is thrown if http headers were already sent
     */
    final public function Redirect(string $url) : void 
    {
        /** If the http headers were not sent, then the user is redirected to the given url */
        if (!headers_sent($filename, $linenum)) {
            header("Location: " . $url);
        }
        /** An exception is thrown if http headers were already sent */
        else {
            throw new \Error("Headers already sent in " . $filename . " on line " . $linenum . "\n");
        }
    }
    
    /**
     * It creates a url from the given parameters
     * It generates the url by concatenating the site url, controller and action
     *
     * @param string $controller the controller
     * @param string $action the action
     *
     * @return string $url the url is returned
     */
    public function GenerateUrl(string $controller, string $action) : string
    {
        /** The site url is fetched */
        $site_url                    = Config::$config["general"]["site_url"];
        /** The url is built */
        $url                         = $site_url . $controller . "/" . $action;
        
        return $url;
    }
    
    /**
     * Default url option handler for logout action
     * Used to logout the user
     *
     * It unsets the is_logged_in session variable
     * It redirects the user to the login page
     *
     * {@internal context web}
     */
    final public function HandleLogout() : void 
    {
        /** The session variable is_logged_in is unset */
        $this->SetSessionConfig("is_logged_in", "", true);
        /** The login url */
        $site_url = Config::$config["general"]["site_url"];
        /** The user is redirected to the login url */
        $this->Redirect($site_url);
    }
    
    /**
     * Used to initialize the application
     *
     * It reads the translation information in config file
     * It generates parameters for the application from the url and from the data submitted by the user
     * It generates url routing information that determines which method should handle the current request
     * It optionally enables Php sessions and redirects the user to the login page if the user is not already logged in
     *
     * @param array $parameters the application parameters     
     */
    final public function InitializeApplication($parameters) : void 
    {              
        /** If the user has enabled text translation */
        if (Config::$config["general"]["translate_text"]) {
            /** The translation text is read */
            Config::GetComponent("translation")->ReadTranslationText();
        }
        
        /** The application parameters are generated */
        Config::GetComponent("requesthandling")->GenerateParameters();
		/** The url routing information is generated */
        Config::GetComponent("urlrouting")->GetCallback();
        
        /** If the sessions have been enabled in application config and the script is not being run from the command line */
        if (Config::$config["general"]["enable_sessions"] && php_sapi_name() != "cli") {
            /** Php sessions are enabled */
            Config::GetComponent("sessionhandling")->EnableSessions();        
        }
        
        /** If session authentication has been enabled */
        if (Config::$config["general"]["enable_session_auth"]) {
            /** The user is redirected if not logged in */
            Config::GetComponent("sessionhandling")->RedirectIfNotLoggedIn();
        }
    }
}

