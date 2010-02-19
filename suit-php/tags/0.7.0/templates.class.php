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
class TemplateManagement
{	
	/**
	SUIT Reference
	**@var object
	**/
	var $suit;	
	
	/**
	The loaded templates.
	**@var array
	**/
	var $loaded;

	/**
	Chain
	**@var array
	**/
	var $chain;

	/**
	Variables
	**@var array
	**/
	var $vars;
	
	function __construct(&$reference)
	{
		//Set a value for the class variables.
		$this->suit = &$reference;
		$this->loaded = array();
		$this->chain = array();
		$this->vars = array();
	}
	
	function __deconstruct()
	{
		unset($loaded);
		unset($this->suit);
	}

	/**
	Include a file.
	**@param string Template Output
	**@param string Folder
	**/
	function checkFile($template, $folder)
	{
		$filepath = PATH_HOME . '/templates';
		//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
		if (!is_dir($filepath))
		{
			mkdir($filepath, 0777);
			chmod($filepath, 0777);
		}
		$filepath = $filepath . '/' . $folder;
		//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
		if (!is_dir($filepath))
		{
			mkdir($filepath, 0777);
			chmod($filepath, 0777);
		}
		//Concatanate the current path with to form the file.
		$filepath = $filepath . '/' . $template . '.inc';	
		if (!file_exists($filepath))
		{
			//Looks like it doesn't. Let's create the missing file.
			touch($filepath);
 			chmod($filepath, 0666);
		}
		return $filepath;
	}
	
	/**
	Include a file.
	**@param string File Path
	**@param string Template Output
	
	**@returns arrays All of the variables defined within the function.
	**/
	function executeCode($filepath, $output)
	{
		$suit = &$this->suit; //Append SUIT as a reference, thus, $suit instead of $this->suit
		//Include the file, of course.
		require $filepath;
		//Get the keys for the defined variables inside of the file.
		$vars = array_keys(get_defined_vars());
		foreach ($vars as $var_name)
		{
		    $this->vars[$var_name] = &$$var_name;
		}
		return $output;
	}
	
	/**
	Implodes values by concatenating from an array.
	@param array Values
	
	@returns string Imploded string
	**/
	function replace($string, $array)
	{
		$pos = array();
		$add = 0;
		foreach ($array as $key => $value)
		{
			if ($string != str_replace($value[0], $value[1], $string))
			{
				if(stripos($string, $value[0], 0) == 0)
				{
					$pos[0] = $key;
					$position = 0;
				}
				else
				{
					$position = -1;
				}
				while($position = stripos($string, $value[0], $position+1)) 
				{
					$pos[$position] = $key;
				}
			}
		}
		ksort($pos);
		foreach ($pos as $key => $value)
		{
			$length = strlen($array[$value][0]);
			$string = substr_replace($string, $array[$value][1], $key+$add, $length);
			$add += strlen($array[$value][1]) - strlen($array[$value][0]);
		}
		
		return $string;
	}

	/**
	Retrieves a template from the database.
	**@param string Template Title
	**/
	function getTemplate($template)
	{
		if (!in_array($template, $this->chain))
		{
			$this->chain[] = $template;
			if (!array_key_exists($template, $this->loaded))
			{
				// Select the template you are trying to grab that's parent is the template set associated with the theme.
				//We send the second parameter in the escape string so we may eliminate the backslashes for otherwise wildcard MySQL Characters
				$templates = $this->suit->db->escape($template, 0);
				$templatecheck_options = array('where' => 'title = \'' . $templates . '\'');
				$templatecheck = $this->suit->db->select(TBL_PREFIX . 'templates', 'content', $templatecheck_options);
				if ($templatecheck)
				{
					$row = mysql_fetch_assoc($templatecheck);
					$output = $row['content'];
					$pass = true;
					$parse = array();
					if ($output != '')
					{
						if (!(strstr('{', $output) == 0 || strstr('}', $output) == 0))
						{
							$pass = false;
						}
					}
					if ($pass)
					{
						$filepath = $this->checkFile($template, 'preload');                               
						$output = $this->executeCode($filepath, $output);
	
						//Match {expression_here} as templates
						preg_match_all('/\{((?:[^{}]*|(?R))*)\}/', $output, $parse, PREG_SET_ORDER);
				
						//Foreach() the template parsing array and run respective actions for them.
						foreach ($parse as $key => $value)
						{
							//Run the getTemplate() function while iterating through the array, and then store the output of the templates inside a 3-Dimensional array.
							$parse[$key][1] = $this->getTemplate($parse[$key][1]);
						}
						
						$output = $this->replace($output, $parse);
	
						$filepath = $this->checkFile($template, 'postload');
						$output = $this->executeCode($filepath, $output);
		
						//Store the template and it's output into the loaded_template array.
						$this->loaded[$template] = $output;
					}
					else
					{
						$output = 'Error: Illegal Content.';
						$this->suit->logError($output);
					}
				}
				else
				{
					//Looks like the template does not exist.
					$output = 'Error: Template ' . $template . ' not found.';
					//It's been output, but now let's log it.
					$this->suit->logError($output);	
				}
			}
			else
			{
				//The template is in the array, so we'll load it from there.
				$output = $this->loaded[$template];
			}
			foreach($this->chain as $key => $value)
			{
				if ($value == $template)
				{
					unset($this->chain[$key]);
				}
			}
		}
		else
		{
			//Looks like the template does not exist.
			$output = 'Error: Infinite Loop caused by ' . $template;
			//It's been output, but now let's log it.
			$this->suit->logError($output);
		}

		//Let's bring in the template now.
		return $output;
	}
}
$mn = 'TemplateManagement';
?>
