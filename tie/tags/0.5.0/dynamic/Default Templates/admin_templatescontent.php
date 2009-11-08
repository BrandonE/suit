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
	//It's always safer to set a variable before use.
	$list = '';
	//The valid list of pages that a user can pass to the $_GET['cmd'] variable.
	$pages = array('edittemplate', 'addtemplate', 'deletetemplate', 'clonetemplate', 'addset', 'cache');
	
	if (isset($_GET['cmd']) && in_array($_GET['cmd'], $pages))
	{
		//Add template.
		if ($_GET['cmd'] == 'addtemplate')
		{
			if (isset($_POST['add']))
			{
				header('refresh: 0; url=./admin_templates.php?cmd=addtemplate&set=' . intval($_POST['set']) . '&template=' . $suit->mysql->escape($_POST['title']) . '&dynamic=' . intval($_POST['dynamic']));
				exit;
			}
			else
			{
				if ($_GET['dynamic'] == 0)
				{
					$admin_templateadded = $suit->templates->getTemplate('admin_templateadded', $rows);
					$list .= $admin_templateadded;
				}
				else
				{
					$admin_dynamictemplateadded = $suit->templates->getTemplate('admin_dynamictemplateadded', $rows);
					$list .= $admin_dynamictemplateadded;
				}
			}
		}
		elseif ($_GET['cmd'] == 'edittemplate')
		{
			if (isset($_POST['edit']))
			{
				header('refresh: 0; url=./admin_templates.php?cmd=edittemplate&template=' . intval($_POST['template']));
				exit;
			}
			else
			{
				$template = intval($_GET['template']);
				$templatecheck_options = array('where' => 'id = \'' . $template . '\'');
				$templatecheck = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $templatecheck_options);
				
				if ($templatecheck)
				{
					while ($row = mysql_fetch_assoc($templatecheck))
					{
						if ($row['dynamic'] == 0)
						{
							$admin_templateedited = $suit->templates->getTemplate('admin_templateedited', $rows);
							$list .= $admin_templateedited;
						}
						else
						{
							$admin_dynamictemplateedited = $suit->templates->getTemplate('admin_dynamictemplateedited', $rows);
							$list .= $admin_dynamictemplateedited;
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
		/**
		Deleting templates.
		**/
		elseif ($_GET['cmd'] == 'deletetemplate')
		{
			if (isset($_POST['delete']))
			{
				header('refresh: 0; url=./admin_templates.php?cmd=deletetemplate');
				exit;
			}
			else
			{
				$list .= $suit->templates->getTemplate('admin_templatedeleted', $rows);
			}
		}
		/**
		Cloning templates
		**/
		elseif ($_GET['cmd'] == 'clonetemplate')
		{
			if (isset($_POST['clone']))
			{
				header('refresh: 0; url=./admin_templates.php?cmd=clonetemplate');
				exit;
			}
			else
			{
				$list .= $suit->templates->getTemplate('admin_templatecloned', $rows);
			}
		}
		
		/**
		Addition of template sets.
		**/
		elseif ($_GET['cmd'] == 'addset')
		{
			//Load the dynamic template for adding the set.
			$list .= $suit->templates->getTemplate('admin_addset', $rows);
			//Check for any errors.
			if (isset($_GET['error']))
			{
				//We'll use a switch() statement to determine what action to take for these errors.
				//When we have our error, we'll load the language string for it.
				switch ($_GET['error'])
				{
					case 'missingtitle':
						$lcontent = $suit->language->getLanguage('missingtitle'); break;
					case 'duplicatetitle':
						$lcontent = $suit->language->getLanguage('duplicatetitle'); break;
					default:
						$lcontent = $suit->language->getLanguage('undefinederror'); break;
				}
				//Replace the value of $list with what we concluded in the error switch() statement.
				$list = str_replace('{1}', $lcontent, $list);
			}
			else
			{
				$list = str_replace('{1}', '', $list);
			}
		}
		//Clear cache.
		elseif ($_GET['cmd'] == 'cache')
		{
			if (isset($_POST['cache']))
			{
				header('refresh: 0; url=./admin_templates.php?cmd=cache&set=' . intval($_POST['set']));
				exit;
			}
			else
			{
				$admin_cachecleared = $suit->templates->getTemplate('admin_cachecleared', $rows);
				$list .= $admin_cachecleared;
			}
		}
	}
	else
	{
		if (!isset($_GET['set']))
		{
			$pages = array('setadded', 'setrenamed', 'setdeleted', 'setcloned');
			
			if (isset($_GET['cmd']) && in_array($_GET['cmd'], $pages))
			{
				/**
				The set's been added
				**/
				if ($_GET['cmd'] == 'setadded')
				{
					if (isset($_POST['addset']))
					{
						header('refresh: 0; url=./admin_templates.php?cmd=setadded&submitted=1');
						exit;
					}
					else
					{
						$message = $suit->templates->getTemplate('admin_setadded', $rows);
					}
				}
				/**
				The set's been renamed
				**/
				elseif ($_GET['cmd'] == 'setrenamed')
				{
					if (isset($_POST['renameset']))
					{
						header('refresh: 0; url=./admin_templates.php?cmd=setrenamed&submitted=1');
						exit;
					}
					else
					{
						$message = $suit->templates->getTemplate('admin_setrenamed', $rows);
					}
				}
				/**
				The set's been deleted
				**/
				elseif ($_GET['cmd'] == 'setdeleted')
				{
					if (isset($_POST['deleteset']))
					{
						header('refresh: 0; url=./admin_templates.php?cmd=setdeleted&submitted=1');
						exit;
					}
					else
					{
						$message = $suit->templates->getTemplate('admin_setdeleted', $rows);
					}
				}
				
				/**
				The Set's been cloned?
				**/
				if ($_GET['cmd'] == 'setcloned')
				{
					if (isset($_POST['cloneset']))
					{
						//Redirect to this same page, but with POST Data not being intact.
						header('refresh: 0; url=./admin_templates.php?cmd=setcloned&submitted=1');
						exit;
					}
					else
					{
						//If the POST Data was not intact, we'll display a success message to the user.
						$message = $suit->templates->getTemplate('admin_setcloned', $rows);
					}
				}
			}
			else
			{
				$message = '';
			}
			
			$admin_selecttemplatesetskeleton = $suit->templates->getTemplate('admin_selecttemplatesetskeleton', $rows);
			$page = $admin_selecttemplatesetskeleton;
			
			$parentget_options = array(
			'where' => 'parent = \'0\'',
			);
			
			$parentget = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $parentget_options);
			
			if ($parentget)
			{
				while ($row = mysql_fetch_assoc($parentget))
				{
					$admin_selecttemplateset = $suit->templates->getTemplate('admin_selecttemplateset', $rows);
					$list .= $admin_selecttemplateset;
					$adminthemes_options = array(
					'where' => 'template = \'' . $row['id'] . '\''
					);
					
					$adminthemes = $suit->mysql->select(TBL_PREFIX . 'themes', '*', $adminthemes_options);
					
					if ($adminthemes)
					{
						while ($row2 = mysql_fetch_assoc($adminthemes))
						{
							$admin_associatedthemes = $suit->templates->getTemplate('admin_associatedthemes', $rows);
							$themes = $admin_associatedthemes;
							$themes = str_replace('{1}', htmlspecialchars($row2['title']), $themes);
						}
					}
					else
					{
						$themes = '';
					}
					$array = Array
					(
						array('{1}', $row['id']),
						array('{2}', htmlspecialchars($row['title'])),
						array('{3}', $themes)
					);
					$list = $suit->templates->implosion($list, $array);
				}
			}
			$array = array
			(
				array('{1}', $message),
				array('{2}', $list)
			);
			$page = $suit->templates->implosion($page, $array);			
			$list = $page;
		}
		else
		{
			if (!isset($_GET['template']))
			{
				$pages = array('view', 'add', 'renameset', 'deleteset', 'cloneset', 'cache');
				if (isset($_GET['cmd']) && in_array($_GET['cmd'], $pages))
				{
					if ($_GET['cmd'] == 'view')
					{
						$set = intval($_GET['set']);
						$templatesetexists_options = array('where' => 'id = \'' . $set . '\' AND parent = \'0\'');
						$templatesetexists = $suit->mysql->select('' . TBL_PREFIX . 'templates', '*', $templatesetexists_options);
						
						if ($templatesetexists)
						{
							$page = $suit->templates->getTemplate('admin_selecttemplateskeleton', $rows);
							$lcontent = $suit->language->getLanguage('templates');
							$admin_getnondynamic_options = array(
							'where' => 'parent = \'' . $set . '\' AND dynamic = \'0\'',
							'orderby' => 'title'
							);
							
							$admin_getnondynamic = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $admin_getnondynamic_options);
							if ($admin_getnondynamic)
							{
								while ($row = mysql_fetch_assoc($admin_getnondynamic))
								{
									$list .= $suit->templates->getTemplate('admin_selecttemplate', $rows);
									$array = array
									(
										array('{1}', htmlspecialchars($row['title'])),
										array('{2}', $set),
										array('{3}', $row['id'])
									);
									$list = $suit->templates->implosion($list, $array);
								}
							}
							$list2 = '';
							$lcontent2 = $suit->language->getLanguage('dynamictemplates');
							
							$getparent_options = array(
							'where' => 'parent = \'' . $set . '\' AND dynamic = \'1\'',
							'orderby' => 'title'
							);
							
							$getparent = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $getparent_options);
							
							if ($getparent)
							{
								while ($row = mysql_fetch_assoc($getparent))
								{
									$admin_selecttemplate = $suit->templates->getTemplate('admin_selecttemplate', $rows);
									$list2 .= $admin_selecttemplate;
									$array = array
									(
										array('{1}', htmlspecialchars($row['title'])),
										array('{2}', $set),
										array('{3}', $row['id'])
									);
									$list2 = $suit->templates->implosion($list2, $array);
								}
							}
							$array = array
							(
								array('{1}', $set),
								array('{2}', $lcontent),
								array('{3}', $list),
								array('{4}', $lcontent2),
								array('{5}', $list2)
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
							$admin_addtemplate = $suit->templates->getTemplate('admin_addtemplate', $rows);
							$list .= $admin_addtemplate;
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
								array('{1}', $lcontent),
								array('{2}', $set)
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
						
						$setcheck = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $setcheck_options);
						
						if ($setcheck)
						{
							while ($row = mysql_fetch_assoc($setcheck))
							{
								$admin_renameset = $suit->templates->getTemplate('admin_renameset', $rows);
								$list .= $admin_renameset;
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
									array('{1}', $lcontent),
									array('{2}', $_GET['set']),
									array('{3}', htmlentities($row['title']))
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
						$set = intval($_GET['set']);
						
						$admintemplates_options = array('where' => 'id = \'' . $set . '\'');
						$admintemplates = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $admintemplates_options);
						
						if ($admintemplates)
						{
							while ($row = mysql_fetch_assoc($admintemplates))
							{
								$list .= $suit->templates->getTemplate('admin_deleteset', $rows);
								$lcontent = $suit->language->getLanguage('deleteconfirm');
								$lcontent = str_replace('{1}', $row['title'], $lcontent);
								$array = array
								(
									array('{1}', $lcontent),
									array('{2}', $set)
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
						$set = intval($_GET['set']);
						$setcheck_options = array(
						'where' => 'id = \'' . $set . '\''
						);
						
						$setcheck = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $setcheck_options);
						
						if ($setcheck)
						{
							while ($row = mysql_fetch_assoc($setcheck))
							{
								$admin_cloneset = $suit->templates->getTemplate('admin_cloneset', $rows);
								$list .= $admin_cloneset;
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
								$array = array
								(
									array('{1}', $lcontent),
									array('{2}', $set),
									array('{3}', htmlentities($row['title']))
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
					$set = intval($_GET['set']);
					$template = intval($_GET['template']);
					
					$admintemplates_options = array(
					'where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\''
					);
					
					$admintemplates = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $admintemplates_options);
					
					if ($admintemplates)
					{
						while ($row = mysql_fetch_assoc($admintemplates))
						{
							$admintemplateset_options = array(
							'where' => 'id = \'' . $set . '\''
							);
							
							$admintemplateset = $suit->mysql->select(TBL_PREFIX . 'templates', '*',$admintemplateset_options);
							
							if ($admintemplateset)
							{
								while ($row2 = mysql_fetch_assoc($admintemplateset))
								{
									$admin_edittemplate = $suit->templates->getTemplate('admin_edittemplate', $rows);
									$list .= $admin_edittemplate;
									$admin_dynamictemplatescodeskeleton = $suit->templates->getTemplate('admin_dynamictemplatescodeskeleton', $rows);
									$admin_dynamictemplatescode = $suit->templates->getTemplate('admin_dynamictemplatescode', $rows);
									//If the template is dynamic, then we'll go ahead and load the dynamic template file along with the actual template as well.
									if ($row['dynamic'] == 1)
									{
										$file = PATH_HOME . 'dynamic/' . $row2['title'] . '/' . $row['title'] . '.php';
										if (!file_exists($file))
										{
											//The file doesn't exist. We'll go ahead and create an empty file.
											$create_dynamic = fopen($file, 'w') or die($lcontent);
											fclose($create_dynamic);
											chmod($file, 0666); //CHMOD the file to be writable by our script.
										}
										//Re-create a file handler by opening up the previous file we were playing with.
										$fh = fopen($file, 'r');
										
										//Grab the file size, and then store the contents of it inside of the $code variable.
										if (filesize($file))
										{
											$code = fread($fh, filesize($file));
										}
										else
										{
											$code = '';
										}
										//We're done; let's close the file out now.
										fclose($fh);
										
										$admin_dynamictemplatescode = str_replace('{1}', htmlentities($code), $admin_dynamictemplatescode);
										$admin_dynamictemplatescodeskeleton = str_replace('{1}', $admin_dynamictemplatescode, $admin_dynamictemplatescodeskeleton);
									}
									else
									{
										$admin_dynamictemplatescode = str_replace('{1}', '', $admin_dynamictemplatescode);
										$admin_dynamictemplatescodeskeleton = str_replace('{1}', '', $admin_dynamictemplatescodeskeleton);
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
									$array = array
									(
										array('{1}', $lcontent),
										array('{2}', $row['parent']),
										array('{3}', $row['id']),
										array('{4}', htmlentities($row['title'])),
										array('{5}', htmlentities($row['content'])),
										array('{6}', $select),
										array('{7}', $select2),
										array('{8}', $admin_dynamictemplatescodeskeleton)
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
					
					$admintemplates = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $admintemplates_options);
					
					if ($admintemplates)
					{
						while ($row = mysql_fetch_assoc($admintemplates))
						{
							$admin_deletetemplate = $suit->templates->getTemplate('admin_deletetemplate', $rows);
							$list .= $admin_deletetemplate;
							$lcontent = $suit->language->getLanguage('deleteconfirm');
							$lcontent = str_replace('{1}', $row['title'], $lcontent);
							$array = array
							(
								array('{1}', $lcontent),
								array('{2}', $set),
								array('{3}', $template)
							);
							$list = $suit->templates->implosion($list, $array);
						}
					}
				}
				
				if ($_GET['cmd'] == 'clone')
				{
					$set = $suit->mysql->escape($_GET['set']);
					$template = $suit->mysql->escape($_GET['template']);
					
					$admintemplates_options = array('where' => 'id = \'' . $template . '\' AND parent = \'' . $set . '\'');
					$admintemplates = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $admintemplates_options);
					
					if ($admintemplates)
					{
						while ($row = mysql_fetch_assoc($admintemplates))
						{
							$admintemplateset_options = array('where' => 'id = \'' . $set . '\'');
							$admintemplateset = $suit->mysql->select(TBL_PREFIX . 'templates', '*', $admintemplateset_options);
							
							if ($admintemplateset)
							{
								while ($row2 = mysql_fetch_assoc($admintemplateset))
								{
									$list .= $suit->templates->getTemplate('admin_clonetemplate', $rows);
									$admin_dynamictemplatescodeskeleton = $suit->templates->getTemplate('admin_dynamictemplatescodeskeleton', $rows);
									$admin_dynamictemplatescode = $suit->templates->getTemplate('admin_dynamictemplatescode', $rows);
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
										$admin_dynamictemplatescode = str_replace('{1}', htmlentities($code), $admin_dynamictemplatescode);
										$admin_dynamictemplatescodeskeleton = str_replace('{1}', $admin_dynamictemplatescode, $admin_dynamictemplatescodeskeleton);
									}
									else
									{
										$admin_dynamictemplatescode = str_replace('{1}', '', $admin_dynamictemplatescode);
										$admin_dynamictemplatescodeskeleton = str_replace('{1}', '', $admin_dynamictemplatescodeskeleton);
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
									$array = array
									(
										array('{1}', $lcontent),
										array('{2}', $row['parent']),
										array('{3}', $row['id']),
										array('{4}', htmlentities($row['title'])),
										array('{5}', htmlentities($row['content'])),
										array('{6}', $select),
										array('{7}', $select2),
										array('{8}', $admin_dynamictemplatescodeskeleton)
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
	$output = str_replace('{1}', $list, $output);
}
else
{
	$output = str_replace('{1}', '', $output);
}
?>
