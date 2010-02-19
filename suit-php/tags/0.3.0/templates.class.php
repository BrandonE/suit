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
	Retrieves a template from the database.
	**@param string
	**@param int
	**@param int
	**@param string
	**@param int
	**@param string
	**/

	function skeleton($template)
	{
		$this->getTemplate($template, $GLOBALS['id'], $GLOBALS['pass'], 0, 0);
	}

	function getTemplate($template, $id, $pass, $layer, $embedded)
	{
		global $tbl_prefix, $system, $PATH_HOME;
		/*
		Verify you are an administrator.
		*/
		if (isset($id) && isset($pass))
		{
			$usercheck_options = array(
			'where' => 'id = \'' . $id . '\' AND password = \'' . $pass . '\''
			);
			
			$usercheck = $system->mysql->select(
			'users',
			'*',
			$usercheck_options
			);
			
			if ($usercheck)
			{
				while ($row = mysql_fetch_assoc($usercheck))
				{
					$theme = $row['theme'];
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
		
		/*
		If the theme ID is not set, then select a default one. Otherwise, select a theme specified by the user.
		*/
		if ($theme == 0)
		{
			$themecheck_options = array(
			'where' => 'defaults = \'1\''
			);
					
			$themecheck = $system->mysql->select(
			'themes',
			'*',
			$themecheck_options
			);
		}
		else
		{
			$themecheck_options = array(
			'where' => 'id = \'' . $theme . '\''
			);
					
			$themecheck = $system->mysql->select(
			'*',
			'themes',
			$themecheck_options
			);
		}
		if ($themecheck)
		{
			while ($row = mysql_fetch_assoc($themecheck))
			{
				$templates = $system->mysql->escape($template, 0);
				
				$templatecheck_options = array(
				'where' => 'title = \'' . $templates . '\' AND parent = \'' . $row['template'] . '\''
				);
				
				$templatecheck = $system->mysql->select(
				'templates',
				'*',
				$templatecheck_options
				);
				if ($templatecheck)
				{
					while ($row2 = mysql_fetch_assoc($templatecheck))
					{
						$output = '';
						if (!($layer != 0 && $row2['dynamic'] == 1 && $embedded == 1))
						{
							if ($system->loggedIn() == 2)
							{
								$output .= '<!--';
								$system->language->getLanguage('templatestart', $id, $pass);
								$GLOBALS['lcontent'] = str_replace('{1}', $row2['title'], $GLOBALS['lcontent']);
								$output .= $GLOBALS['lcontent'];
								$output .= '-->' . "\n";
							}
							$output .= $row2['content'];
							if ($system->loggedIn() == 2)
							{
								$output .= "\n" . '<!--';
								$system->language->getLanguage('templateend', $id, $pass);
								$GLOBALS['lcontent'] = str_replace('{1}', $row2['title'], $GLOBALS['lcontent']);
								$output .= $GLOBALS['lcontent'];
								$output .= '-->';
							}
							if (!($layer !=0 && $row2['dynamic']))
							{
								//Match {expression_here} as templates
								preg_match_all("/{(.*)}/", $output, $parse['templates'], PREG_SET_ORDER);
								foreach ($parse['templates'] as $key => $value)
								{
									$this->getTemplate($parse['templates'][$key][1], $id, $pass, $layer+1, 1);
									$output = str_replace($parse['templates'][$key][0], $GLOBALS['layered'][$layer+1], $output);
								}
							}
							//Match [expression_here] as languages.
							preg_match_all("/\[(.*)\]/", $output, $parse['language'], PREG_SET_ORDER);
							foreach ($parse['language'] as $key => $value)
							{
								$system->language->getLanguage($parse['language'][$key][1], $id, $pass);
								$output = str_replace($parse['language'][$key][0], $GLOBALS['lcontent'], $output);
							}
							//Match (expression_here) as templates
							preg_match_all("/\((.*)\)/", $output, $parse['dynamictemplates'], PREG_SET_ORDER);
							foreach ($parse['dynamictemplates'] as $key => $value)
							{
								$this->getDynamicTemplate($parse['dynamictemplates'][$key][1], $id, $pass, $layer+1);
								$output = str_replace($parse['dynamictemplates'][$key][0], $GLOBALS['dcontent'], $output);
							}
						}
						else
						{
							$system->language->getLanguage('isdynamic', $id, $pass);
							$GLOBALS['lcontent'] = str_replace('{1}', $template, $GLOBALS['lcontent']);
							$output = $GLOBALS['lcontent'];
							$query = 'INSERT INTO `errorlog` VALUES (\'\', \'' . mysql_real_escape_string($GLOBALS['lcontent']) . '\', \'' . date('m/d/y H:i:s') . '\', \'' . mysql_real_escape_string($_SERVER['PHP_SELF']) . '\')';
							mysql_query($query);
						}
						if ($layer == 0)
						{
							$GLOBALS['content'] = $output;
						}
						else
						{
							$GLOBALS['layered'][$layer] = $output;
						}
					}
				}
				else
				{
					$system->language->getLanguage('templatenotfound', $id, $pass);
					$GLOBALS['lcontent'] = str_replace('{1}', $template, $GLOBALS['lcontent']);
					if ($layer != 0)
					{
						$GLOBALS['layered'][$layer] = $GLOBALS['lcontent'];
					}
					else
					{
						$GLOBALS['content'] .= $GLOBALS['lcontent'];
					}
					$query = 'INSERT INTO errorlog VALUES (\'\', \'' . mysql_real_escape_string($GLOBALS['lcontent']) . '\', \'' . date('m/d/y H:i:s') . '\', \'' . mysql_real_escape_string($_SERVER['PHP_SELF']) . '\')';
					mysql_query($query);
				}
			}
		}
	}
	
	function getDynamicTemplate($template, $id, $pass, $layer)
	{
		global $tbl_prefix, $system, $PATH_HOME;	
		/*
		Verify you are an administrator.
		*/
		if (isset($id) && isset($pass))
		{
			$usercheck_options = array(
			'where' => 'id = \'' . $id . '\' AND password = \'' . $pass . '\''
			);
			
			$usercheck = $system->mysql->select(
			'users',
			'*',
			$usercheck_options
			);
			
			if ($usercheck)
			{
				while ($row = mysql_fetch_assoc($usercheck))
				{
					$theme = $row['theme'];
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
		
		/*
		If the theme ID is not set, then select a default one. Otherwise, select a theme specified by the user.
		*/
		if ($theme == 0)
		{
			$themecheck_options = array(
			'where' => 'defaults = \'1\''
			);
					
			$themecheck = $system->mysql->select(
			'themes',
			'*',
			$themecheck_options
			);
		}
		else
		{
			$themecheck_options = array(
			'where' => 'id = \'' . $theme . '\''
			);
					
			$themecheck = $system->mysql->select(
			'*',
			'themes',
			$themecheck_options
			);
		}
		if ($themecheck)
		{
			while ($row = mysql_fetch_assoc($themecheck))
			{
				$templates = $system->mysql->escape($template, 0);
				
				$templatecheck_options = array(
				'where' => 'title = \'' . $templates . '\' AND parent = \'' . $row['template'] . '\''
				);
				
				$templatecheck = $system->mysql->select(
				'templates',
				'*',
				$templatecheck_options
				);
				
				if ($templatecheck)
				{
					while ($row2 = mysql_fetch_assoc($templatecheck))
					{
						if ($row2['dynamic'] == 1)
						{
							$this->getTemplate($template, $id, $pass, $layer, 0);
							
							/*
							We must verify that the file for this dynamic template exists in dynamic. 
							If it does not, then a blank file will be created automatically.
							*/
							if (file_exists($PATH_HOME . 'dynamic/' . $template . '.php'))
							{
								include $PATH_HOME . 'dynamic/' . $template . '.php';
							}
							else
							{
								$system->language->getLanguage('cantopenfile', $id, $pass);
								$create_dynamic = fopen($PATH_HOME . 'dynamic/' . $template . '.php', 'w') or die($GLOBALS['lcontent']);
								fclose($create_dynamic);
							}
							
							$GLOBALS['dcontent'] = $GLOBALS['layered'][$layer];
						}
						else
						{
							$system->language->getLanguage('notdynamic', $id, $pass);
							$GLOBALS['lcontent'] = str_replace('{1}', $template, $GLOBALS['lcontent']);
							$GLOBALS['dcontent'] = $GLOBALS['lcontent'];
							$system->logError($GLOBALS['lcontent']);
						}	
					}
				}
				else
				{
					$system->language->getLanguage('templatenotfound', $id, $pass);
					$GLOBALS['lcontent'] = str_replace('{1}', $template, $GLOBALS['lcontent']);
					$GLOBALS['dcontent'] = $GLOBALS['lcontent'];
					$system->logError($GLOBALS['lcontent']);
				}
			}
		}
	}
}
$mn = 'TemplateManagement';
?>
