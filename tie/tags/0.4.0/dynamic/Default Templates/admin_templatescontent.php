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
if ($suit->loggedIn() == 2)
{
	$list = '';
	if (isset($_GET['cmd']) && ($_GET['cmd'] == 'edittemplate' || $_GET['cmd'] == 'addtemplate' || $_GET['cmd'] == 'deletetemplate' || $_GET['cmd'] == 'clonetemplate' || $_GET['cmd'] == 'addset'))
	{
		if ($_GET['cmd'] == 'addtemplate')
		{
			if (isset($_POST['add']))
			{
				header('refresh: 0; url=./admin_templates.php?cmd=addtemplate&set=' . $_POST['set'] . '&template=' . $_POST['title'] . '&dynamic=' . $_POST['dynamic']);
				exit;
			}
			else
			{
				if ($_GET['dynamic'] == 0)
				{
					$layered[$layer+1] = $suit->templates->getTemplate('admin_templateadded', $layer+1, 0);
					$list .= $layered[$layer+1];
				}
				else
				{
					$layered[$layer+1] = $suit->templates->getTemplate('admin_dynamictemplateadded', $layer+1, 0);
					$list .= $layered[$layer+1];
				}
			}
		}
		if ($_GET['cmd'] == 'edittemplate')
		{
			if (isset($_POST['edit']))
			{
				header('refresh: 0; url=./admin_templates.php?cmd=edittemplate&template=' . $_POST['template']);
				exit;
			}
			else
			{
				$template = $suit->mysql->escape($_GET['template']);
				$templatecheck_options = array(
				'where' => 'id = \'' . $template . '\''
				);
				
				$templatecheck = $suit->mysql->select(
				'templates',
				'*',
				$templatecheck_options
				);
				
				if ($templatecheck)
				{
					while ($row = mysql_fetch_assoc($templatecheck))
					{
						if ($row['dynamic'] == 0)
						{
							$layered[$layer+1] = $suit->templates->getTemplate('admin_templateedited', $layer+1, 0);
							$list .= $layered[$layer+1];
						}
						else
						{
							$layered[$layer+1] = $suit->templates->getTemplate('admin_dynamictemplateedited', $layer+1, 0);
							$list .= $layered[$layer+1];
						}
					}
				}
				else
				{
					header('refresh: 0; url=./admin_templates.php');
					exit;
				}
			}
		}
		if ($_GET['cmd'] == 'deletetemplate')
		{
			if (isset($_POST['delete']))
			{
				header('refresh: 0; url=./admin_templates.php?cmd=deletetemplate');
				exit;
			}
			else
			{
				$layered[$layer+1] = $suit->templates->getTemplate('admin_templatedeleted', $layer+1, 0);
				$list .= $layered[$layer+1];
			}
		}
		if ($_GET['cmd'] == 'clonetemplate')
		{
			if (isset($_POST['clone']))
			{
				header('refresh: 0; url=./admin_templates.php?cmd=clonetemplate');
				exit;
			}
			else
			{
				$layered[$layer+1] = $suit->templates->getTemplate('admin_templatecloned', $layer+1, 0);
				$list .= $layered[$layer+1];
			}
		}
		if ($_GET['cmd'] == 'addset')
		{
			$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_addset', $layer+1);
			$list .= $layered[$layer+1];
			if (isset($_GET['error']) && ($_GET['error'] == 'missingtitle' || $_GET['error'] == 'duplicatetitle'))
			{
				if ($_GET['error'] == 'missingtitle')
				{
					$lcontent = $suit->language->getLanguage('missingtitle');
				}
				if ($_GET['error'] == 'duplicatetitle')
				{
					$lcontent = $suit->language->getLanguage('duplicatetitle');
				}
				$list = str_replace('{1}', $lcontent, $list);
			}
			else
			{
				$list = str_replace('{1}', '', $list);
			}
		}
	}
	else
	{
		if (!isset($_GET['set']))
		{
			if (isset($_GET['cmd']) && ($_GET['cmd'] == 'setadded' || $_GET['cmd'] == 'setrenamed' || $_GET['cmd'] == 'setdeleted' || $_GET['cmd'] == 'setcloned'))
			{
				if ($_GET['cmd'] == 'setadded')
				{
					if (isset($_POST['addset']))
					{
						header('refresh: 0; url=./admin_templates.php?cmd=setadded&submitted=1');
						exit;
					}
					else
					{
						$layered[$layer+1] = $suit->templates->getTemplate('admin_setadded', $layer+1, 0);
						$message = $layered[$layer+1];
					}
				}
				if ($_GET['cmd'] == 'setrenamed')
				{
					if (isset($_POST['renameset']))
					{
						header('refresh: 0; url=./admin_templates.php?cmd=setrenamed&submitted=1');
						exit;
					}
					else
					{
						$layered[$layer+1] = $suit->templates->getTemplate('admin_setrenamed', $layer+1, 0);
						$message = $layered[$layer+1];
					}
				}
				if ($_GET['cmd'] == 'setdeleted')
				{
					if (isset($_POST['deleteset']))
					{
						header('refresh: 0; url=./admin_templates.php?cmd=setdeleted&submitted=1');
						exit;
					}
					else
					{
						$layered[$layer+1] = $suit->templates->getTemplate('admin_setdeleted', $layer+1, 0);
						$message = $layered[$layer+1];
					}
				}
				if ($_GET['cmd'] == 'setcloned')
				{
					if (isset($_POST['cloneset']))
					{
						header('refresh: 0; url=./admin_templates.php?cmd=setcloned&submitted=1');
						exit;
					}
					else
					{
						$layered[$layer+1] = $suit->templates->getTemplate('admin_setcloned', $layer+1, 0);
						$message = $layered[$layer+1];
					}
				}
			}
			else
			{
				$message = '';
			}
			$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_selecttemplatesetskeleton', $layer+1);
			$page = $layered[$layer+1];
			$parentget_options = array(
			'where' => 'parent = \'0\'',
			);
			
			$parentget = $suit->mysql->select('templates', '*', $parentget_options);
			
			if ($parentget)
			{
				while ($row = mysql_fetch_assoc($parentget))
				{
					$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_selecttemplateset', $layer+1);
					$list .= $layered[$layer+1];
					$adminthemes_options = array(
					'where' => 'template = \'' . $row['id'] . '\''
					);
					
					$adminthemes = $suit->mysql->select(
					'themes',
					'*',
					$adminthemes_options
					);
					
					if ($adminthemes)
					{
						while ($row2 = mysql_fetch_assoc($adminthemes))
						{
							$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_associatedthemes', $layer+1);
							$themes = $layered[$layer+1];
							$themes = str_replace('{1}', htmlspecialchars($row2['title']), $themes);
						}
					}
					else
					{
						$themes = '';
					}
					$array = Array
					(
						Array
						(
							'{1}', $row['id']
						),

						Array
						(
							'{2}', htmlspecialchars($row['title'])
						),

						Array
						(
							'{3}', $themes
						)
					);
					$list = $suit->templates->implosion($list, $array);
				}
			}
			$array = Array
			(
				Array
				(
					'{1}', $message
				),
	
				Array
				(
					'{2}', $list
				)
			);
			$page = $suit->templates->implosion($page, $array);			
			$list = $page;
		}
		else
		{
			if (!isset($_GET['template']))
			{
				if ($_GET['cmd'] == 'view' || $_GET['cmd'] == 'add' || $_GET['cmd'] == 'renameset' || $_GET['cmd'] == 'deleteset' || $_GET['cmd'] == 'cloneset')
				{
					if ($_GET['cmd'] == 'view')
					{
						$set = $suit->mysql->escape($_GET['set']);
						$templatesetexists_options = array('where' => 'id = \'' . $set . '\' AND parent = \'0\'');
						$templatesetexists = $suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatesetexists_options);
						
						if ($templatesetexists)
						{
							$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_selecttemplateskeleton', $layer+1);
							$page = $layered[$layer+1];
							$lcontent = $suit->language->getLanguage('templates');
							$query = 'SELECT * FROM ' . TBL_PREFIX . 'templates WHERE parent = \'' . $set . '\' AND dynamic = \'0\' ORDER BY `title`';
							$result = mysql_query($query);
							if ($result)
							{
								while ($row = mysql_fetch_assoc($result))
								{
									$layered[$layer+1] = $suit->templates->getTemplate('admin_selecttemplate', $layer+1, 0);
									$list .= $layered[$layer+1];
									$array = Array
									(
										Array
										(
											'{1}', htmlspecialchars($row['title'])
										),
							
										Array
										(
											'{2}', $set
										),

										Array
										(
											'{3}', $row['id']
										)
									);
									$list = $suit->templates->implosion($list, $array);
								}
							}
							$list2 = '';
							$lcontent2 = $suit->language->getLanguage('dynamictemplates');
							$query = 'SELECT * FROM ' . TBL_PREFIX . 'templates WHERE parent = \'' . $set . '\' AND dynamic = \'1\' ORDER BY `title`';
							$result = mysql_query($query);
							if ($result)
							{
								while ($row = mysql_fetch_assoc($result))
								{
									$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_selecttemplate', $layer+1);
									$list2 .= $layered[$layer+1];
									$array = Array
									(
										Array
										(
											'{1}', htmlspecialchars($row['title'])
										),
							
										Array
										(
											'{2}', $set
										),

										Array
										(
											'{3}', $row['id']
										)
									);
									$list2 = $suit->templates->implosion($list2, $array);
								}
							}
							$array = Array
							(
								Array
								(
									'{1}', $set
								),
					
								Array
								(
									'{2}', $lcontent
								),

								Array
								(
									'{3}', $list
								),

								Array
								(               
									'{4}', $lcontent2
								),

								Array
								(
									'{5}', $list2
								)
							);
							$page = $suit->templates->implosion($page, $array);
							$list = $page;
						}
						else
						{
							header('refresh: 0; url=./admin_templates.php');
							exit;
						}
					}
					if ($_GET['cmd'] == 'add')
					{
						$set = $suit->mysql->escape($_GET['set']);
						$templatesetexists_options = array('where' => 'id = \'' . $set . '\' AND parent = \'0\'');
						$templatesetexists = $suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatesetexists_options);
						
						if ($templatesetexists)
						{
							$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_addtemplate', $layer+1, 0);
							$list .= $layered[$layer+1];
							if (isset($_GET['error']) && ($_GET['error'] == 'missingtitle' || $_GET['error'] == 'duplicatetitle'))
							{
								if ($_GET['error'] == 'missingtitle')
								{
									$lcontent = $suit->language->getLanguage('missingtitle');
								}
								if ($_GET['error'] == 'duplicatetitle')
								{
									$lcontent = $suit->language->getLanguage('duplicatetitle');
								}
							}
							else
							{
								$lcontent = '';
							}
							$array = Array
							(
								Array
								(
									'{1}', $lcontent
								),
					
								Array
								(
									'{2}', $set
								)
							);
							$list = $suit->templates->implosion($list, $array);
						}
						else
						{
							header('refresh: 0; url=./admin_templates.php');
							exit;
						}
					}
					if ($_GET['cmd'] == 'renameset')
					{
						$set = $suit->mysql->escape($_GET['set']);
						$setcheck_options = array(
						'where' => 'id = \'' . $set . '\''
						);
						
						$setcheck = $suit->mysql->select(
						'templates',
						'*',
						$setcheck_options
						);
						
						if ($setcheck)
						{
							while ($row = mysql_fetch_assoc($setcheck))
							{
								$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_renameset', $layer+1);
								$list .= $layered[$layer+1];
								if (isset($_GET['error']) && ($_GET['error'] == 'missingtitle' || $_GET['error'] == 'duplicatetitle'))
								{
									if ($_GET['error'] == 'missingtitle')
									{
										$lcontent = $suit->language->getLanguage('missingtitle');
									}
									if ($_GET['error'] == 'duplicatetitle')
									{
										$lcontent = $suit->language->getLanguage('duplicatetitle');
									}
								}
								else
								{
									$lcontent = '';
								}
								$array = Array
								(
									Array
									(
										'{1}', $lcontent
									),
						
									Array
									(
										'{2}', $_GET['set']
									),
	
									Array
									(
										'{3}', htmlentities($row['title'])
									)
								);
								$list = $suit->templates->implosion($list, $array);
							}
						}
						else
						{
							header('refresh: 0; url=./admin_templates.php');
							exit;
						}
					}
					if ($_GET['cmd'] == 'deleteset')
					{
						$set = $suit->mysql->escape($_GET['set']);
						
						$admintemplates_options = array(
						'where' => 'id = \'' . $set . '\''
						);
						
						$admintemplates = $suit->mysql->select(
						'templates',
						'*',
						$admintemplates_options
						);
						
						if ($admintemplates)
						{
							while ($row = mysql_fetch_assoc($admintemplates))
							{
								$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_deleteset', $layer+1);
								$list .= $layered[$layer+1];
								$lcontent = $suit->language->getLanguage('deleteconfirm');
								$lcontent = str_replace('{1}', $row['title'], $lcontent);
								$array = Array
								(
									Array
									(
										'{1}', $lcontent
									),
						
									Array
									(
										'{2}', $set
									)
								);
								$list = $suit->templates->implosion($list, $array);
							}
						}
						else
						{
							header('refresh: 0; url=./admin_templates.php');
							exit;
						}
					}
					if ($_GET['cmd'] == 'cloneset')
					{
						$set = $suit->mysql->escape($_GET['set']);
						$setcheck_options = array(
						'where' => 'id = \'' . $set . '\''
						);
						
						$setcheck = $suit->mysql->select(
						'templates',
						'*',
						$setcheck_options
						);
						
						if ($setcheck)
						{
							while ($row = mysql_fetch_assoc($setcheck))
							{
								$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_cloneset', $layer+1);
								$list .= $layered[$layer+1];
								if (isset($_GET['error']) && ($_GET['error'] == 'missingtitle' || $_GET['error'] == 'duplicatetitle'))
								{
									if ($_GET['error'] == 'missingtitle')
									{
										$lcontent = $suit->language->getLanguage('missingtitle');
									}
									if ($_GET['error'] == 'duplicatetitle')
									{
										$lcontent = $suit->language->getLanguage('duplicatetitle');
									}
								}
								else
								{
									$lcontent = '';
								}
								$array = Array
								(
									Array
									(
										'{1}', $lcontent
									),
						
									Array
									(
										'{2}', $_GET['set']
									),
	
									Array
									(
										'{3}', htmlentities($row['title'])
									)
								);
								$list = $suit->templates->implosion($list, $array);
							}
						}
						else
						{
							header('refresh: 0; url=./admin_templates.php');
							exit;
						}
					}
				}
				else
				{
					header('refresh: 0; url=./admin_templates.php');
					exit;
				}
			}
			else
			{
				if ($_GET['cmd'] == 'edit')
				{
					$set = $suit->mysql->escape($_GET['set']);
					$template = $suit->mysql->escape($_GET['template']);
					
					$admintemplates_options = array(
					'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
					);
					
					$admintemplates = $suit->mysql->select(
					'templates',
					'*',
					$admintemplates_options
					);
					
					if ($admintemplates)
					{
						while ($row = mysql_fetch_assoc($admintemplates))
						{
							$admintemplateset_options = array(
							'where' => 'id = \'' . $set . '\''
							);
							
							$admintemplateset = $suit->mysql->select('templates', '*', $admintemplateset_options);
							
							if ($admintemplateset)
							{
								while ($row2 = mysql_fetch_assoc($admintemplateset))
								{
									$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_edittemplate', $layer+1);
									$list .= $layered[$layer+1];
									$layered[$layer+2] = $suit->templates->getDynamicTemplate('admin_dynamictemplatescodeskeleton', $layer+2);
									$layered[$layer+3] = $suit->templates->getDynamicTemplate('admin_dynamictemplatescode', $layer+3);
									if ($row['dynamic'] == 1)
									{
										$file = PATH_HOME . 'dynamic/' . $row2['title'] . '/' . $row['title'] . '.php';
										if (!file_exists($file))
										{
											$create_dynamic = fopen($file, 'w') or die($lcontent);
											fclose($create_dynamic);
											chmod($file, 0666); //CHMOD the file to be writable by our script.
										}
										$fh = fopen($file, 'r');
										if (filesize($file))
										{
											$code = fread($fh, filesize($file));
										}
										else
										{
											$code = '';
										}
										fclose($fh);
										$layered[$layer+3] = str_replace('{1}', htmlentities($code), $layered[$layer+3]);
										$layered[$layer+2] = str_replace('{1}', $layered[$layer+3], $layered[$layer+2]);
									}
									else
									{
										$layered[$layer+3] = str_replace('{1}', '', $layered[$layer+3]);
										$layered[$layer+2] = str_replace('{1}', '', $layered[$layer+2]);
									}
									if (isset($_GET['error']))
									{
										if ($_GET['error'] == 'missingtitle')
										{
											$lcontent = $suit->language->getLanguage('missingtitle');
										}
										if ($_GET['error'] == 'duplicatetitle')
										{
											$lcontent = $suit->language->getLanguage('duplicatetitle');
										}
									}
									else
									{
										$lcontent = '';
									}
									if ($row['dynamic'] == 0)
									{
										$select = ' selected';
									}
									else
									{
										$select = '';
									}
									if ($row['dynamic'] == 1)
									{
										$select2 = ' selected';
									}
									else
									{
										$select2 = '';
									}
									$array = Array
									(
										Array
										(
											'{1}', $lcontent
										),
							
										Array
										(
											'{2}', $row['parent']
										),
		
										Array
										(
											'{3}', $row['id']
										),

										Array
										(
											'{4}', htmlentities($row['title'])
										),

										Array
										(
											'{5}', htmlentities($row['content'])
										),

										Array
										(
											'{6}', $select
										),

										Array
										(
											'{7}', $select2
										),

										Array
										(
											'{8}', $layered[$layer+2]
										)
									);
									$list = $suit->templates->implosion($list, $array);
								}
							}
						}
					}
					else
					{
						header('refresh: 0; url=./admin_templates.php');
						exit;
					}
				}
				if ($_GET['cmd'] == 'delete')
				{
					$set = $suit->mysql->escape($_GET['set']);
					$template = $suit->mysql->escape($_GET['template']);
					
					$admintemplates_options = array(
					'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
					);
					
					$admintemplates = $suit->mysql->select(
					'templates',
					'*',
					$admintemplates_options
					);
					
					if ($admintemplates)
					{
						while ($row = mysql_fetch_assoc($admintemplates))
						{
							$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_deletetemplate', $layer+1);
							$list .= $layered[$layer+1];
							$lcontent = $suit->language->getLanguage('deleteconfirm');
							$lcontent = str_replace('{1}', $row['title'], $lcontent);
							$array = Array
							(
								Array
								(
									'{1}', $lcontent
								),
					
								Array
								(
									'{2}', $set
								),

								Array
								(
									'{3}', $template
								)
							);
							$list = $suit->templates->implosion($list, $array);
						}
					}
				}
				if ($_GET['cmd'] == 'clone')
				{
					$set = $suit->mysql->escape($_GET['set']);
					$template = $suit->mysql->escape($_GET['template']);
					
					$admintemplates_options = array(
					'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
					);
					
					$admintemplates = $suit->mysql->select(
					'templates',
					'*',
					$admintemplates_options
					);
					
					if ($admintemplates)
					{
						while ($row = mysql_fetch_assoc($admintemplates))
						{
							$admintemplateset_options = array(
							'where' => 'id = \'' . $set . '\''
							);
							
							$admintemplateset = $suit->mysql->select('templates', '*', $admintemplateset_options);
							
							if ($admintemplateset)
							{
								while ($row2 = mysql_fetch_assoc($admintemplateset))
								{
									$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_clonetemplate', $layer+1);
									$list .= $layered[$layer+1];
									$layered[$layer+2] = $suit->templates->getDynamicTemplate('admin_dynamictemplatescodeskeleton', $layer+2);
									$layered[$layer+3] = $suit->templates->getDynamicTemplate('admin_dynamictemplatescode', $layer+3);
									if ($row['dynamic'] == 1)
									{
										$file = PATH_HOME . 'dynamic/' . $row2['title'] . '/' . $row['title'] . '.php';
										$fh = fopen($file, 'r');
										if (filesize($file))
										{
											$code = fread($fh, filesize($file));
										}
										else
										{
											$code = '';
										}
										fclose($fh);
										$layered[$layer+3] = str_replace('{1}', htmlentities($code), $layered[$layer+3]);
										$layered[$layer+2] = str_replace('{1}', $layered[$layer+3], $layered[$layer+2]);
									}
									else
									{
										$layered[$layer+3] = str_replace('{1}', '', $layered[$layer+3]);
										$layered[$layer+2] = str_replace('{1}', '', $layered[$layer+2]);
									}
									if (isset($_GET['error']) && ($_GET['error'] == 'missingtitle'))
									{
										$lcontent = $suit->language->getLanguage('missingtitle');
									}
									else
									{
										$lcontent = '';
									}
									if ($row['dynamic'] == 0)
									{
										$select = ' selected';
									}
									else
									{
										$select = '';
									}
									if ($row['dynamic'] == 1)
									{
										$select2 = ' selected';
									}
									else
									{
										$select2 = '';
									}
									$array = Array
									(
										Array
										(
											'{1}', $lcontent
										),
							
										Array
										(
											'{2}', $row['parent']
										),
		
										Array
										(
											'{3}', $row['id']
										),

										Array
										(
											'{4}', htmlentities($row['title'])
										),

										Array
										(
											'{5}', htmlentities($row['content'])
										),

										Array
										(
											'{6}', $select
										),

										Array
										(
											'{7}', $select2
										),

										Array
										(
											'{8}', $layered[$layer+2]
										)
									);
									$list = $suit->templates->implosion($list, $array);
								}
							}
						}
					}
					else
					{
						header('refresh: 0; url=./admin_templates.php');
						exit;
					}
				}
			}
		}
	}
	$layered[$layer] = str_replace('{1}', $list, $layered[$layer]);
}
else
{
	$layered[$layer] = str_replace('{1}', '', $layered[$layer]);
}
?>
