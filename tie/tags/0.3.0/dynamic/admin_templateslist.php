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
$list = '';
if (isset($_POST['submit']) && isset($_GET['cmd']))
{
	if ($_GET['cmd'] == 'update')
	{
		$template = $system->mysql->escape($_POST['id']);
		$templatecheck_options = array(
		'where' => 'id = \'' . $template . '\''
		);
		
		$templatecheck = $system->mysql->select(
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
					$system->templates->getTemplate('admin_templateupdated', $id, $pass, $layer+1, 0);
					$list .= $GLOBALS['layered'][$layer+1];
				}
				else
				{
					$system->templates->getTemplate('admin_dynamictemplateupdated', $id, $pass, $layer+1, 0);
					$list .= $GLOBALS['layered'][$layer+1];
				}
			}
		}
	}
}
else
{
	if (!isset($_GET['set']))
	{
		$parentget_options = array(
		'where' => 'parent = \'0\'',
		);
		
		$parentget = $system->mysql->select(
		'templates',
		'*',
		$parentget_options
		);
		
		if ($parentget)
		{
			while ($row = mysql_fetch_assoc($parentget))
			{
				$system->templates->getTemplate('admin_selecttemplateset', $id, $pass, $layer+1, 0);
				$list .= $GLOBALS['layered'][$layer+1];
				$list = str_replace('{1}', $row['id'], $list);
				$list = str_replace('{2}', htmlspecialchars($row['title']), $list);
				
				$adminthemes_options = array(
				'where' => 'template = \'' . $row['id'] . '\''
				);
				
				$adminthemes = $system->mysql->select(
				'themes',
				'*',
				$adminthemes_options
				);
				
				if ($adminthemes)
				{
					while ($row2 = mysql_fetch_assoc($adminthemes))
					{
						$system->templates->getTemplate('admin_associatedthemes', $id, $pass, $layer+1, 0);
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
			$set = $system->mysql->escape($_GET['set']);
			$query = 'SELECT * FROM ' . $tbl_prefix . 'templates WHERE id = \'' . $set . '\'';
			$result = mysql_query($query);
			if ($result)
			{
				$system->templates->getTemplate('admin_selecttemplateskeleton', $id, $pass, $layer+1, 0);
				$page = $GLOBALS['layered'][$layer+1];
				$system->language->getLanguage('templates', $id, $pass);
				$page = str_replace('{1}', $GLOBALS['lcontent'], $page);
				$query = 'SELECT * FROM ' . $tbl_prefix . 'templates WHERE parent = \'' . $set . '\' AND dynamic = \'0\' ORDER BY `title`';
				$result = mysql_query($query);
				if ($result)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$system->templates->getTemplate('admin_selecttemplate', $id, $pass, $layer+1, 0);
						$list .= $GLOBALS['layered'][$layer+1];
						$list = str_replace('{1}', $set, $list);
						$list = str_replace('{2}', $row['id'], $list);
						$list = str_replace('{3}', htmlspecialchars($row['title']), $list);
					}
				}
				$page = str_replace('{2}', $list, $page);
				$list = '';
				$system->language->getLanguage('dynamictemplates', $id, $pass);
				$page = str_replace('{3}', $GLOBALS['lcontent'], $page);
				$query = 'SELECT * FROM ' . $tbl_prefix . 'templates WHERE parent = \'' . $set . '\' AND dynamic = \'1\' ORDER BY `title`';
				$result = mysql_query($query);
				if ($result)
				{
					while ($row = mysql_fetch_assoc($result))
					{
						$system->templates->getTemplate('admin_selecttemplate', $id, $pass, $layer+1, 0);
						$list .= $GLOBALS['layered'][$layer+1];
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
			'templates',
			'*',
			$admintemplates_options
			);
			
			if ($admintemplates)
			{
				while ($row = mysql_fetch_assoc($admintemplates))
				{
					$system->templates->getTemplate('admin_edittemplate', $id, $pass, $layer+1, 0);
					$list .= $GLOBALS['layered'][$layer+1];
					$list = str_replace('{1}', $row['id'], $list);
					$list = str_replace('{2}', htmlentities($row['title']), $list);
					$list = str_replace('{3}', htmlentities($row['content']), $list);
				}
			}
			else
			{
				header('refresh: 0; url=./templates.php');
				exit;
			}
		}
	}
}
$GLOBALS['layered'][$layer] = str_replace('{1}', $list, $GLOBALS['layered'][$layer]);
?>
