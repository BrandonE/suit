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
if ($suit->templates->vars['local']->loggedIn() == 2)
{
	//It's always safer to set a variable before use.
	$list = '';
	$pages = array('add');
	if (!isset($_GET['template']))
	{
		if (isset($_GET['cmd']) && in_array($_GET['cmd'], $pages))
		{
			if ($_GET['cmd'] == 'add')
			{
				$templatesetexists_options = array();
				$templatesetexists = $suit->db->select(TBL_PREFIX . 'templates', '*', $templatesetexists_options);
				if ($templatesetexists)
				{
					$admin_templates_add = $suit->templates->getTemplate('admin_templates_add');
					$list .= $admin_templates_add;
					if (isset($suit->templates->vars['error']))
					{
						//We'll use a switch() statement to determine what action to take for these errors.
						//When we have our error, we'll load the language string for it.
						switch ($suit->templates->vars['error'])
						{
							case 'missingtitle':
								$lcontent = $suit->templates->vars['local']->getPhrase('missingtitle'); break;
							case 'duplicatetitle':
								$lcontent = $suit->templates->vars['local']->getPhrase('duplicatetitle'); break;
							default:
								$lcontent = $suit->templates->vars['local']->getPhrase('undefinederror'); break;
						}
						//Replace the value of $list with what we concluded in the error switch() statement.
					}
					else
					{
						$lcontent = '';
					}
					$title = '';
					$content = '';
					$preload = '';
					$postload = '';
					//Template Cloning
					if (isset($_GET['id']))
					{
						$id = intval($_GET['id']);
						$locate_options = array
						(
							'where' => 'id = \'' . $id . '\''
						);
						$locate = $suit->db->select(TBL_PREFIX . 'templates', '*', $locate_options); 
						if ($locate)
						{
							while ($row = mysql_fetch_assoc($locate))
							{
								$title = $row['title'];
								$content = $row['content'];
								$filepath = $suit->templates->checkFile($row['title'], 'preload');
								$preload = file_get_contents($filepath);
								$filepath = $suit->templates->checkFile($row['title'], 'postload');
								$postload = file_get_contents($filepath);
							}
						}
					}
					if (isset($suit->templates->vars['error']))
					{
						$content = $suit->templates->vars['content'];
						$preload = $suit->templates->vars['preload'];
						$postload = $suit->templates->vars['postload'];
					}
					$array = Array
					(
						array('<message>', $lcontent),
						array('<content>', htmlentities($content)),
						array('<preload>', htmlentities($preload)),
						array('<postload>', htmlentities($postload)),
						array('<title>', htmlentities($title))
					);
					$list = $suit->templates->replace($list, $array);
				}
				else
				{
					$suit->templates->getTemplate('badrequest');
                                }
			}
		}
		else
		{
			$templateexists_options = array
			(
				'orderby' => 'title',
				'orderby_type' => 'asc'
			);
			$templateexists = $suit->db->select(TBL_PREFIX . 'templates', '*', $templateexists_options);
			if ($templateexists)
			{
				$page = $suit->templates->getTemplate('admin_templates_select_skeleton');
				$admin_templates_select = $suit->templates->getTemplate('admin_templates_select');
				while ($row = mysql_fetch_assoc($templateexists))
				{
					$list .= $admin_templates_select;
					$array = array
					(
						array('<title>', htmlspecialchars($row['title'])),
						array('<id>', $row['id'])
					);
					$list = $suit->templates->replace($list, $array);
				}
				//The valid list of pages that a user can pass to the $_GET['cmd'] variable.
				$pages = array('edited', 'added', 'deleted');
				if (isset($_GET['cmd']) && in_array($_GET['cmd'], $pages))
				{
					switch ($_GET['cmd'])
					{
						case 'added':
							$language = 'addedsuccessfully';
							break;
						case 'edited':
							$language = 'editedsuccessfully';
							break;
						case 'deleted':
							$language = 'deletedsuccessfully';
							break;
					}
					$success = $suit->templates->getTemplate('success');
					$success = str_replace('<message>', $suit->templates->vars['local']->getPhrase($language), $success);
				}
				else
				{
					$success = '';
				}
				$array = array
				(
					array('<list>', $list),
					array('<message>', $success)
				);
				$page = $suit->templates->replace($page, $array);
				$list = $page;
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
			}
		}
	}
	else
	{
		if ($_GET['cmd'] == 'edit')
		{
			if (isset($_GET['template']))
			{
				$template = intval($_GET['template']);
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
			}
			
			$admintemplates_options = array
			(
				'where' => 'id = \'' . $template . '\''
			);
			$admintemplates = $suit->db->select(TBL_PREFIX . 'templates', '*', $admintemplates_options);	
			if ($admintemplates)
			{
				$admin_templates_edit = $suit->templates->getTemplate('admin_templates_edit');
				while ($row = mysql_fetch_assoc($admintemplates))
				{
					$list .= $admin_templates_edit;
					if (isset($suit->templates->vars['error']))
					{
						//We'll use a switch() statement to determine what action to take for these errors.
						//When we have our error, we'll load the language string for it
						switch ($suit->templates->vars['error'])
						{
							case 'missingtitle':
								$lcontent = $suit->templates->vars['local']->getPhrase('missingtitle'); break;
							case 'duplicatetitle':
								$lcontent = $suit->templates->vars['local']->getPhrase('duplicatetitle'); break;
							default:
								$lcontent = $suit->templates->vars['local']->getPhrase('undefinederror'); break;
						}
						//Replace the value of $list with what we concluded in the error switch() statement.
					}
					else
					{
						$lcontent = '';
					}
					if (isset($suit->templates->vars['error']))
					{
						$content = $suit->templates->vars['content'];
						$preload = $suit->templates->vars['preload'];
						$postload = $suit->templates->vars['postload'];
					}
					else
					{
						$content = $row['content'];
					}
					$filepath = $suit->templates->checkFile($row['title'], 'preload');
					$preload = file_get_contents($filepath);
					$filepath = $suit->templates->checkFile($row['title'], 'postload');
					$postload = file_get_contents($filepath);					
					$array = array
					(
						array('<message>', $lcontent),
						array('<template>', $row['id']),
						array('<title>', htmlentities($row['title'])),
						array('<preload>', htmlentities($preload)),
						array('<content>', htmlentities($content)),
						array('<postload>', htmlentities($postload))
					);
					$list = $suit->templates->replace($list, $array);
				}
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
			}
		}
		if ($_GET['cmd'] == 'delete')
		{
			if (isset($_GET['template']))
			{
				$template = intval($_GET['template']);
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
				exit;
			}
			
			$admintemplates_options = array(
			'where' => 'id = \'' . $template . '\''
			);
			$admintemplates = $suit->db->select(TBL_PREFIX . 'templates', '*', $admintemplates_options);
			if ($admintemplates)
			{
				$admin_templates_delete = $suit->templates->getTemplate('admin_templates_delete');
				while ($row = mysql_fetch_assoc($admintemplates))
				{
					$list .= $admin_templates_delete;
					$lcontent = $suit->templates->vars['local']->getPhrase('deleteconfirm');
					$lcontent = str_replace('<template>', $row['title'], $lcontent);
					$array = array
					(
						array('<message>', $lcontent),
						array('<template>', $template)
					);
					$list = $suit->templates->replace($list, $array);
				}
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
				exit;
			}
		}
	}
	$output = str_replace('<admin_templates>', $list, $output);
}
else
{
	$output = str_replace('<admin_templates>', $output, $output);
}
?>
