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
	
	function __construct(&$reference)
	{
		$this->suit =& $reference;
	}
	
	/**
	Include a file.
	**@param string File Path
	**@param int The Layer
	**@param
	**/
	function include_file($path, $layer, $layercontent)
	{
		$suit = $this->suit; //We don't want to give too much trouble for dynamic templates, so we will let them use $suit.
		$layered[$layer] = $layercontent;
		//Include the file.
		include $path;
		return $layered[$layer];
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
		
		//foreach() Supplied argument array in order to grab the separator (|)
		foreach ($array as $key => $value)
		{
			$string = str_replace($array[$key][0], '|' . $array[$key][0] . '|', $string); 
		}
		
		//Separate each value by creating an array from the | separator.
		$string = explode("|", $string);
		
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
				if ($value == $array[$key2][0])
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
	Set a user's theme based on the database value
	**@param int User ID
	**@param string User's Password Hash
	**/
	function setTheme()
	{		
		if (defined('ID') && defined('PASS'))
		{
			$usercheck_options = array(
			'where' => 'id = \'' . ID . '\' AND password = \'' . PASS . '\''
			);
				
			$usercheck = $this->suit->mysql->select('' . TBL_PREFIX . 'users', '*', $usercheck_options);
			
			if ($usercheck)
			{
				while ($row = mysql_fetch_assoc($usercheck))
				{
					$theme = intval($row['theme']);
				}
			}
			else
			{
				$theme = 0;
			}
		}
		else
		{
			$theme = 0;
		}
		
		//If the theme ID is not set, then select the default one. Otherwise, select a theme specified by the user.
		if ($theme == 0)
		{
			$themecheck_options = array(
			'where' => 'defaults = \'1\''
			);
		}
		else
		{
			$themecheck_options = array(
			'where' => 'id = \'' . $theme . '\''
			);
		}
		
		//Run the query now.
		$themecheck = $this->suit->mysql->select(
		'themes',
		'*',
		$themecheck_options
		);
		//Return the query
		if ($themecheck)
		{
			return $themecheck;
		}
		else
		{
			return false;
		}
	}
	
	/**
	Retrieves a template from the database.
	**@param string Template Title
	**@param int User ID
	**@param string User's Password
	**@param int The layer #
	**@param int Is it embedded?
	**/
	function getTemplate($template, $layer, $embedded)
	{
		/*
		Select the appropriate theme if a userid and a password hash is sent through the function
		*/
		$themecheck = $this->setTheme();
		
		if ($themecheck)
		{
			while ($row = mysql_fetch_assoc($themecheck))
			{
				// Select the template you are trying to grab that's parent is the template set associated with the theme.
				//We send the second parameter in the escape string so we may eliminate the backslashes for otherwise wildcard MySQL Characters
				$templates = $this->suit->mysql->escape($template, 0);
				
				$templatecheck_options = array(
				'where' => 'title = \'' . $templates . '\' AND parent = \'' . $row['template'] . '\''
				);
				
				$templatecheck = $this->suit->mysql->select(
				'templates',
				'*',
				$templatecheck_options
				);
				
				if ($templatecheck)
				{
					while ($row2 = mysql_fetch_assoc($templatecheck))
					{
						$output = '';
						// If the template is not all of the following conditions: The layer not being 0, it being dynamic, and it being embedded
						if (!($layer != 0 && $row2['dynamic'] == 1 && $embedded == 1) && !($layer == 0 && $row2['dynamic'] == 1 && $embedded == 0))
						{
							$output .= $row2['content'];
							
							if (!($layer !=0 && $row2['dynamic']))
							{
								//Match {expression_here} as templates
								preg_match_all('/\{((?:[^{}]*|(?R))*)\}/', $output, $parse['templates'], PREG_SET_ORDER);
								//Match (expression_here) as dynamic templates
								preg_match_all('/\(((?:[^()]*|(?R))*)\)/', $output, $parse['dynamictemplates'], PREG_SET_ORDER);
							}
							//Match [expression_here] as languages.
							preg_match_all('/\[((?:[^\[\]]*|(?R))*)\]/', $output, $parse['language'], PREG_SET_ORDER);
							
							//Let's foreach() through the $parse array, which contains all of the parsed templates.
							foreach ($parse as $key => $value)
							{
								//Using the $parse array, we'll go ahead and attempt to evaluate and see if any illegal formatting has been parsed.
								foreach ($parse[$key] as $key2 => $value2)
								{
									//All of the possible illegal conditions that should be catched. The beginning of the symbols is catched in one array, and the ending is catched in a second one.
									preg_match_all('/\{/mi', $parse[$key][$key2][1], $parse['testtemplates'], PREG_SET_ORDER); // {
									preg_match_all('/\}/mi', $parse[$key][$key2][1], $parse['testtemplates2'], PREG_SET_ORDER); // }
									preg_match_all('/\(/mi', $parse[$key][$key2][1], $parse['testdynamictemplates'], PREG_SET_ORDER); // (
									preg_match_all('/\)/mi', $parse[$key][$key2][1], $parse['testdynamictemplates2'], PREG_SET_ORDER); // )
									preg_match_all('/\[/mi', $parse[$key][$key2][1], $parse['testlanguage'], PREG_SET_ORDER); // [
									preg_match_all('/\]/mi', $parse[$key][$key2][1], $parse['testlanguage2'], PREG_SET_ORDER); //]
									//Now that the preg_match_all() is done, we'll see the result of it. If neither of the above arrays that have any output from the function, then it's fine. 
									//On the contrary, this conditional will run.
									if (!(empty($parse['testtemplates']) && empty($parse['testtemplates2']) && empty($parse['testdynamictemplates']) && empty($parse['testdynamictemplates2']) && empty($parse['testlanguage']) && empty($parse['testlanguage2'])))
									{
										//There was illegal content. Output the error to the user, and we log it.
										$lcontent = $this->suit->language->getLanguage('illegalcontent');
										$output = str_replace($parse[$key][$key2][0], $lcontent, $output);
										$this->suit->logError($output);
									}
								}
							}

							if (!($layer !=0 && $row2['dynamic']))
							{
								foreach ($parse['templates'] as $key => $value)
								{
									$layered[$layer+1] = $this->getTemplate($parse['templates'][$key][1], $layer+1, 1);
									$output = str_replace($parse['templates'][$key][0], $layered[$layer+1], $output);
								}

								foreach ($parse['dynamictemplates'] as $key => $value)
								{
									$dcontent = $this->getDynamicTemplate($parse['dynamictemplates'][$key][1], $layer+1);
									$output = str_replace($parse['dynamictemplates'][$key][0], $dcontent, $output);
								}
							}
							foreach ($parse['language'] as $key => $value)
							{
								$lcontent = $this->suit->language->getLanguage($parse['language'][$key][1]);
								$output = str_replace($parse['language'][$key][0], $lcontent, $output);
							}
						}
						else
						{
							//The template is dynamic; we'll now have to return an error because of this.
							$lcontent = $this->suit->language->getLanguage('isdynamic');
							$lcontent = str_replace('{1}', $template, $lcontent);
							//Log the error
							$output = $lcontent;
							$this->suit->logError($output);
						}
					}
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
				return $output;
			}
		}
	}
	/**
	Retrieve a dynamic template from the database and file-system
	**@param string Dynamic Template Title
	**@param int The layer #
	******
	**@returns string The dynamic content
	**/
	function getDynamicTemplate($template, $layer)
	{
		/*
		Apply theme settings.
		*/
		$themecheck = $this->setTheme();
		
		if ($themecheck)
		{
			while ($row = mysql_fetch_assoc($themecheck))
			{
				//We send the second parameter in the escape string so we may eliminate the backslashes for otherwise wildcard MySQL Characters
				$templates = $this->suit->mysql->escape($template, 0); 
				
				$templatecheck_options = array(
				'where' => 'title = \'' . $templates . '\' AND parent = \'' . $row['template'] . '\''
				);
				
				$templatecheck = $this->suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatecheck_options);
				
				if ($templatecheck)
				{
					while ($row2 = mysql_fetch_assoc($templatecheck))
					{
						//Time to verify if this template is dynamic, so we can include the proper file from the dynamic/ directory.
						if ($row2['dynamic'] == 1)
						{
							//Well, whaddya know. It's dynamic. Let's first load the static template for it.
							$layered[$layer] = $this->getTemplate($template, $layer, 0);
							//Query the database for the title of the set, which will be the folder from where the dynamic templates are grabbed.
							$templatefolder_options = array('where' => 'id = \'' . $row2['parent'] . '\'');
							$templatefolder = $this->suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatefolder_options);
							if ($templatefolder)
							{
								while ($row3 = mysql_fetch_assoc($templatefolder))
								{
									$filepath = PATH_HOME . 'dynamic/' . $row3['title'] . '';
									//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
									if (!is_dir($filepath))
									{
										mkdir($filepath, 0777);
									}
									//Concatanate the current path with to form the file.
									$filepath = $filepath . '/' . $template . '.php';
									
									if (file_exists($filepath))
									{
										$layered[$layer] = $this->include_file($filepath, $layer, $layered[$layer]);
									}
									else
									{
										//Looks like it doesn't. Let's create the missing file, and report the error to the user.
										$lcontent = $this->suit->language->getLanguage('cantopenfile', ID, PASS);
										$create_dynamic = fopen($filepath, 'w') or die($lcontent);
										fclose($create_dynamic);
										chmod($filepath, 0666); //CHMOD the file to be writable by our script.
									}
								}
							}
							$dcontent = $layered[$layer];
						}
						else
						{
							//Hold it there, you've tried loading a non-dynamic template now.
							$lcontent = $this->suit->language->getLanguage('notdynamic', ID, PASS);
							$lcontent = str_replace('{1}', $template, $lcontent);
							$dcontent = $lcontent;
							$this->suit->logError($lcontent); //Log this erorr.
							$layered[$layer] = $dcontent;
						}	
					}
				}
				else
				{
					$lcontent = $this->suit->language->getLanguage('templatenotfound', ID, PASS);
					$lcontent = str_replace('{1}', $template, $lcontent);
					$dcontent = $lcontent;
					$this->suit->logError($lcontent);
					$layered[$layer] = $dcontent;
				}
			}
			return $dcontent;
		}
	}
}
$mn = 'TemplateManagement';
?>
