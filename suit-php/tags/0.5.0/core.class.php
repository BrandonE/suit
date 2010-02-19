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
	**@var array
	**/
	var $userinfo = array();
	
	/**
	The current page ID we are on.
	**@var integer
	**/
	var $id;
	
	/**
	Set the user information and store it in an associative array for easier uses in the script.
	**/
	function setUserinfo()
	{
		//If the condition is met, then we'll set the userid and the password to the values matching the cookie in the database.
		if (!defined('ID') && !defined('PASS'))
		{
			//Begin with the user's cookies, first.
			if (isset($_COOKIE[COOKIE_PREFIX . 'id']) && isset($_COOKIE[COOKIE_PREFIX . 'pass']))
			{
				$id = intval($_COOKIE[COOKIE_PREFIX . 'id']);
				$pass = $this->mysql->escape($_COOKIE[COOKIE_PREFIX . 'pass']);
				//Query the database with the supplied information.
				$usercheck_options = array(
				'where' => 'id = \'' . $id . '\' AND password =\'' . $pass . '\''
				);
			
				$usercheck = $this->mysql->select(TBL_PREFIX . 'users', '*', $usercheck_options);
			
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
					setcookie(COOKIE_PREFIX . 'id', '', time()-3600, COOKIE_PATH, COOKIE_DOMAIN);
					setcookie(COOKIE_PREFIX . 'pass', '', time()-3600, COOKIE_PATH, COOKIE_DOMAIN);
				}
			}
			else
			{
				$this->userinfo['id'] = 0;
				$this->userinfo['password'] = '';
			}
			//Set the constants as the userinfo, once all of the checking is complete.
			define('ID', $this->userinfo['id']); //The User ID
			define('PASS', $this->userinfo['password']); //The password.
		}
	}
	/**
	Load a module from a .class.php file
	
	**@param string The module name, excluding the extension.
	**@param string The variable to create the object as.
	**/
	function loadModule($file_name, $class_name, &$reference='')
	{
		$include = PATH_HOME . 'modules/' . $class_name . '.class.php';
		
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
		//Cache The Page's Templates.
		if ($this->templates->recache)
		{
			$template = $this->templates->current_template;
			$filepath = PATH_HOME . 'cache/templates/' . $template . '';
			//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
			if (!is_dir($filepath))
			{
				mkdir($filepath);
				chmod($filepath, 0777);
			}
			//Concatanate the current path with to form the file.
			$filepath = $filepath . '/' . $this->setID . '.php';
			
			if (file_exists($filepath))
			{
				unlink($filepath);
			}
			//Looks like it doesn't. Let's create the missing file, and report the error to the user.
			$lcontent = $this->language->getLanguage('cantopenfile');
			$content = '<?php' . "\n" . '$templates = Array(';
			end($this->templates->loaded_template);
			$lastkey = key($this->templates->loaded_template);
			foreach ($this->templates->loaded_template as $key => $value)
			{
				$key = addslashes($key);
				$content .= '\'' . $key . '\'';
				if ($key != $lastkey)
				{
					$content .= ', ';
				}
			}
			$content .= ');' . "\n" . '?>';
			$file = fopen($filepath, 'w') or die($lcontent);
			fwrite($file, $content);
			fclose($file);
			chmod($filepath, 0666); //CHMOD the file to be writable by our script.
		}

		if ($this->language->recache)
		{
			//Cache The Page's Languages
			$language = $this->language->current_language_title;
			$filepath2 = PATH_HOME . 'cache/languages/' . $language . '';
			//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
			if (!is_dir($filepath2))
			{
				mkdir($filepath2);
				chmod($filepath2, 0777);
			}
			//Concatanate the current path with to form the file.
			$filepath2 = $filepath2 . '/' . $this->setID . '.php';
			
			if (file_exists($filepath2))
			{
				unlink($filepath2);
			}
			//Looks like it doesn't. Let's create the missing file, and report the error to the user.
			$lcontent = $this->language->getLanguage('cantopenfile');
			$content2 = '<?php' . "\n" . '$languages = Array(';
			end($this->language->loaded_language);
			$lastkey = key($this->language->loaded_language);
			foreach ($this->language->loaded_language as $key => $value)
			{
				$key = addslashes($key);
				$content2 .= '\'' . $key . '\'';
				if ($key != $lastkey)
				{
					$content2 .= ', ';
				}
			}
			$content2 .= ');' . "\n" . '?>';
			$file2 = fopen($filepath2, 'w') or die($lcontent);
			fwrite($file2, $content2);
			fclose($file2);
			chmod($filepath2, 0666); //CHMOD the file to be writable by our script.
		}
echo '<pre>'; print_r($this->mysql->query_list); echo '</pre>';
		//Destroy this system class.
		unset($this);
		unset($GLOBALS);
	}
	
	/**
	Logs an error to the MySQL Database table with specific information on where the error was triggered,
	along with a message.
	**@param string The Error message
	**/
	function logError($lcontent)
	{
		//Build the query.
		$logerror = 'INSERT INTO `'. TBL_PREFIX .'errorlog` VALUES (\'\', \'' . $this->mysql->escape($lcontent) . '\', \'' . date('m/d/y H:i:s') . '\', \'' . basename($_SERVER['SCRIPT_NAME']) . '\')';
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
