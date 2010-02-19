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
class TemplateManagement//extends SUIT
{
	/**
	Uses the getTemplate() function in order to fetch a skeleton for use in a page.
	**@param string Title of template
	**/
	function skeleton($template)
	{
		return $this->getTemplate($template, $GLOBALS['id'], $GLOBALS['pass'], 0, 0);
	}
	
	/**
	Include a file.
	**@param string File Path
	**/
	function include_file($path, $layer, $layercontent)
	{
		global $suit;
		
		$layered[$layer] = $layercontent;

		include $path;

		return $layered[$layer];
	}
	
	/**
	Set a user's theme based on the database value
	**@param int User ID
	**@param string User's Password Hash
	**/
	function setTheme($id = '', $pass = '')
	{
		global $suit;
		
		if (isset($id) && isset($pass))
		{
			$usercheck_options = array(
			'where' => 'id = \'' . $id . '\' AND password = \'' . $pass . '\''
			);
				
			$usercheck = $suit->mysql->select('' . TBL_PREFIX . 'users', '*', $usercheck_options);
			
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
		$themecheck = $suit->mysql->select(
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
	function getTemplate($template, $id, $pass, $layer, $embedded)
	{
		global $suit;
		/*
		Select the appropriate theme if a userid and a password hash is sent through the function
		*/
		$themecheck = $this->setTheme($id, $pass);
		
		if ($themecheck)
		{
			while ($row = mysql_fetch_assoc($themecheck))
			{
				// Select the template you are trying to grab that's parent is the template set associated with the theme.
				//We send the second parameter in the escape string so we may eliminate the backslashes for otherwise wildcard MySQL Characters
				$templates = $suit->mysql->escape($template, 0);
				
				$templatecheck_options = array(
				'where' => 'title = \'' . $templates . '\' AND parent = \'' . $row['template'] . '\''
				);
				
				$templatecheck = $suit->mysql->select(
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
						if (!($layer != 0 && $row2['dynamic'] == 1 && $embedded == 1))
						{
							//Append the template start comments if you're a valid administrator.
							if ($suit->loggedIn() == 2)
							{
								$output .= '<!--';
								$lcontent = $suit->language->getLanguage('templatestart', $id, $pass);
								$lcontent = str_replace('{1}', $row2['title'], $lcontent);
								$output .= $lcontent;
								$output .= '-->' . "\n";
							}
							$output .= $row2['content'];
							//Append the template end comments if you're a valid administrator.
							if ($suit->loggedIn() == 2)
							{
								$output .= "\n" . '<!--';
								$lcontent = $suit->language->getLanguage('templateend', $id, $pass);
								$lcontent = str_replace('{1}', $row2['title'], $lcontent);
								$output .= $lcontent;
								$output .= '-->';
							}
							
							
							if (!($layer !=0 && $row2['dynamic']))
							{
								//Match {expression_here} as templates
								preg_match_all("/{(.*)}/", $output, $parse['templates'], PREG_SET_ORDER);
								foreach ($parse['templates'] as $key => $value)
								{
									$layered[$layer+1] = $this->getTemplate($parse['templates'][$key][1], $id, $pass, $layer+1, 1);
									$output = str_replace($parse['templates'][$key][0], $layered[$layer+1], $output);
								}
							}
							//Match [expression_here] as languages.
							preg_match_all("/\[(.*)\]/", $output, $parse['language'], PREG_SET_ORDER);
							foreach ($parse['language'] as $key => $value)
							{
								$lcontent = $suit->language->getLanguage($parse['language'][$key][1], $id, $pass);
								$output = str_replace($parse['language'][$key][0], $lcontent, $output);
							}
							//Match (expression_here) as dynamic templates
							preg_match_all("/\((.*)\)/", $output, $parse['dynamictemplates'], PREG_SET_ORDER);
							foreach ($parse['dynamictemplates'] as $key => $value)
							{
								$dcontent = $this->getDynamicTemplate($parse['dynamictemplates'][$key][1], $id, $pass, $layer+1);
								$output = str_replace($parse['dynamictemplates'][$key][0], $dcontent, $output);
							}
						}
						else
						{
							//The template is dynamic; we'll now have to return an error because of this.
							$lcontent = $suit->language->getLanguage('isdynamic', $id, $pass);
							$lcontent = str_replace('{1}', $template, $lcontent);
							//Log the error
							$output = $lcontent;
							$suit->logError($output);
						}
						return $output;
					}
				}
				else
				{
					//Looks like the template does not exist.
					$lcontent = $suit->language->getLanguage('templatenotfound', $id, $pass);
					$lcontent = str_replace('{1}', $template, $lcontent);
					return $lcontent;
					//It's been output, but now let's log it.
					$suit->logError($lcontent);
				}
			}
		}
	}
	/**
	Retrieve a dynamic template from the database and file-system
	**@param string Dynamic Template Title
	**@param int User ID
	**@param string User's Password Hash
	**@param int The layer #
	**/
	function getDynamicTemplate($template, $id, $pass, $layer)
	{
		global $suit;	
		/*
		Apply theme settings.
		*/
		$themecheck = $this->setTheme();
		
		if ($themecheck)
		{
			while ($row = mysql_fetch_assoc($themecheck))
			{
				//We send the second parameter in the escape string so we may eliminate the backslashes for otherwise wildcard MySQL Characters
				$templates = $suit->mysql->escape($template, 0); 
				
				$templatecheck_options = array(
				'where' => 'title = \'' . $templates . '\' AND parent = \'' . $row['template'] . '\''
				);
				
				$templatecheck = $suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatecheck_options);
				
				if ($templatecheck)
				{
					while ($row2 = mysql_fetch_assoc($templatecheck))
					{
						//Time to verify if this template is dynamic, so we can include the proper file from the dynamic/ directory.
						if ($row2['dynamic'] == 1)
						{
							//Well, whaddya know. It's dynamic. Let's first load the static template for it.
							$layered[$layer] = $this->getTemplate($template, $id, $pass, $layer, 0);
							//Query the database for the title of the set, which will be the folder from where the dynamic templates are grabbed.
							$templatefolder_options = array('where' => 'id = \'' . $row2['parent'] . '\'');
							$templatefolder = $suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatefolder_options);
							if ($templatefolder)
							{
								while ($row3 = mysql_fetch_assoc($templatefolder))
								{
									$filepath = PATH_HOME . 'dynamic/' . $row3['title'] . '';
									//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
									if (!opendir($filepath))
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
										$lcontent = $suit->language->getLanguage('cantopenfile', $id, $pass);
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
							$lcontent = $suit->language->getLanguage('notdynamic', $id, $pass);
							$lcontent = str_replace('{1}', $template, $lcontent);
							$dcontent = $lcontent;
							$suit->logError($lcontent); //Log this erorr.
							$layered[$layer] = $dcontent;
						}	
					}
				}
				else
				{
					$lcontent = $suit->language->getLanguage('templatenotfound', $id, $pass);
					$lcontent = str_replace('{1}', $template, $lcontent);
					$dcontent = $lcontent;
					$suit->logError($lcontent);
					$layered[$layer] = $dcontent;
				}
			}
			return $dcontent;
		}
	}
}
$mn = 'TemplateManagement';
?>
