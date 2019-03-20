<?php

declare(strict_types=1);

namespace Framework\Application\Libraries;

use \Framework\Config\Config as Config;

/**
 * This class provides function for handling sessions
 *
 * @category   Libraries
 * @author     Nadir Latif <nadir@pakjiddat.pk>
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 */
final class SessionHandling
{
    /**
     * Used to enable php sessions
     *
     * This function enables php sessions
     */
    public function EnableSessions() : void 
    {
       /** If the session is not started then it is started */
       if (!$this->IsSessionStarted()) {
           /** The session is started */
           session_start();
           /** A new session id is generated */
           session_regenerate_id();
       }
	   /** The session data is set to application config */
       Config::$config["general"]["session"]   = $_SESSION;
    }
    
    /**
     * Used to authenticate the user
     * It checks the value of the is_logged_in session variable
     * If the user is not logged in and the current page is not the login page or the login validation url
     * Then the user is redirected to the login page
     */
    public function RedirectIfNotLoggedIn() : void 
    {
        /** The login validation url */
        $login_validation_url    = Config::$config["general"]["login_validation_url"];
		/** The login url */
        $login_url               = Config::$config["general"]["login_url"];
        /** The current url */
        $url             = Config::$config["path"]["url"];
      
        /** If the current page is not same as login url */        
        if ($url != $login_validation_url && $url != $login_url && $this->GetSessionConfig('is_logged_in') != "yes") 
        {
            /** The user is redirected to the login page */
            Config::GetCompnent("application")->Redirect($login_url);
        }
    }
    
    /**
     * Used to check if the given user name and password match the login information in application config
     *
     * @param string $user_name the user name
     * @param string $password the user password
     *
     * @return boolean $is_valid indicates if the given credentials are valid
     */
    public function ValidateCredentials(string $user_name, string $password) : bool
    {
        /** Indicates if the given credentials are valid */
        $is_valid         = false;
        /** The valid credentials are fetched */
        $credentials      = Config::$config["session_auth"]["credentials"];
        /** Each valid credential is checked against the given credentials */
        for ($count = 0; $count < count($credentials); $count++) 
        {
            /** If the user name and/or password is incorrect then the user is marked as not logged in */
            if ($credentials[$count]['user_name'] == $user_name && $credentials[$count]['password'] == $password) 
            {
                /** is_valid is set to true */
                $is_valid  = true;
                /** The session variable is_logged_in is set */
                $this->SetSessionConfig("is_logged_in", "yes", false);
                /** The session variable full_name is set */
                $this->SetSessionConfig("full_name", $credentials[$count]['full_name'], false);
            }
        }
       
        return $is_valid;
    }
    
    /**
     * Used to set the given session config
     *
     * @param string $config_name name of the required session config
     * @param string $config_value value of the required session config
     * @param boolean $unset indicates if the session variable should be unset
     */
    public function SetSessionConfig(string $config_name, string $config_value, bool $unset) : void 
    {
        /** If the session variable should be unset */
        if ($unset) 
        {
            unset($_SESSION[$config_name]);
        }
        else
        {
            /** Sets the given session variable */
            $_SESSION[$config_name] = $config_value;
        }
    }
    /**
     * Used to get the given session config     
     *
     * @param string $config_name name of the required session config
     *
     * @return string $session_config_value the session config value
     */
    public function GetSessionConfig(string $config_name) : string
    {
        /** If the given session variable is not set then function returns empty */
        if (!isset($_SESSION[$config_name])) $session_config_value = "";
        else 
        {
            /** Returns the given session variable */
            $session_config_value = $_SESSION[$config_name];
        }
        return $session_config_value;
    }
    /**
     * Used to determine if a session has been started
     *
     * @return boolean $is_session_started true if session is already started. false if session has not been started
     */
    public function IsSessionStarted() : bool
    {
        /** Indicates if the session has been started */
        $is_session_started = false;
        /** If the php is not being run from command line */
        if (php_sapi_name() !== 'cli') 
        {
            /** If the current php version is greater than or equal to 5.4.0 */
            if (version_compare(phpversion() , '5.4.0', '>=')) 
            {
                $is_session_started = session_status() === PHP_SESSION_ACTIVE ? true : false;
            }
            else
            {
                $is_session_started = session_id() === '' ? false : true;
            }
        }
        return $is_session_started;
    }
}
