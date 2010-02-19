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
	The current page ID we are on.
	**@var integer
	**/
	var $page;
	
	/**
	The errors logged on the current page.
	**@var array
	**/
	var $errors;
	
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
		$this->file = array();
		$this->errors = array();
		$this->start = $start;
	}

	/**
	Set a user's template.
	
	**@returns string Template Name
	**/
	function setPage($page)
	{
		if (isset($page))
		{
			$settemplate_options = array('where' => 'page = \'' . $this->db->escape($page) . '\'');
		}
		else
		{
			$settemplate_options = array('where' => 'defaults = \'1\'');
		}
		//Run the query now.
		$settemplate = $this->db->select(TBL_PREFIX . 'pages', '*', $settemplate_options);
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
}
?>
