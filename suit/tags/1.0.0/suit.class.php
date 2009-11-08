<?php
/**
**@This program is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@This program is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class SUIT
{
	
	/**
	Chain of templates used as a check to prevent infinite loops.
	**@var array
	**/
	private $chain = array();
	
	/**
	The errors logged on the current page.
	**@var array
	**/
	private $errors = array();
	
	/**
	The loaded templates.
	**@var array
	**/
	private $loaded = array();
	
	/**
	Name of reference.
	**@var string
	**/
	private $name;

	/**
	Name of reference.
	**@var string
	**/
	private $returnname;
	
	/**
	Templates Folder
	**@var string
	**/
	public $templates;

	/**
	Constructor.
	**@param string Name of SUIT Reference
	**@param array Configuation Settings
	**/
	public function __construct($templates, $debug = false, $name = 'suit', $returnname = 'output')
  	{
		$this->templates = $templates;
		$this->name = $name; //We can now create a SUIT object and name it whatever we want.
		$this->returnname = $returnname;
		if (phpversion() <= '4.4.9')
		{
			//The PHP version is under 5, therefore, SUIT cannot run.
			trigger_error('SUIT Error #1. See http://www.suitframework.com/docs/error1/', E_USER_ERROR); //PHP Version must be greater than 4.4.9. 
		}
		elseif (!(is_dir($this->templates) && (is_writable($this->templates)) && is_dir($this->templates . '/content') && (is_writable($this->templates . '/content')) && is_dir($this->templates . '/code') && (is_writable($this->templates . '/code'))))
		{
			//File permissions for the writable folder are not properly set, or the directory for it does not exist.
			trigger_error('SUIT Error #2. See http://www.suitframework.com/docs/error2/', E_USER_ERROR); //Templates directory or it's required one of it's required subdirectories does not exist or is not CHMOD 777.
		}
		else
		{
			//Turn off register_globals().
			ini_set('register_globals', 0);
			//Turn off magic_quotes_runtime().
			set_magic_quotes_runtime(0);
			//If debug mode is on, then we will display all debug information if any errors/warnings/notices were to occur.
			if ($debug)
			{
				error_reporting(E_ALL);
			}
			else
			{
				error_reporting(0);
			}
		}
	}

	/**
	Destructor.
	**/
	public function __destruct()
	{
		unset($GLOBALS, $_POST, $_GET);
	}
	
	/**
	Include a file.
	**@param string Template
	**/
	public function checkFile($template, $create = false)
	{
		$return = array();
		$return['content'] = $this->templates . '/content/' . $template . '.tpl';
		if (!file_exists($return['content']) && $create)
		{
			//The tempate file does not exist, so we will create it and CHMOD it so it is writable by our script.
			touch($return['content']);
			chmod($return['content'], 0666);
		}
		if (file_exists($return['content']))
		{
			$return['code'] = $this->templates . '/code/' . $template . '.inc.php';	
			if (!file_exists($return['code']))
			{
				//The tempate file does not exist, so we will create it and CHMOD it so it is writable by our script.
				touch($return['code']);
				chmod($return['code'], 0666);
			}
			return $return;
		}
		//Return the path to the file now.
		return false;
	}

	/**
	Retrieves a template
	**@param string Content Output
	**@returns string The Template
	**/
	public function getTemplate($template)
	{
		$output = '';
		if (!in_array($template, $this->chain))
		{
			if (!array_key_exists($template, $this->loaded))
			{
				$filepath = $this->checkFile($template);
				if ($filepath)
				{
					$output = file_get_contents($filepath['content']);
					//Add the template to the chain.
					$this->chain[$template] = $template;
					//Include this template's file, and have the return value from includeFile() provide us with the $output which will not be manipulated.
					$output = $this->includeFile($filepath['code'], $output);
					//Remove the template from the chain.
					unset($this->chain[$template]);
					//Store the template and it's output into the loaded array.
					$this->loaded[$template] = $output;
				}
				else
				{
					//Template does not exist in the database.
					trigger_error('SUIT Error #3: ' . $template . '. See http://www.suitframework.com/docs/error3/', E_USER_WARNING); //Template Not Found. 
				}
			}
			else
			{
				//The template is in the array, so we'll load it from there.
				$output = $this->loaded[$template];
			}
		}
		else
		{
			//Template is in the chains array.
			trigger_error('SUIT Error #4: ' . $template . '. See http://www.suitframework.com/docs/error4/', E_USER_WARNING); //Infinite Loop. 
		}
		//Finally, give off the output.
		return $output;
	}
	
	/**
	Include a file.
	**@param string File Path
	**@param string Template Output
	**@returns string The Manipulated Output
	**/
	private function includeFile($filepath, $output)
	{
		//Allow them to use the SUIT object from a variable with the same name as $this->name.
		${$this->name} = &$this;
		${$this->returnname} = $output;
		//Include the file, of course.
		include $filepath;
		//Return the $output now that it has been manipulated.
		return ${$this->returnname};
	}
}
?>