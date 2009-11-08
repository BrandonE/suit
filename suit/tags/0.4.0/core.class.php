<?php
/**
**@This file is part of The SUIT Framework.

**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.

**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.
**/
class SUIT
{
	/**
	The version number we are running on.
	**@var float
	**/
	var $version = 0.1;

	/**
	The friendly version number we are running on.
	**@var integer
	**/
	var $version_friendly = 01;
	
	/**
	Publicly display PHP Script errors inside the script? 
	This should only be set to 1 if you're in a development environment. Otherwise, this should be set to 0.
	**@var boolean
	**/
	var $debug = 1;
	
	/**
	Storing user account information in the array.
	**@var 
	**/
	var $userinfo = array();
	
	/**
	Set the user information and store it in an associative array for easier uses in the script.
	**/
	function setUserinfo()
	{
		//If the condition is met, then we'll set the userid and the password to the values matching the cookie in the database.
		if (!defined('ID') && !defined('PASS'))
		{
			//Begin with the user's cookies, first.
			if (isset($_COOKIE['id']) && isset($_COOKIE['pass']))
			{
				$id = intval($_COOKIE['id']);
				$pass = $this->mysql->escape($_COOKIE['pass']);
				//Query the database with the supplied information.
				$usercheck_options = array(
				'where' => 'id = \'' . $id . '\' AND password =\'' . $pass . '\''
				);
			
				$usercheck = $this->mysql->select('users', '*', $usercheck_options);
			
				if ($usercheck)
				{
					$this->userinfo = mysql_fetch_assoc($usercheck);
				}
				else
				{
					//The user was not found. We declare the userid as 0 for no user.
					$this->userinfo['id'] = 0;
					$this->userinfo['password'] = '';
					//Also delete the cookie, since it's useless.
					setcookie('id', '', time()-3600, '' . COOKIE_PATH . '', '' . COOKIE_DOMAIN . '');
					setcookie('pass', '', time()-3600, '' . COOKIE_PATH . '', '' . COOKIE_DOMAIN . '');
				}
			}
			else
			{
				$this->userinfo['id'] = 0;
				$this->userinfo['password'] = '';
			}
			//Set the constants as the userinfo, once all of the checking is complete.
			define('ID', $this->userinfo['id']); //The User ID
			define('PASS', $this->userinfo['password']); //The password .
		}
	}
	/**
	Load a module from a .class.php file
	**
	**@param string The module name, excluding the extension.
	**@param string The variable to create the object as.
	**/
	function loadModule($file_name, $class_name, &$reference='')
	{
		$include = "" . PATH_HOME . "modules/{$class_name}.class.php";
		
		if (file_exists($include))
		{
			//Let's load the class file.
			include $include;
			
			//$mn is the defined class name under which the object is created from. 
			//We have to verify that it is properly set before creating an object.
			if (isset($mn) && class_exists($mn))
			{
				//We can now call this object as: $this->class_name. If a reference was also a parameter, then we'll send as argument so it can all be done by __construct.
				$this->$class_name = new $mn(&$reference);
			}
			else
			{
				//There was an error.
				echo 'The variable for the intended module: <strong>' . $mn . '</strong> is not set, and therefor, this file cannot be loaded as one.';
			}
		}
		else
		{
			//That module does not exist.
			echo 'The module ' . $include . ' does not exist.';
		}
	}
	
	/**
	Unset variables and perform neccesary clean-up to free up memory.
	**/
	function cleanUp()
	{
		//Destroy this system class.
		unset($this);
	}
	
	/**
	Logs an error to the MySQL Database table with specific information on where the error was triggered,
	along with a message.
	**@param string The Error message
	**/
	function logError($lcontent)
	{
		//Build the query.
		$logerror = 'INSERT INTO `'. TBL_PREFIX .'errorlog` VALUES (\'\', \'' . $this->mysql->escape($lcontent) . '\', \'' . date('m/d/y H:i:s') . '\', \'' . $this->mysql->escape($_SERVER['PHP_SELF']) . '\')';
		//Execute the query
		mysql_query($logerror);
	}
	
	/**
	Queries the database to check if the user is logged in.
	**@returns integer User Level
	**/
	function loggedIn()
	{
		//We'll verify by using the $userinfo[] array which was set initially.
		//If the $userinfo['id'] value is greater than zero, then this means you are a valid user.
		if (isset($this->userinfo['id']) && $this->userinfo['id'] > 0)
		{
			//You're an authorized normal user, in this case.
			$return = 1;
			//If the integer value for your user ID specifies you're an admin, then the return value is set to 2.
			if ($this->userinfo['admin'] == 1)
			{
				//You're an authorized administrator, in this case.
				$return = 2;
			}
		}
		else
		{
			//The user is not a valid member, so in this case, we return a value of 0, which denotes the user is not logged in.
			$return = 0;
		}	
		//Return the user-level now.
		return $return;
	}
}
?>
