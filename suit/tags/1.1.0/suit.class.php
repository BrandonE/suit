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
	Array gluing the content and code together.
	**@var array
	**/
	private $glue = array();
	
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
	public function __construct($templates, $name = 'suit', $returnname = 'output')
  	{
		$this->templates = $templates;
		$this->name = $name; //We can now create a SUIT object and name it whatever we want.
		$this->returnname = $returnname;
		if (phpversion() <= '4.4.9')
		{
			//The PHP version is under 5, therefore, SUIT may not run.
			trigger_error('SUIT Error: PHP Version must be greater than 4.4.9. See http://www.suitframework.com/docs/error1/', E_USER_ERROR);
		}
		elseif (!is_dir($this->templates) || !is_dir($this->templates . '/content') || !is_dir($this->templates . '/code') || !is_dir($this->templates . '/glue'))
		{
			//File permissions for the writable folder are not properly set, or the directory for it does not exist.
			trigger_error('SUIT Error: Templates directory or it\'s required one of it\'s required subdirectories does not exist. See http://www.suitframework.com/docs/error4/', E_USER_ERROR);
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
	Retrieves a template
	**@param string Content Output
	**@returns string The Template
	**/
	public function getTemplate($template)
	{
		$output = '';
		//A chain is used to prevent infinite loops. If this template is not in the chain array, then continue.
		if (!in_array($template, $this->chain))
		{
			//Let's not repeat this step if the template was already loaded.
			if (!array_key_exists($template, $this->loaded))
			{
				$filepath = $this->templates . '/glue/' . $template . '.txt';
				//The glue is the method of linking template content and code together.
				//If this glue .txt file does not exist, then the template does not as well.
				if (file_exists($filepath))
				{
					$array = explode('=', file_get_contents($filepath), 2);
					//The first explode result element is the content file. If the key exists, continue.
					if (isset($array[0]))
					{
						$filepath = $this->templates . '/content/' . $array[0] . '.tpl';
						//If the file exists, continue.
						if (file_exists($filepath))
						{
							//Store the content in a variable.
							$output = file_get_contents($filepath);
						}
					}
					//The second explode result element is the code file. If the key exists, continue.
					if (isset($array[1]))
					{
						$filepath = $this->templates . '/code/' . $array[1] . '.inc.php';
						//If the file exists, continue.
						if (file_exists($filepath))
						{
							//Add the template to the chain.
							$this->chain[$template] = $template;
							//Include the file, and have the return value from includeFile() provide us with the $output which has been manipulated by the code.
							$output = $this->includeFile($filepath, $output);
							//Remove the template from the chain.
							unset($this->chain[$template]);
						}
					}
					//Store the template and it's output into the loaded array.
					$this->loaded[$template] = $output;
				}
				else
				{
					//Template does not exist.
					trigger_error('SUIT Error: Template ' . $template . ' Not Found. See http://www.suitframework.com/docs/error2/', E_USER_WARNING);
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
			//Template is in the chains array. Stop the infinite loop.
			trigger_error('SUIT Error: Infinite Loop Caused by ' . $template . '. See http://www.suitframework.com/docs/error3/', E_USER_WARNING);
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
		//Allow them to use the return value from a variable with the same name as $this->returnname.
		${$this->returnname} = $output;
		//Include the file.
		include $filepath;
		//Return the $output now that it has been manipulated.
		return ${$this->returnname};
	}
}
?>