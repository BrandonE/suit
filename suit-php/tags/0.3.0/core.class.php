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
class System
{
	/**
	The version we are running.
	**@var float
	**/
	var $version = 0.1;
	
	/**
	This is only for development environments. Make sure to switch this to 0 once in a production environment.
	**@var bool
	**/
	var $debug = true;
	
	/**
	Create the settings array, from which we will be loading the main system settings.
	**@var array
	**/
	var $settings = array();
	
	/**
	Load a module from a .class.php file
	**
	**@param string The file name
	**@param string The variable to define the object as.
	**/
	function loadModule($file_name, $class_name)
	{
		global $PATH_HOME;

		if (file_exists("{$PATH_HOME}modules/{$class_name}.class.php"))
		{
			require "{$PATH_HOME}modules/{$class_name}.class.php";
			
			/**
			$mn is the defined class name under which the object is created from. 
			We have to verify that it is properly set before creating an object.
			**/
			if (isset($mn) && class_exists($mn))
			{
				/**
				We can now call this object as: $system->class_name.
				**/
				$this->$class_name = new $mn;
			}
			else
			{
				/**
				Whoops, that didn't come out well.
				**/
				echo 'The variable <strong>$mn</strong> is not set, and therefor, this file cannot be loaded as a module.';
			}
		}
		else
		{
			echo "The module {$file_name} does not exist. in {$PATH_HOME}modules/{$class_name}.class.php";
		}
	}
	
	/**
	Unset variables and perform neccesary clean-up to free up memory.
	**/
	function cleanUp()
	{
		//Unset Globals
		unset($GLOBALS);
		//Finally, delete the system-not literally of course.
		unset($this);
	}
	
	/**
	Logs an error to the MySQL Database table.
	**@param string
	**/
	function logError($lcontent)
	{
		global $tbl_prefix;
		$query = 'INSERT INTO `'. $tbl_prefix .'errorlog` VALUES (\'\', \'' . mysql_real_escape_string($lcontent) . '\', \'' . date('m/d/y H:i:s') . '\', \'' . mysql_real_escape_string($_SERVER['PHP_SELF']) . '\')';
		
		mysql_query($query);
	}
	
	function loggedIn()
	{
		if (isset($_COOKIE['id']) && isset($_COOKIE['pass']))
		{
			global $system;
			
			$id = $system->mysql->escape($_COOKIE['id']);
			$pass = $system->mysql->escape($_COOKIE['pass']);
			
			$admincheck_options = array(
			'where' => 'id = \'' . $id . '\' AND password =\'' . $pass . '\''
			);
			
			$admincheck = $system->mysql->select(
			'users',
			'*',
			$admincheck_options
			);
			
			if ($admincheck)
			{
				//You're a user.
				$return = 1;
				
				while ($row = mysql_fetch_assoc($admincheck))
				{
					if ($row['admin'] == 1)
					{
						$return = 2;
					}
				}
				
				return $return;
			}
			else
			{
				//The user is not a member, so we return 0.
				$return = 0;
			}
			
			return $return;
		}
	}
}
?>
