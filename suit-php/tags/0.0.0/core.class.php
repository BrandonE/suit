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

		if (file_exists("{$PATH_HOME}inc/modules/{$class_name}.class.php"))
		{
			require "{$PATH_HOME}inc/modules/{$class_name}.class.php";
			
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
				echo 'A module with the title '.$mn.' was not defined as a class.';
			}
		}
		else
		{
			echo "The module {$file_name} does not exist. in {$PATH_HOME}inc/modules/{$class_name}.class.php";
		}
	}
	
	/**
	Query the database for settings.
	**/
	function loadSettings()
	{
		/*
		Query the MySQL database for 
		*/
		$settingscheck = $system->mysql->select(
		'settings',
		'*'
		);
		
		if ($settingscheck)
		{
			while ($row = mysql_fetch_assoc($settingscheck))
			{
				/*
				Using the while() loop, store the rows in an array so they may be called as $system->settings->key.
				*/
				$this->settings[$row['key']] = $row['value'];
			}
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
}
?>