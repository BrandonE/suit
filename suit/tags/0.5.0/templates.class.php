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
	The current user's theme.
	**@var integer
	**/
	var $current_theme;
	
	/**
	The currently loaded temmplates.
	**@var array
	**/
	var $loaded_template = array();

	/**
	Content of cached columns
	**@var array
	**/
	var $rows = array();

	/**
	Whether or not to recache
	**@var bool
	**/
	var $recache = false;
	
	function __construct(&$reference)
	{
		$this->suit =& $reference;
	}
	
	function __deconstruct()
	{
		unset($loaded_template);
		unset($suit);
	}
	
	/**
	Include a file.
	**@param string File Path
	**@param int The Layer
	**@param
	**/
	function include_file($path, $output, $rows)
	{
		$suit = &$this->suit; //We don't want to give too much trouble for dynamic templates, so we will let them use $suit.
		//Include the file.
		include $path;
		return $output;
	}

	/**
	Set a user's template based on file id
	
	@returns string Template Name
	**/
	function setTemplate()
	{
		$filecheck_options = array('where' => 'id = \'' . $this->suit->setID . '\'');
		$filecheck = $this->suit->mysql->select(TBL_PREFIX . 'files', '*', $filecheck_options);
		if ($filecheck)
		{
			while ($row = mysql_fetch_assoc($filecheck))
			{
				return $row['template'];
			}
		}
		else
		{
			return 0;
		}
	}
		
	/**
	Set a user's theme based on the database value
	@returns integer Theme ID
	**/
	function setTheme()
	{
		if (isset($this->suit->userinfo['id']) && isset($this->suit->userinfo['theme']))
		{
			$theme = $this->suit->userinfo['theme'];
		}
		else
		{
			$theme = 0;
		}
		
		//If the theme ID is not set, then select the default one. Otherwise, select a theme specified by the user.
		if ($theme == 0)
		{
			$themecheck_options = array('where' => 'defaults = \'1\'');
		}
		else
		{
			$themecheck_options = array('where' => 'id = \'' . $theme . '\'');
		}
		
		//Run the query now.
		$themecheck = $this->suit->mysql->select(TBL_PREFIX . 'themes', '*', $themecheck_options);
		//Return the query
		if ($themecheck)
		{
			while ($row = mysql_fetch_assoc($themecheck))
			{
				$return = $row['id'];
				//Let's get the title of the template associated with this.
				$template_options = array('where' => 'id = \'' . $return . '\'');
				$template = $this->suit->mysql->select(TBL_PREFIX . 'templates', 'title', $template_options);
				
				if ($template)
				{
					//Fetch Associative array for it.
					while ($row2 = mysql_fetch_assoc($template))
					{
						//Set the current template with the chosen field value.
						$this->current_template = $row2['title'];
					}
				}
			}
		}
		else
		{
			$return = 0;
		}
		
		return $return;
	}
	
	/**
	Implodes values by concatenating from an array.
	@param array Values
	
	@returns string Imploded string
	**/
	function implosion($string, $array)
	{	
		//Pre-set arrays
		$exploded = array();
		$done = array();
		//foreach() Supplied argument array in order to grab the separator (|)
		foreach ($array as $key => $value)
		{
			if (!(in_array($array[$key][0], $done)))
			{
				$string = str_replace($array[$key][0], '|' . $array[$key][0] . '|', $string);
				$done[] .= $array[$key][0];
			} 
		}
		
		//Separate each value by creating an array from the ~ separator.
		$string = explode('|', $string);
		
		//Add the content to an $exploded[] array now.
		foreach ($string as $value)
		{
			//If it's not an empty value, then confirm the adding.
			if (!empty($value))
			{
				$exploded[] .= $value;
			}
		}
										
		foreach($exploded as $key => $value)
		{
			foreach ($array as $key2 => $value2)
			{
				if ($exploded[$key] == $array[$key2][0])
				{
					$exploded[$key] = $array[$key2][1];
				}
			}
		}
		
		//Implode the string, and return it.
		$string = implode($exploded);
		
		return $string;
	}

	/**
	Retrieves a template from the database.
	**@param string Template Title
	**@param array Rows
	**/
	function getTemplate($template)
	{
		//Pre-set the output.
		$output = '';
		//If the current theme was valid, then proceed.
		if ($this->current_theme)
		{
			if (!array_key_exists($template, $this->loaded_template))
			{
				if (!(isset($this->rows[$template]['id'])))
				{
					// Select the template you are trying to grab that's parent is the template set associated with the theme.
					//We send the second parameter in the escape string so we may eliminate the backslashes for otherwise wildcard MySQL Characters
					$templates = $this->suit->mysql->escape($template, 0);
					$templatecheck_options = array('where' => 'title = \'' . $templates . '\' AND parent = \'' . $this->current_theme . '\'');
					$templatecheck = $this->suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
					if ($templatecheck)
					{
						$rows = array();
						$row = mysql_fetch_assoc($templatecheck);
						foreach ($row as $key => $value)
						{
							$rows[$row['title']][$key] = $value;
						}
						$output = $this->parseTemplate($row['title'], $rows);
					}
					else
					{
						//Looks like the template does not exist.
						$lcontent = $this->suit->language->getLanguage('templatenotfound');
						$lcontent = str_replace('{1}', $template, $lcontent);
						$output = $lcontent;
						//It's been output, but now let's log it.
						$this->suit->logError($lcontent);
					}
				}
				else
				{
					$output = $this->parseTemplate($template, $this->rows);
				}
				//Store the template and it's output into the loaded_template array.
				$this->loaded_template[$template] = $output;
				$this->recache = true;
			}
			else
			{
				//The template is in the array, so we'll load it from there.
				$output = $this->loaded_template[$template];
			}
		}
		else
		{
			//Looks like the template does not exist.
			$output = $this->suit->language->getLanguage('themenotfound');
			//It's been output, but now let's log it.
			$this->suit->logError($output);
		}
		//Let's bring in the template now.
		return $output;
	}

        /**
	Parse Template
	**@param string Template Title
	**@param array Rows
	**/
	function parseTemplate($template, $rows)
	{
		//Pre-set the output.
		$output = '';
		//Pre-define the parsing array.
		$parse = array();
		$output .= $rows[$template]['content'];
		
		if (!$rows[$template]['dynamic'])
		{
			//Match {expression_here} as templates
			preg_match_all('/\{((?:[^{}]*|(?R))*)\}/', $output, $parse['templates'], PREG_SET_ORDER);
		}
		//Match [expression_here] as languages.
		preg_match_all('/\[((?:[^\[\]]*|(?R))*)\]/', $output, $parse['language'], PREG_SET_ORDER);
		
		//Let's foreach() through the $parse array, which contains all of the parsed templates.
		foreach ($parse as $key => $value)
		{
			//Using the $parse array, we'll go ahead and attempt to evaluate and see if any illegal formatting has been parsed.
			foreach ($parse[$key] as $key2 => $value2)
			{
				//All of the possible illegal conditions that should be catched. 
				//The beginning of the symbols is catched in one array, and the ending is catched in a second one.
				preg_match_all('/\{/mi', $parse[$key][$key2][1], $parse['testtemplates'], PREG_SET_ORDER);
				preg_match_all('/\}/mi', $parse[$key][$key2][1], $parse['testtemplates2'], PREG_SET_ORDER);
				preg_match_all('/\[/mi', $parse[$key][$key2][1], $parse['testlanguage'], PREG_SET_ORDER);
				preg_match_all('/\]/mi', $parse[$key][$key2][1], $parse['testlanguage2'], PREG_SET_ORDER);
				//Now that the preg_match_all() is done, we'll see the result of it. 
				//If neither of the above arrays that have any output from the function, then it's fine. On the contrary, this conditional will run.
				if (!(empty($parse['testtemplates']) && empty($parse['testtemplates2']) && empty($parse['testlanguage']) && empty($parse['testlanguage2'])))
				{
					//There was illegal content. Output the error to the user, and we log it.
					$lcontent = $this->suit->language->getLanguage('illegalcontent');
					$output = str_replace($parse[$key][$key2][0], $lcontent, $output);
					$this->suit->logError($output);
				}
			}
		}

		if (!$rows[$template]['dynamic'])
		{
			foreach ($parse['templates'] as $key => $value)
			{
				if (isset($rows[$parse['templates'][$key][1]]['id']))
				{
					$parse['templates'][$key][1] = $this->getTemplate($parse['templates'][$key][1], $rows);
				}
				else
				{
					$parse['templates'][$key][1] = $this->getTemplate($parse['templates'][$key][1], 0);
				}
			}
			$output = $this->implosion($output, $parse['templates']);
		}
		
		foreach ($parse['language'] as $key => $value)
		{
			$parse['language'][$key][1] = $this->suit->language->getLanguage($parse['language'][$key][1]);
		}
		$output = $this->implosion($output, $parse['language']);
		
		//Time to verify if this template is dynamic, so we can include the proper file from the dynamic/ directory.
		if ($rows[$template]['dynamic'] == 1)
		{
			$filepath = PATH_HOME . 'dynamic/' . $this->current_template . '';
			//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
			if (!is_dir($filepath))
			{
				mkdir($filepath, 0777);
				chmod($filepath, 0777);
			}
			//Concatanate the current path with to form the file.
			$filepath = $filepath . '/' . $template . '.php';
			
			if (!file_exists($filepath))
			{
				//Looks like it doesn't. Let's create the missing file, and report the error to the user.
				$lcontent = $this->suit->language->getLanguage('cantopenfile');
				$create_dynamic = fopen($filepath, 'w') or die($lcontent);
				fclose($create_dynamic);
				chmod($filepath, 0666); //CHMOD the file to be writable by our script.
			}
			$included = $this->include_file($filepath, $output, $rows);
			$output = $included;
		}
		return $output;
	}

        /**
	Generate Cache
	**/
	function generateCache()
	{
		$filepath = PATH_HOME . 'cache/templates/' . $this->current_template;
		//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
		if (!is_dir($filepath))
		{
			mkdir($filepath);
			chmod($filepath, 0777);
		}
		//Concatanate the current path with to form the file.
		$filepath = $filepath . '/' . $this->suit->setID . '.php';
		
		if (file_exists($filepath))
		{
			require $filepath;
		}
		if (isset($templates))
		{
			//Add values from the $templates array into a variable called $in for future the MySQL function IN().
			$in = '';
			end($templates);
			$lastkey = key($templates);
			foreach ($templates as $key => $value)
			{
				$value = addslashes($value);
				$in .= '\'' . $value . '\'';
				if ($key != $lastkey)
				{
					$in .= ', ';
				}
			}
			//Query using the IN() function to shorten query numbers.
			$templatecheck_options = array('where' => 'title IN(' . $in . ') AND parent = \'' . $this->current_theme . '\'');	
			$templatecheck = $this->suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
			if ($templatecheck)
			{
				while ($row = mysql_fetch_assoc($templatecheck))
				{
					foreach ($row as $key => $value)
					{
						$this->rows[$row['title']][$key] = $value;
					}
				}
			}
		}
	}
}
$mn = 'TemplateManagement';
?>
