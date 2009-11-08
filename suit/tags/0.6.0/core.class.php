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
	var $version;

	/**
	The friendly version number we are running on.
	**@var integer
	**/
	var $version_friendly;
	
	/**
	Publicly display PHP Script errors inside the script? 
	This should only be set to 1 if you're in a development environment. Otherwise, this should be set to 0.
	**@var boolean
	**/
	var $debug;
	
	/**
	Storing user account information in an associative array.
	**@var array
	**/
	var $user;
	
	/**
	The current template set we are using.
	**@var string
	**/
	var $template;
	
	/**
	The current page ID we are on.
	**@var integer
	**/
	var $file;

	/**
	The current page ID we are on.
	**@var integer
	**/
	var $theme;

	/**
	The current page ID we are on.
	**@var integer
	**/
	var $language;
	
	/**
	The errors logged on the current page.
	**@var array
	**/
	var $errors;
	
	/**
	FTP Connection Stream
	**@var resource
	**/
	var $ftp_connection;
	
	/**
	Page Loading Start-time
	**@var float
	**/
	var $start;
	
	/**
	Page Loading Time
	**@var float
	**/
	var $loadtime;
	
	function __construct($debug = false, $start = '')
	{
		$this->version = 0.1;
		$this->version_friendly = 01;
		$this->debug = $debug; //Debugging by default is on, unless specified otherwise.
		$this->userinfo = array();
		$this->errors = array();
		$this->start = $start;
	}

	/**
	Set the user information and store it in an associative array for easier uses in the script.
	**/
	function setUser()
	{
		//Begin with the user's cookies, first.
		if (isset($_COOKIE[COOKIE_PREFIX . 'id']) && isset($_COOKIE[COOKIE_PREFIX . 'pass']))
		{
			$id = intval($_COOKIE[COOKIE_PREFIX . 'id']);
			$pass = $this->db->escape($_COOKIE[COOKIE_PREFIX . 'pass']);
			//Query the database with the supplied information.
			$usercheck_options = array('where' => 'id = \'' . $id . '\' AND password =\'' . $pass . '\'');
			$usercheck = $this->db->select(TBL_PREFIX . 'users', '*', $usercheck_options);
			
			if ($usercheck)
			{
				$return = mysql_fetch_assoc($usercheck);
			}
			else
			{
				//The user was not found. You're a guest, and therefor, and you have a userid of 0. Your password is blank as well.
				$return['id'] = 0;
				$return['password'] = '';
				//Delete the cookies now. They are useless.
				setcookie(COOKIE_PREFIX . 'id', '', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				setcookie(COOKIE_PREFIX . 'pass', '', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
			}
		}
		else
		{
			$return['id'] = 0;
			$return['password'] = '';
		}
		return $return;
	}

	/**
	Set a user's template.
	
	**@returns string Template Name
	**/
	function setFile($id)
	{
		$settemplate_options = array('where' => 'id = \'' . $id . '\'');
		//Run the query now.
		$settemplate = $this->db->select(TBL_PREFIX . 'files', '*', $settemplate_options);
		if ($settemplate)
		{
			$return = mysql_fetch_array($settemplate);
		}
		else
		{
			$return = 0;
		}
		
		return $return;
	}

	/**
	Set a user's theme based on the database value
	@returns integer Theme ID
	**/
	function setTheme()
	{
		if (isset($this->user['id']) && isset($this->user['theme']) && ($this->user['theme'] > 0))
		{
			//You have a theme. Now we'll run a query to select the specified theme.
			$theme = $this->user['theme'];
			$themecheck_options = array('where' => 'id = \'' . $theme . '\'');
		}
		else
		{
			//You must be a guest. We'll give you defaults then.
			$themecheck_options = array('where' => 'defaults = \'1\'');
		}
		
		//Run the query now.
		$themecheck = $this->db->select(TBL_PREFIX . 'themes', '*', $themecheck_options);
		//Return the query
		if ($themecheck)
		{
			$return = mysql_fetch_array($themecheck);
		}
		else
		{
			$return = 0;
		}
		//Return the theme id.
		return $return;
	}

	function setTemplate()
	{
		//Gets the id and title of the template associated with this.
		$template_options = array('where' => 'id = \'' . $this->theme['templateset'] . '\'');
		$template = $this->db->select(TBL_PREFIX . 'templates', 'id, title', $template_options);		
		if ($template)
		{
			$return = mysql_fetch_assoc($template);
		}
		else
		{
			$return = 0;
		}
		return $return;
	}

	/**
	Set a user's theme.
	**/
	function setLanguage()
	{
		if (isset($this->user['id']) && isset($this->user['language']) && ($this->user['language'] > 0))
		{
			$language = $this->user['language'];
			$setlanguage_options = array('where' => 'id = \'' . $language . '\'');
		}
		else
		{
			$setlanguage_options = array('where' => 'defaults = \'1\'');
		}
		
		$setlanguage = $this->db->select(TBL_PREFIX . 'languages', 'id, title', $setlanguage_options);
		
		if ($setlanguage)
		{
			while ($row = mysql_fetch_array($setlanguage))
			{
				//Create a return value.
				$return = $row;
			}
		}
		else
		{
			$return = 0;
		}
		return $return;
	}

	/**
	Load a module from a .class.php file
	
	**@param string The module name, excluding the extension.
	**@param string The variable to create the object as.
	**/
	function loadModule($file_name, $class_name, &$reference='')
	{
		$include = PATH_HOME . '/modules/' . $file_name . '.class.php';
		
		if (file_exists($include))
		{
			//Let's load the class file.
			include $include;
			
			//$mn is the defined class name under which the object is created from. 
			//We have to verify that it is properly set before creating an object.
			if (isset($mn) && class_exists($mn))
			{
				//We can now call this object as: $this->class_name.
				//If a reference was also a parameter, then we'll send as argument so it can all be done by __construct.
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
	Perform necessary tasks after output has been sent to the user. This is the final function executed before the entire system class is destroyed along with everything under it.
	
	**@returns none
	**/
	function cleanUp()
	{
		//Page load ending time.
		$endtime = microtime();
		$endtime = explode(' ', $endtime);
		$endtime = $endtime[1] + $endtime[0];
		$endtime = $endtime - $this->start;
		$this->load = round($endtime, 4);
		//Destroy this system class, and any globals (most likely not)
		unset($this->errors);
		unset($this);
		unset($GLOBALS);
	}
	
	/**
	Logs an error to the MySQL Database table with specific information on where the error was triggered, along with a message.
	**@param string The Error message
	
	**@returns none
	**/
	function logError($lcontent)
	{
		$lcontent = $this->db->escape($lcontent);
		$location = basename($_SERVER['SCRIPT_NAME']);
		$date = date('m/d/y H:i:s');

		//Build the query.
		$logerror_fields = array('content', 'date', 'location');
		$logerror_values = array($lcontent, $date, $location);
		$logerror = $this->db->insert(TBL_PREFIX . 'errorlog', $logerror_fields, $logerror_values);
		
		$insertid = mysql_insert_id(); //Add current insert ID over to an array.
		
		if (!$logerror)
		{
			echo 'Could not log error.';
		}
	}
	
	/**
	Queries the database to check if the user is logged in.
	
	**@returns integer User Level
	**/
	function loggedIn()
	{
		//We'll verify by using the $user[] array which was set initially.
		//If the $user['id'] value is greater than zero, then this means you are a valid user.
		if (isset($this->user['id']) && $this->user['id'] > 0)
		{
			//You're an authorized normal user, in this case.
			$return = 1;
			//If the integer value for your user ID specifies you're an admin, then the return value is set to 2.
			if ($this->user['admin'] == 1)
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
