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
	Content of cached columns
	**@var array
	**/
	var $content;
	
	function __construct(&$reference)
	{
		//Set a value for the class variables.
		$this->suit = &$reference;
		$this->loaded = array();
		$this->content = array();
	}
	
	function __deconstruct()
	{
		unset($loaded);
		unset($this->suit);
	}
	
	/**
	Include a file.
	**@param string File Path
	**@param string Template Output
	
	**@returns arrays All of the variables defined within the function.
	**/
	function includeFile($path, $output, $chains)
	{
		$vars = array(); //Pre-create an array for the local file variables.
		$suit = &$this->suit; //Append SUIT as a reference, thus, $suit instead of $this->suit
		//Include the file, of course.
		include $path;
		//Get the keys for the defined variables inside of the file.
		$var_names = array_keys(get_defined_vars());
		//Run a foreach() loop that stores the variables inside the array.
		foreach ($var_names as $var_name)
		{
		    $vars[$var_name] = $$var_name; //We're going to use variable variables for this to work.
		}
		//Return the defined variables now for future usage.
		return $vars;
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
			if ($string != str_replace($value, ' ', $string))
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
	function getTemplate($template, $chains)
	{
		//Pre-set vars.
		$vars = array();
		if (!in_array($template, $chains))
		{
			$chains[] = $template;
			//If the current theme was valid, then proceed.
			if ($this->suit->theme['id'] != 0)
			{
				if (!array_key_exists($template, $this->loaded))
				{
					//Pre-define the arrays.
					$parse = array();
					$vars = array();
					// Select the template you are trying to grab that's parent is the template set associated with the theme.
					//We send the second parameter in the escape string so we may eliminate the backslashes for otherwise wildcard MySQL Characters
					$templates = $this->suit->db->escape($template, 0);
					$templatecheck_options = array('where' => 'title = \'' . $templates . '\' AND (parent = \'' . $this->suit->template['id'] . '\' OR parent = \'1\')');
					$templatecheck = $this->suit->db->select(TBL_PREFIX . 'templates', 'title, content', $templatecheck_options);
					if ($templatecheck)
					{
						while ($row = mysql_fetch_assoc($templatecheck))
						{
							$vars['output'] = $row['content'];
						}
					}
					else
					{
						//Looks like the template does not exist.
						$lcontent = $this->suit->languages->getLanguage('templatenotfound');
						$lcontent = str_replace('<1>', $template, $lcontent);
						$this->content[$template] = $lcontent;
						//It's been output, but now let's log it.
						$this->suit->logError($this->content[$template]);
					}
			
					//Match {expression_here} as templates
					preg_match_all('/\{((?:[^{}]*|(?R))*)\}/', $vars['output'], $parse['templates'], PREG_SET_ORDER);
			
					//Match [expression_here] as languages.
					preg_match_all('/\[((?:[^\[\]]*|(?R))*)\]/', $vars['output'], $parse['language'], PREG_SET_ORDER);
					
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
								//There was illegal content. Output the error to the user, and log it to the database.
								$lcontent = $this->suit->languages->getLanguage('illegalcontent');
								$vars['output'] = str_replace($parse[$key][$key2][0], $lcontent, $vars['output']);
								$this->suit->logError($vars['output']);
							}
						}
					}
			
					//Foreach() the template parsing array and run respective actions for them.
					foreach ($parse['templates'] as $key => $value)
					{
						//Run the getTemplate() function while iterating through the array, and then store the output of the templates inside a 3-Dimensional array.
						$output = $this->getTemplate($parse['templates'][$key][1], $chains);
						$parse['templates'][$key][1] = $output['output'];
					}
					
					//Foreach() the language parsing array  and run respective actions for them.
					foreach ($parse['language'] as $key => $value)
					{
						//Run the getLanguage() function.
						$parse['language'][$key][1] = $this->suit->languages->getLanguage($parse['language'][$key][1]);
					}
					
					$vars['output'] = $this->replace($vars['output'], $parse['templates']);
					$vars['output'] = $this->replace($vars['output'], $parse['language']);

					//Set a filepath to the Global Templates directory.
					$filepath = PATH_HOME . '/files/Global Templates';
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
						$filepath = PATH_HOME . '/files/' . $this->suit->template['title'];
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
							$lcontent = $this->suit->languages->getLanguage('cantopenfile');
							$create = fopen($filepath, 'w') or die($lcontent);
							fclose($create);
							chmod($filepath, 0666); //CHMOD the file to be writable by our script.
						}
					}
					$included = $this->includeFile($filepath, $vars['output'], $chains);
					$vars = $included;
	
					//Store the template and it's output into the loaded_template array.
					$this->loaded[$template] = $vars;
				}
				else
				{
					//The template is in the array, so we'll load it from there.
					$vars = $this->loaded[$template];
				}
			}
			else
			{
				//Looks like the template does not exist.
				$vars['output'] = $this->suit->languages->getLanguage('themenotfound');
				//It's been output, but now let's log it.
				$this->suit->logError($vars['output']);
			}
		}
		else
		{
			//Looks like the template does not exist.
			$vars['output'] = $this->suit->languages->getLanguage('infiniteloop');
			$vars['output'] = str_replace('<template>', $template, $vars['output']);
			//It's been output, but now let's log it.
			$this->suit->logError($vars['output']);
		}

		//Let's bring in the template now.
		return $vars;
	}
}
$mn = 'TemplateManagement';
?>
