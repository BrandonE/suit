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
	This is only for development environments. Make sure to switch this to 0 once in a production environment.
	**@var boolean
	**/
	var $debug = true;
	
	/**
	Storing user account information in the array.
	**@var 
	**/
	var $userinfo = array();
	
	/**
	Load a module from a .class.php file
	**
	**@param string The module name, excluding the extension.
	**@param string The variable to create the object as.
	**/
	function loadModule($file_name, $class_name)
	{
		$include = "" . PATH_HOME . "modules/{$class_name}.class.php";
		
		if (file_exists($include))
		{
			include $include;
			
			//$mn is the defined class name under which the object is created from. 
			//We have to verify that it is properly set before creating an object.
			if (isset($mn) && class_exists($mn))
			{
				//We can now call this object as: $this->class_name.
				$this->$class_name = new $mn;
			}
			else
			{
				//Whoops, that didn't come out well.
				echo 'The variable for the intended module: <strong>' . $mn . '</strong> is not set, and therefor, this file cannot be loaded as one.';
			}
		}
		else
		{
			echo 'The module ' . $include . ' does not exist.';
		}
	}
	
	/**
	Unset variables and perform neccesary clean-up to free up memory.
	**/
	function cleanUp()
	{
		//Unset all variables inside of the global scope.
		//The unset() function is called twice due to the zend_hash_del_key_or_index hole in PHP <4.4.3 and <5.1.4
		unset($GLOBALS);
		unset($GLOBALS);
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
		//Begin with the user's cookies, first.
		if (isset($_COOKIE['id']) && isset($_COOKIE['pass']))
		{
			//If the condition is met, then we'll set the userid and the password hash as a variable, along with properly filtering the data.
			$id = intval($_COOKIE['id']);
			$pass = $this->mysql->escape($_COOKIE['pass']);
			
			//Query the database with the supplied information.
			$usercheck_options = array(
			'where' => 'id = \'' . $id . '\' AND password =\'' . $pass . '\''
			);
			
			$usercheck = $this->mysql->select('users', '*', $usercheck_options);
			
			if ($usercheck)
			{
				//If the $usercheck succeeded, the return value is set to 1.
				//You're an authorized normal user, in this case.
				$return = 1;
				
				//Initiate a wh
				while ($row = mysql_fetch_assoc($usercheck))
				{
					//If the database integer value for your user ID specifies you're an admin, then the return value is set to 2.
					//You're an authorized administrator, in this case.
					if ($row['admin'] == 1)
					{
						$return = 2;
					}
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
}
?>
