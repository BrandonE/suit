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
ob_start();
if ($suit->loggedIn() == 2)
{
	$list = '';
	if (isset($_GET['cmd']))
	{
		if (isset($_POST['edit']) && $_GET['cmd'] == 'updatetemplate')
		{
				$template = $suit->mysql->escape($_POST['id']);
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
							$layered[$layer+1] = $suit->templates->getTemplate('admin_templateupdated', $GLOBALS['id'], $GLOBALS['pass'], $layer+1, 0);
							$list .= $layered[$layer+1];
						}
						else
						{
							$layered[$layer+1] = $suit->templates->getTemplate('admin_dynamictemplateupdated', $GLOBALS['id'], $GLOBALS['pass'], $layer+1, 0);
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
		else
		{
			header('refresh: 0; url=./admin_templates.php');
			exit;
		}
	}
	else
	{
		if (!isset($_GET['set']))
		{
			$parentget_options = array(
			'where' => 'parent = \'0\'',
			);
			
			$parentget = $suit->mysql->select(
			'templates',
			'*',
			$parentget_options
			);
			
			if ($parentget)
			{
				while ($row = mysql_fetch_assoc($parentget))
				{
					$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_selecttemplateset', $GLOBALS['id'], $GLOBALS['pass'], $layer+1);
					$list .= $layered[$layer+1];
					$list = str_replace('{1}', $row['id'], $list);
					$list = str_replace('{2}', htmlspecialchars($row['title']), $list);
					
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
							$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_associatedthemes', $GLOBALS['id'], $GLOBALS['pass'], $layer+1);
							$themes = $layered[$layer+1];
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
				$set = $suit->mysql->escape($_GET['set']);
				$templatesetexists_options = array('where' => 'id = \'' . $set . '\' AND parent = \'0\'');
				$templatesetexists = $suit->mysql->select(
				'' . TBL_PREFIX . 'templates',
				'*',
				$templatesetexists_options
				);
				
				if ($templatesetexists)
				{
					$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_selecttemplateskeleton', $GLOBALS['id'], $GLOBALS['pass'], $layer+1);
					$page = $layered[$layer+1];
					$lcontent = $suit->language->getLanguage('templates', $GLOBALS['id'], $GLOBALS['pass']);
					$page = str_replace('{1}', $lcontent, $page);
					$query = 'SELECT * FROM ' . TBL_PREFIX . 'templates WHERE parent = \'' . $set . '\' AND dynamic = \'0\' ORDER BY `title`';
					$result = mysql_query($query);
					if ($result)
					{
						while ($row = mysql_fetch_assoc($result))
						{
							$layered[$layer+1] = $suit->templates->getTemplate('admin_selecttemplate', $GLOBALS['id'], $GLOBALS['pass'], $layer+1, 0);
							$list .= $layered[$layer+1];
							$list = str_replace('{1}', $set, $list);
							$list = str_replace('{2}', $row['id'], $list);
							$list = str_replace('{3}', htmlspecialchars($row['title']), $list);
						}
					}
					$page = str_replace('{2}', $list, $page);
					$list = '';
					$lcontent = $suit->language->getLanguage('dynamictemplates', $GLOBALS['id'], $GLOBALS['pass']);
					$page = str_replace('{3}', $lcontent, $page);
					$query = 'SELECT * FROM ' . TBL_PREFIX . 'templates WHERE parent = \'' . $set . '\' AND dynamic = \'1\' ORDER BY `title`';
					$result = mysql_query($query);
					if ($result)
					{
						while ($row = mysql_fetch_assoc($result))
						{
							$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_selecttemplate', $GLOBALS['id'], $GLOBALS['pass'], $layer+1);
							$list .= $layered[$layer+1];
							$list = str_replace('{1}', $set, $list);
							$list = str_replace('{2}', $row['id'], $list);
							$list = str_replace('{3}', htmlspecialchars($row['title']), $list);
						}
					}
					$page = str_replace('{4}', $list, $page);
					$list = $page;
				}
				else
				{
					header('refresh: 0; url=./admin_templates.php');
					exit;
				}
			}
			else
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
						
						$admintemplateset = $suit->mysql->select(
						'templates',
						'*',
						$admintemplateset_options
						);
						
						if ($admintemplateset)
						{
							while ($row2 = mysql_fetch_assoc($admintemplateset))
							{
								$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_edittemplate', $GLOBALS['id'], $GLOBALS['pass'], $layer+1);
								$list .= $layered[$layer+1];
								$layered[$layer+2] = $suit->templates->getDynamicTemplate('admin_dynamictemplatescodeskeleton', $GLOBALS['id'], $GLOBALS['pass'], $layer+2);
								$layered[$layer+3] = $suit->templates->getDynamicTemplate('admin_dynamictemplatescode', $GLOBALS['id'], $GLOBALS['pass'], $layer+3);
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
								$list = str_replace('{4}', $layered[$layer+2], $list);
								$list = str_replace('{1}', $row['id'], $list);
								$list = str_replace('{2}', htmlentities($row['title']), $list);
								$list = str_replace('{3}', htmlentities($row['content']), $list);
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
	$layered[$layer] = str_replace('{1}', $list, $layered[$layer]);
}
else
{
	$layered[$layer] = str_replace('{1}', '', $layered[$layer]);
}
?>
