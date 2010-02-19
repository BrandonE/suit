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
	Name of reference
	**@var string
	**/
    var $name;

	function __construct($name)
  	{
		$this->version = 0.1;
		$this->version_friendly = 01;
		$this->errors = array();
		$this->name = $name;
	}

	/**
	Initialize
	**@param int page id.
	**/
	function init($page)
	{
		$rdbms = array
		(
			'mysql',
			'postgresql'
		);
		if (!(defined('DB_TYPE') && defined('DB_HOST') && defined('DB_NAME') && defined('DB_PASS') && defined('DB_PORT') && defined('DB_PREFIX') && defined('FLAG_DEBUG') && defined('PATH_HOME') && defined('PATH_TEMPLATES')))
		{
			echo 'Constants not properly defined';
			exit;
		}
		elseif (phpversion() <= '4.4.9')
		{
			echo 'PHP Version must be greater than 4.4.9';
			exit;
		}
		elseif (ini_get('register_globals'))
		{
			echo 'Register Globals MUST be disabled';
			exit;
		}
		elseif (!(is_dir(PATH_TEMPLATES) && (substr(sprintf('%o', fileperms(PATH_TEMPLATES)), -4) == '0777')))
		{
			echo 'Templates Folder does not exist or is not CHMOD 777';
			exit;
		}
		elseif (!in_array(DB_TYPE, $rdbms))
		{
			echo 'There is no RDBMS specified, or the RDBMS is not supported by SUIT';
			exit;
		}
		else
		{
			//If debug mode is on, then we'll show all errors that go on in the coding.
			if (FLAG_DEBUG)
			{
				error_reporting(E_ALL);
			}
			else
			{
				error_reporting(0);
			}
			//Load the vital modules required for the basic functionality of SUIT.
			$this->loadModule('templates', 'templates', $this);
			$this->loadModule('db', 'db');
			//Connect to the database, specifying what is in the configuration file.
			$this->db->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($page != '')
			{
				$settemplate_options = array
				(
					'where' => 'title = \'' . $this->db->escape($page) . '\''
				);
			}
			else
			{
				$settemplate_options = array
				(
					'where' => 'defaults = \'1\''
				);
			}
			//Run the query now.
			$settemplate = $this->db->select(DB_PREFIX . 'pages', '*', $settemplate_options);
			if ($settemplate)
			{
				$this->page = mysql_fetch_array($settemplate);
			}
			else
			{
				$this->page = 0;
			}
		}
	}

	/**
	Load a module from a .class.php file
	**@param string The module name, excluding the extension.
	**@param string The variable to create the object as.
	**/
	function loadModule($file_name, $class_name, &$reference='')
	{
		$include = PATH_HOME . '/' . $file_name . '.class.php';
		if (file_exists($include))
		{
			//Let's load the class file.
			include $include;

			//$mn is the defined class name under which the object is created from. 
			//We have to verify that it is properly set before creating an object.
			//Plus, we should make sure that it is not set.
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
		$this->db->close();
		//Destroy this system class, and any globals (most likely not)
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
		$location = basename($_SERVER['REQUEST_URI']);
		if (strpos($location, '?') !== false)
		{
			$location = reset(explode('?', $location));
		}
		$date = date('m/d/y H:i:S');
		//Build the query.
		if (!in_array($lcontent, $this->errors))
		{
			$logerror_fields = array('content', 'date', 'location');
			$logerror_values = array($lcontent, $date, $location);
			$logerror = $this->db->insert(DB_PREFIX . 'errorlog', $logerror_fields, $logerror_values);
			if ($logerror)
			{
				$this->errors[] = $lcontent; //So we update for the next time.
			}
			else
			{
				echo 'Could not log error.';
			}
		}
	}
}
?>