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
	public $chain = array();

	/**
	Filepath
	**@var string
	**/
	private $filepath;

	/**
	Name of content reference.
	**@var string
	**/
	private $content;
	
	/**
	Name of suit reference.
	**@var string
	**/
	private $suit;
	
	/**
	Templates Folder
	**@var string
	**/
	public $templates;

	/**
	Variables
	**@var array
	**/
	public $vars = array();

	/**
	Constructor.
	**@param string Name of SUIT Reference
	**@param string SUIT Reference Name
	**@param string Content Reference Name
	**/
	public function __construct($templates, $suit = 'suit', $content = 'content')
  	{
		$this->templates = $templates;
		$this->suit = $suit;
		$this->content = $content;
		if (phpversion() <= '4.4.9')
			//The PHP version is under 5, therefore, SUIT may not run.
			$this->error('SUIT Error: PHP Version must be greater than 4.4.9. See http://www.suitframework.com/docs/error1/');
		elseif (!is_dir($this->templates) || !is_dir($this->templates . '/content') || !is_dir($this->templates . '/code') || !is_dir($this->templates . '/glue'))
			//File permissions for the writable folder are not properly set, or the directory for it does not exist.
			$this->error('SUIT Error: Templates directory or it\'s required one of it\'s required subdirectories does not exist. See http://www.suitframework.com/docs/error4/');
	}

	/**
	Display an Error.
	**@param string Error Message
	**@param string Type of Error
	**@returns none
	**/
	private function error($error, $type = 'Error')
	{
		$backtrace = debug_backtrace();
		echo '<br />
<b>SUIT ' . $type . '</b>:  ' . $error . ' in <b>' . $backtrace[1]['file'] . '</b> on line <b>' . $backtrace[1]['line'] . '</b><br />';
		if ($type == 'Error')
			exit;
	}

	/**
	Retrieves a template
	**@param string Content
	**@returns string The Template
	**/
	public function getTemplate($template)
	{
		$template = str_replace('../', '', strval($template));
		$return = '';
		//A chain is used to prevent infinite loops. If this template is not in the chain array, then continue.
		if (!in_array($template, $this->chain))
			//The glue is the method of linking template content and code together. If this glue .txt file does not exist, then the template does not as well.
			if (file_exists($this->filepath = $this->templates . '/glue/' . $template . '.txt'))
			{
				$array = explode('=', file_get_contents($this->filepath));
				$array = $this->glueEscape($array);
				//The first explode result element is the content file. If the key exists, continue.
				if (isset($array[0]) && ($array[0]))
				{
					//If the file exists, continue.
					if (file_exists($this->filepath = $this->templates . '/content/' . str_replace('../', '', $array[0]) . '.tpl'))
						//Store the content in a variable.
						$return = file_get_contents($this->filepath);
					unset($array[0]);
				}
				//Iterate through the code files.
				foreach ($array as $value)
					if ($value)
						//If the file exists, continue.
						if (file_exists($this->filepath = $this->templates . '/code/' . str_replace('../', '', $value) . '.inc.php'))
						{
							//Add the template to the chain.
							$this->chain[$template] = $template;
							//Include the file, and have the return value from includeFile() provide us with the $return which has been manipulated by the code.
							$return = $this->includeFile($return);
							//Remove the template from the chain.
							unset($this->chain[$template]);
						}
			}
			else
				//Template does not exist.
				$this->error('Template ' . $template . ' Not Found. See http://www.suitframework.com/docs/error2/', 'Warning');
		else
			//Template is in the chains array. Stop the infinite loop.
			$this->error('Infinite Loop Caused by ' . $template . '. See http://www.suitframework.com/docs/error3/', 'Warning');
		//Finally, give off the content.
		return $return;
	}

	/**
	Escape the Glue File
	**@param array Exploded File
	**@returns array Corrected Array
	**/
	public function glueEscape($return)
	{
		foreach ($return as $key => $value)
			if (isset($return[$key]))
				do
				{
					$count = 0;
					while (isset($return[$key][strlen($return[$key]) - ($count + 1)]) && ($return[$key][strlen($return[$key]) - ($count + 1)] == '\\'))
						$count++;
					$condition = $count % 2;
					if ($condition)
						$count++;
					if ($count)
						$return[$key] = substr($return[$key], 0, -($count / 2));
					if ($condition)
					{
						$return[$key] = $return[$key] . '=' . $return[$key+1];
						unset($return[$key+1]);
					}
				}
				while ($condition);
		return $return;
	}

	/**
	Include a file.
	**@param string Template Content
	**@returns string The Manipulated Content
	**/
	private function includeFile($content)
	{
		//Allow them to use the SUIT object from a variable with the same name as $this->suit.
		${$this->suit} = &$this;
		//Allow them to use the return value from a variable with the same name as $this->content.
		${$this->content} = $content;
		//Include the file.
		include $this->filepath;
		//Return the $content now that it has been manipulated.
		return ${$this->content};
	}
}
?>