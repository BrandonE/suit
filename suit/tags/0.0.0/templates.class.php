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
	function getTemplate($template, $admin, $id, $pass, $layer, $embedded)
	{
		global $tbl_prefix, $system, $PATH_HOME;
		/*
		Verify you are an administrator.
		*/
		if ($admin == 1)
		{
			$admins = 'admin';
		}
		else
		{
			$admins = '';
		}
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
					$theme = $row[$admins . 'theme'];
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
			'' . $admins . 'themes',
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
			'' . $admins . 'themes',
			$themecheck_options
			);
		}
		if ($themecheck)
		{
			while ($row = mysql_fetch_assoc($themecheck))
			{
				$templates = $system->mysql->escape($template);
				
				$templatecheck_options = array(
				'where' => 'title = \'' . $templates . '\' AND parent = \'' . $row['template'] . '\''
				);
				
				$templatecheck = $system->mysql->select(
				''.$admins.'templates',
				'*',
				$templatecheck_options
				);
				
				if ($templatecheck)
				{
					while ($row2 = mysql_fetch_assoc($templatecheck))
					{
						if ($layer == 0)
						{
							$GLOBALS['content'] = '';
							if (loggedIn() == 2)
							{
								$GLOBALS['content'] .= '<!--';
								$system->language->getLanguage('templatestart', $id, $pass);
								$GLOBALS['lcontent'] = str_replace('{1}', $row2['title'], $GLOBALS['lcontent']);
								$GLOBALS['content'] .= $GLOBALS['lcontent'];
								$GLOBALS['content'] .= '-->' . "\n";
							}
							$GLOBALS['content'] .= $row2['content'];
							if (loggedIn() == 2)
							{
								$GLOBALS['content'] .= "\n" . '<!--';
								$system->language->getLanguage('templateend', $id, $pass);
								$GLOBALS['lcontent'] = str_replace('{1}', $row2['title'], $GLOBALS['lcontent']);
								$GLOBALS['content'] .= $GLOBALS['lcontent'];
								$GLOBALS['content'] .= '-->';
							}
							//Match {expression_here} as templates
							preg_match_all("/{(.*)}/", $GLOBALS['content'], $parse['templates'], PREG_SET_ORDER);
							foreach ($parse['templates'] as $key => $value)
							{
								$this->getTemplate($parse['templates'][$key][1], $admin, $id, $pass, 1, 1);
								$GLOBALS['content'] = str_replace($parse['templates'][$key][0], $GLOBALS['layered'][1], $GLOBALS['content']);
							}
							//Match (expression_here) as templates
							preg_match_all("/\((.*)\)/", $GLOBALS['content'], $parse['dynamictemplates'], PREG_SET_ORDER);
							foreach ($parse['dynamictemplates'] as $key => $value)
							{
								$this->getDynamicTemplate($parse['dynamictemplates'][$key][1], $admin, $id, $pass, 1);
								$GLOBALS['content'] = str_replace($parse['dynamictemplates'][$key][0], $GLOBALS['dcontent'], $GLOBALS['content']);
							}
							//Match [expression_here] as languages.
							preg_match_all("/\[(.*)\]/", $GLOBALS['content'], $parse['language'], PREG_SET_ORDER);
							foreach ($parse['language'] as $key => $value)
							{
								$system->language->getLanguage($parse['language'][$key][1], $id, $pass);
								$GLOBALS['content'] = str_replace($parse['language'][$key][0], $GLOBALS['lcontent'], $GLOBALS['content']);
							}
						}
						else
						{
							if (!($row2['dynamic'] == 1 && $embedded == 1))
							{
								$GLOBALS['layered'][$layer] = '';
								if (loggedIn() == 2)
								{
									$GLOBALS['layered'][$layer] .= '<!--';
									$system->language->getLanguage('templatestart', $id, $pass);
									$GLOBALS['lcontent'] = str_replace('{1}', $row2['title'], $GLOBALS['lcontent']);
									$GLOBALS['layered'][$layer] .= $GLOBALS['lcontent'];
									$GLOBALS['layered'][$layer] .= '-->' . "\n";
								}
								$GLOBALS['layered'][$layer] .= $row2['content'];
								if (loggedIn() == 2)
								{
									$GLOBALS['layered'][$layer] .= "\n" . '<!--';
									$system->language->getLanguage('templateend', $id, $pass);
									$GLOBALS['lcontent'] = str_replace('{1}', $row2['title'], $GLOBALS['lcontent']);
									$GLOBALS['layered'][$layer] .= $GLOBALS['lcontent'];
									$GLOBALS['layered'][$layer] .= '-->';
								}
								if (!($row2['dynamic']))
								{
									preg_match_all("/{(.*)}/", $GLOBALS['layered'][$layer], $parse['templates'], PREG_SET_ORDER);
									foreach ($parse['templates'] as $key => $value)
									{
										$this->getTemplate($parse['templates'][$key][1], $admin, $id, $pass, $layer+1, 1);
										$GLOBALS['layered'][$layer] = str_replace($parse['templates'][$key][0], $GLOBALS['layered'][$layer+1], $GLOBALS['layered'][$layer]);
									}
								}
								//Match (expression_here) as templates
								preg_match_all("/\((.*)\)/", $GLOBALS['layered'][$layer], $parse['dynamictemplates'], PREG_SET_ORDER);
								foreach ($parse['dynamictemplates'] as $key => $value)
								{
									$this->getDynamicTemplate($parse['dynamictemplates'][$key][1], $admin, $id, $pass, $layer+1);
									$GLOBALS['layered'][$layer] = str_replace($parse['dynamictemplates'][$key][0], $GLOBALS['dcontent'], $GLOBALS['content']);
								}
								preg_match_all("/\[(.*)\]/", $GLOBALS['layered'][$layer], $parse['language'], PREG_SET_ORDER);
								foreach ($parse['language'] as $key => $value)
								{
									$system->language->getLanguage($parse['language'][$key][1], $id, $pass);
									$GLOBALS['layered'][$layer] = str_replace($parse['language'][$key][0], $GLOBALS['lcontent'], $GLOBALS['layered'][$layer]);
								}
							}
							else
							{
								$system->language->getLanguage('isdynamic', $id, $pass);
								$GLOBALS['lcontent'] = str_replace('{1}', $template, $GLOBALS['lcontent']);
								$GLOBALS['layered'][$layer] = $GLOBALS['lcontent'];
							}
						}
					}
				}
				else
				{
					$system->language->getLanguage('templatenotfound', $id, $pass);
					$GLOBALS['lcontent'] = str_replace('{1}', $template, $GLOBALS['lcontent']);
					$GLOBALS['layered'][$layer] = $GLOBALS['lcontent'];
				}
			}
		}
	}
	
	function getDynamicTemplate($template, $admin, $id, $pass, $layer)
	{
		global $tbl_prefix, $system, $PATH_HOME;	
		/*
		Verify you are an administrator.
		*/
		if ($admin == 1)
		{
			$admins = 'admin';
		}
		else
		{
			$admins = '';
		}
		
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
					$theme = $row[$admins . 'theme'];
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
			'' . $admins . 'themes',
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
			'' . $admins . 'themes',
			$themecheck_options
			);
		}
		if ($themecheck)
		{
			while ($row = mysql_fetch_assoc($themecheck))
			{
				$templates = $system->mysql->escape($template);
				
				$templatecheck_options = array(
				'where' => 'title = \'' . $templates . '\' AND parent = \'' . $row['template'] . '\''
				);
				
				$templatecheck = $system->mysql->select(
				''.$admins.'templates',
				'*',
				$templatecheck_options
				);
				if ($templatecheck)
				{
					while ($row2 = mysql_fetch_assoc($templatecheck))
					{
						if ($row2['dynamic'] == 1)
							{
							$this->getTemplate($template, $admin, $id, $pass, $layer, 0);
							if ($admin == '')
							{
								switch ($template)
								{
									case 'test':
										echo 'blah';
									;	break;
								}
							}
							else
							{
								switch ($template)
								{
									case 'loginmessage':
										if (isset($GLOBALS['message']))
										{
											$system->language->getLanguage($GLOBALS['message'], $adminid, $adminpass);
											$GLOBALS['layered'][$layer] = str_replace('{1}', $GLOBALS['lcontent'], $GLOBALS['layered'][$layer]);
											if ($GLOBALS['message'] = 'nomatch')
											{
												$GLOBALS['layered'][$layer] = str_replace('{1}', htmlspecialchars($_POST['username']), $GLOBALS['layered'][$layer]);
											}
										}
										else
										{
											$GLOBALS['layered'][$layer] = str_replace('{1}', '', $GLOBALS['layered'][$layer]);
										}
									;	break;
	
									case 'admintemplatesmessage':
										if (isset($GLOBALS['message']))
										{
											$system->language->getLanguage($GLOBALS['message'], $adminid, $adminpass);
											$GLOBALS['layered'][$layer] = str_replace('{1}', $GLOBALS['lcontent'], $GLOBALS['layered'][$layer]);
										}
										else
										{
											$GLOBALS['layered'][$layer] = str_replace('{1}', '', $GLOBALS['layered'][$layer]);
										}
									;	break;
	               
									case 'admintemplateslist':
										$list = '';
										if (!isset($_GET['id']))
										{
											$parentget_options = array(
											'where' => 'parent = \'0\'',
											);
											
											$parentget = $system->mysql->select(
											'admintemplates',
											'*',
											$parentget_options
											);
											
											if ($parentget)
											{
												while ($row = mysql_fetch_assoc($parentget))
												{
													$system->templates->getTemplate('admintemplatelist', 1, $adminid, $adminpass, $layer+1, 0);
													$list .= $GLOBALS['layered'][$layer+1];
													$list = str_replace('{1}', $row['id'], $list);
													$list = str_replace('{2}', htmlspecialchars($row['title']), $list);
													
													$adminthemes_options = array(
													'where' => 'template = \'' . $row['id'] . '\''
													);
													
													$adminthemes = $system->mysql->select(
													'adminthemes',
													'*',
													$adminthemes_options
													);
													
													if ($adminthemes)
													{
														while ($row2 = mysql_fetch_assoc($adminthemes))
														{
															$system->templates->getTemplate('adminassociatedthemes', 1, $adminid, $adminpass, $layer+1, 0);
															$themes .= $GLOBALS['layered'][$layer+1];
															$themes = str_replace('{1}', htmlspecialchars($row2['title']), $themes);
														}
														$list = str_replace('{3}', $themes, $list);
													}
													else
													{
														$list = str_replace('{3}', '', $list);
													}
												}
											}
										}
										else
										{
											if (!isset($_GET['template']))
											{
												$id = $system->mysql->escape($_GET['id']);
												/*
												$admintemplates_options = array(
												'where' => 'parent = \'' . $id . '\''
												);
												
												$admintemplates = $system->mysql->select(
												'admintemplates',
												'*',
												$admintemplates_options
												);
												*/
												$query = 'SELECT * FROM ' . $tbl_prefix . 'admintemplates WHERE parent = \'' . $id . '\' order by title';
												$result = mysql_query($query);
												if ($result)
												{
													while ($row = mysql_fetch_assoc($result))
													{
														$system->templates->getTemplate('selectadmintemplate', 1, $adminid, $adminpass, $layer+1, 0);
														$list .= $GLOBALS['layered'][$layer+1];
														$list = str_replace('{1}', $id, $list);
														$list = str_replace('{2}', $row['id'], $list);
														$list = str_replace('{3}', htmlspecialchars($row['title']), $list);
													}
												}
												else
												{
													header('refresh: 0; url=./templates.php');
													exit;
												}
											}
											else
											{
												$template = $system->mysql->escape($_GET['template']);
												
												$admintemplates_options = array(
												'where' => 'id = \'' . $template . '\''
												);
												
												$admintemplates = $system->mysql->select(
												'admintemplates',
												'*',
												$admintemplates_options
												);
												
												if ($admintemplates)
												{
													while ($row = mysql_fetch_assoc($admintemplates))
													{
														$system->templates->getTemplate('editadmintemplate', 1, $adminid, $adminpass, $layer+1, 0);
														$list .= $GLOBALS['layered'][$layer+1];
														if ($row['deletable'] == 1)
														{
															$deletable = '';
														}
														else
														{
															$deletable = 'readonly';
														}
														$list = str_replace('{1}', $row['id'], $list);
														$list = str_replace('{2}', htmlentities($row['title']), $list);
														$list = str_replace('{3}', $deletable, $list);
														$list = str_replace('{4}', htmlentities($row['content']), $list);
													}
												}
												else
												{
													header('refresh: 0; url=./templates.php');
													exit;
												}
											}
										}
										$GLOBALS['layered'][$layer] = str_replace('{1}', $list, $GLOBALS['layered'][$layer]);
									;	break;
								}
							}
							$GLOBALS['dcontent'] = $GLOBALS['layered'][$layer];
						}
						else
						{
							$system->language->getLanguage('notdynamic', $id, $pass);
							$GLOBALS['lcontent'] = str_replace('{1}', $template, $GLOBALS['lcontent']);
							$GLOBALS['dcontent'] = $GLOBALS['lcontent'];
						}	
					}
				}
				else
				{
					$system->language->getLanguage('templatenotfound', $id, $pass);
					$GLOBALS['lcontent'] = str_replace('{1}', $template, $GLOBALS['lcontent']);
					$GLOBALS['dcontent'] = $GLOBALS['lcontent'];
				}
			}
		}
	}
}
$mn = 'TemplateManagement';
?>
