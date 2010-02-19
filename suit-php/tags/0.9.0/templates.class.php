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
	Chain of templates used as a check to prevent infinite loops.
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
		//Set a values for the class variables.
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
	function checkFile($template)
	{
		//If PATH_TEMPLATES is not a directory, this won't work.
		if (!is_dir(PATH_TEMPLATES))
		{
			return false;
		}
		else
		{
			//Concatanate the current path with to form the file.
			$filepath = PATH_TEMPLATES . '/' . $template . '.inc.php';	
			if (!file_exists($filepath))
			{
				//Looks like it doesn't. Let's create the missing file.
				touch($filepath);
	 			chmod($filepath, 0666);
			}
			return $filepath;
		}
	}
	
	/**
	Include a file.
	**@param string File Path
	**@param string Template Output
	
	**@returns arrays All of the variables defined within the function.
	**/
	function includeFile($filepath, $output)
	{
		//Append SUIT as a reference, thus, $suit instead of $this->suit
		${$this->suit->name} = &$this->suit;
		//Include the file, of course.
		require $filepath;
		return $output;
	}

	/**
	Retrieves a template from the database.
	**@param string Template Title
	**/
	function getTemplate($template)
	{
		//This will only return false if an infinite loop occurs.
		if (!in_array($template, $this->chain))
		{
			if (!array_key_exists($template, $this->loaded))
			{
				$templatecheck_options = array
				(
					'where' => 'title = \'' . $this->suit->db->escape($template, 0) . '\''
				);
				$templatecheck = $this->suit->db->select(DB_PREFIX . 'templates', 'content', $templatecheck_options);
				//If the template can be found in the database.
				if ($templatecheck)
				{
					$row = mysql_fetch_assoc($templatecheck);
					$output = $row['content'];
					$filepath = $this->checkFile($template);
					if ($filepath != false)
					{
						//Add the template to the chain.
						$this->chain[$template] = $template;
						//Include this template's file.
						$output = $this->includeFile($filepath, $output);
						//Remove the template from the chain.
						unset($this->chain[$template]);
					}
					else
					{
						$this->suit->logError('Error: Templates Directory Not Found');
					}
					//Store the template and it's output into the loaded array.
					$this->loaded[$template] = $output;
				}
				else
				{
					$output = 'Error: Template ' . $template . ' not found.';
					$this->suit->logError($output);	
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
			$output = 'Error: Infinite Loop caused by ' . $template;
			$this->suit->logError($output);
		}
		return $output;
	}
}
$mn = 'TemplateManagement';
?>