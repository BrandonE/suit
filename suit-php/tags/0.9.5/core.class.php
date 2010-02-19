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
	The current page ID we are on.
	**@var integer
	**/
    	var $page;

	/**
	The errors logged on the current page.
	**@var array
	**/
	var $errors = array();
	
	/**
	Name of reference
	**@var string
	**/
    	var $name;

	function __construct($name)
  	{
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
			'postgresql',
			'flatfile'
		);
		if (!(defined('DB_TYPE') && defined('DB_HOST') && defined('DB_NAME') && defined('DB_PASS') && defined('DB_PORT') && defined('DB_PREFIX') && defined('FLAG_DEBUG') && defined('PATH_HOME') && defined('PATH_WRITABLE')))
		{
			print 'SUIT Error #2. See http://www.suitframework.com/docs/error2/';
			exit;
		}
		elseif (phpversion() <= '4.4.9')
		{
			print 'SUIT Error #3. See http://www.suitframework.com/docs/error3/';
			exit;
		}
		elseif (ini_get('register_globals'))
		{
			print 'SUIT Error #4. See http://www.suitframework.com/docs/error4/';
			exit;
		}
		elseif (!(is_dir(PATH_WRITABLE) && (substr(sprintf('%o', fileperms(PATH_WRITABLE)), -4) == '0777')))
		{
			print 'SUIT Error #5. See http://www.suitframework.com/docs/error5/';
			exit;
		}
		elseif (!in_array(DB_TYPE, $rdbms))
		{
			print 'SUIT Error #6. See http://www.suitframework.com/docs/error6/';
			exit;
		}
		else
		{
			set_magic_quotes_runtime(0);
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
				$query = 'SELECT * FROM ' . DB_PREFIX . 'pages WHERE title = \'' . $this->db->escape($page) . '\'';
			}
			else
			{
				$query = 'SELECT * FROM ' . DB_PREFIX . 'pages WHERE defaults = \'1\'';
			}
			//Run the query now.
			$check = $this->db->query($query);
			if ($check)
			{
				$this->page = mysql_fetch_array($check);
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
				print 'SUIT Error #7. See http://www.suitframework.com/docs/error7/';
			}
		}
		else
		{
			//That module does not exist.
			print 'SUIT Error #8: ' . $include . '. See http://www.suitframework.com/docs/error8/';
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
	Logs an error to the Database table with specific information on where the error was triggered, along with a message.
	**@param string The Error message
	**@returns none
	**/
	function logError($lcontent)
	{
		$lcontent = $this->db->escape($lcontent);
		$location = $_SERVER['REQUEST_URI'];
		//Build the query.
		if (!in_array($lcontent, $this->errors))
		{
			$query = 'INSERT INTO ' . DB_PREFIX . 'errorlog (content, time, location) VALUES(\'' . $lcontent . '\', \'' . mktime() . '\', \'' . $location . '\')';
			$check = mysql_query($query);
			if ($check)
			{
				$this->errors[] = $lcontent; //So we update for the next time.
			}
			else
			{
				print 'SUIT Error #8. See http://www.suitframework.com/docs/error8/';
			}
		}
	}
	
	/**
	Convert Line Breaks
	**@param string code
	**@param string source
	**@returns string Converted Code
	**/	
	function breakConvert($code, $source)
	{
		if (stristr($source, 'WIN'))
		{
			$char = "\r\n";
		}
		elseif (stristr($source, 'LIN'))
		{
			$char = "\n";
		}
		elseif (stristr($source, 'MAC'))
		{
			$char = "\r";
		}
		else
		{
			$char = "\n";
		}
		return $char;
	}
}
?>
