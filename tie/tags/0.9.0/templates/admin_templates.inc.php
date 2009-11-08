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
$suit->templates->getTemplate('tie');
$tie = &$suit->templates->vars['tie'];
$output = $tie->parsePhrases($output);
if ($tie->loggedIn() == 2)
{
	if (isset($_GET['start']))
	{
		$start = intval($_GET['start']);
	}
	else
	{
		$start = 0;
	}
	if (isset($_GET['limit']))
	{
		$limit = intval($_GET['limit']);
	}
	else
	{
		$limit = 10;
	}
	if (isset($_GET['search']) && (strlen($_GET['search']) >= 4))
	{
		$search = $suit->db->escape($_GET['search']);
	}
	else
	{
		$search = '';
	}
	$orderby_type = 'asc';
	if (isset($_GET['orderby']) && ($_GET['orderby'] == 'desc'))
	{
		$orderby_type = 'desc';
	}
	if (isset($_GET['select']) && ($_GET['select'] == 'true'))
	{
		$select = true;
		$selectdisplay = 'true';
	}
	else
	{
		$select = false;
		$selectdisplay = 'false';
	}
	if (!empty($_POST))
	{
		$redirect = $suit->templates->getTemplate('path_url') . '/index.php?page=admin_templates&start=' . $start . '&limit=' . $limit . '&orderby=' . $orderby_type . '&select=' . $selectdisplay . '&search=' . $search;
	}
	if (isset($_POST['add']) && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['code']))
	{
		$title = $suit->db->escape($tie->magic($_POST['title']));
		$content = $suit->db->escape($tie->magic($_POST['content']));
		$code = trim($tie->magic($_POST['code']));
		$error = $tie->addSubmit('title = \'' . $title . '\'', DB_PREFIX . 'templates', $title);
		if (!$error)
		{
			$query = 'INSERT INTO ' . DB_PREFIX . 'templates VALUES (\'\', \'' . $title . '\', \'' . $content . '\')';
			mysql_query($query);
			$code = $tie->breakConvert($code, PHP_OS);
			$filepath = $suit->templates->checkFile($title);
			file_put_contents($filepath, $code);
			$tie->redirect($tie->getPhrase('addedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['edit']) && isset($_POST['title']) && isset($_POST['id']) && isset($_POST['content']) && isset($_POST['code']))
	{
		$title = $suit->db->escape($tie->magic($_POST['title']));
		$id = intval($_POST['id']);
		$code = trim($tie->magic($_POST['code']));
		$content = $suit->db->escape($tie->magic($_POST['content']));
		$oldtitle = '';
		$error = $tie->editSubmit('id = \'' . $id . '\'', 'title = \'' . $title . '\'', DB_PREFIX . 'templates', $title, &$oldtitle, 'title');
		if (!$error)
		{
			$query = 'UPDATE ' . DB_PREFIX . 'templates SET content = \'' . $content . '\', title = \'' . $title . '\' WHERE id = \'' . $id . '\'';	
			mysql_query($query);
			$code = $tie->breakConvert($code, PHP_OS);
			$filepath = $suit->templates->checkFile($title);
			file_put_contents($filepath, $code);
			if ($title != $oldtitle)
			{
				$filepath = $suit->templates->checkFile($oldtitle);
				unlink($filepath);
			}
			$tie->redirect($tie->getPhrase('editedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['delete']) && isset($_POST['id']))
	{
		$id = intval($_POST['id']);
		$results = $tie->deleteSubmit($id, DB_PREFIX . 'templates');
		while ($row = mysql_fetch_assoc($results))
		{
			$filepath = $suit->templates->checkFile($row['title']);
			unlink($filepath);
		}
		$tie->redirect($tie->getPhrase('deletedsuccessfully'), 2, $redirect);
	}
	if (isset($_POST['limit']) && isset($_POST['limitval']))
	{
		$limitval = intval($_POST['limitval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_templates&start=' . $start . '&limit=' . $limitval . '&orderby=' . $orderby_type . '&select=' . $selectdisplay . '&search=' . $search);
	}
	if (isset($_POST['search']) && isset($_POST['searchval']) && (strlen($_POST['searchval']) >= 4))
	{
		$searchval = $suit->db->escape($_POST['searchval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_templates&start=' . $start . '&limit=' . $limit . '&orderby=' . $orderby_type . '&select=' . $selectdisplay . '&search=' . $searchval);
	}
	$output = $tie->parseTemplates($output);
	//It's always safer to set a variable before use.
	$list = '';
	$pages = array('add', 'edit', 'delete');
	if (!(isset($_GET['cmd']) && in_array($_GET['cmd'], $pages)))
	{
		$range = $tie->setRange($start, $limit);
		$options = array
		(
			'orderby' => 'title',
			'orderby_type' => $orderby_type,
			'limit' => $range
		);
		if ($search)
		{
			$options['where'] = 'MATCH (title, content) AGAINST (\'' . $search . '\')';
		}
		$list = $tie->createList(DB_PREFIX . 'templates', $options, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_templates', 'id', 0, 0, 0, $start, $limit, $select, $search);
		if (!$list)
		{
			$suit->templates->getTemplate('badrequest');
		}
	}
	else
	{
		if ($_GET['cmd'] == 'add')
		{
			$list = $suit->templates->getTemplate('admin_templates_form');
			if (isset($error) && ($error))
			{
				$message = $tie->errorForm($error);
			}
			else
			{
				$message = '';
				$title = '';
				$content = '';
				$code = '';
				//Template Cloning
				if (isset($_GET['id']) && ($_GET['id']))
				{
					$id = intval($_GET['id']);
					$options = array
					(
						'where' => 'id = \'' . $id . '\''
					);
					$check = $suit->db->select(DB_PREFIX . 'templates', '*', $options); 
					if ($check)
					{
						while ($row = mysql_fetch_assoc($check))
						{
							$title = $row['title'];
							$content = $row['content'];
							$filepath = $suit->templates->checkFile($row['title']);
							$code = file_get_contents($filepath);
						}
					}
				}
			}
			$array = Array
			(
				array('<message>', $message),
				array('<content>', htmlentities($content)),
				array('<code>', htmlentities($code)),
				array('<title>', htmlentities($title)),
				array('<name>', 'add'),
				array('<value>', $tie->getPhrase('add'))
			);
			$list = $tie->replace($list, $array);
		}
		if (isset($_GET['cmd']) && ($_GET['cmd'] == 'edit'))
		{
			if (isset($_GET['id']))
			{
				$id = intval($_GET['id']);
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
			}
			$check = $tie->editForm('id = \'' . $id . '\'', DB_PREFIX . 'templates');
			$list = $suit->templates->getTemplate('admin_templates_form');
			while ($row = mysql_fetch_assoc($check))
			{
				if (isset($error) && ($error))
				{
					$message = $tie->errorForm($error);
				}
				else
				{
					$message = '';
					$content = $row['content'];
					$filepath = $suit->templates->checkFile($row['title']);
					$code = file_get_contents($filepath);
				}
				$array = array
				(
					array('<message>', $message),
					array('<id>', $row['id']),
					array('<title>', htmlentities($row['title'])),
					array('<content>', htmlentities($content)),
					array('<code>', htmlentities($code)),
					array('<name>', 'edit'),
					array('<value>', $tie->getPhrase('edit'))
				);
				$list = $tie->replace($list, $array);
			}
		}
		if (isset($_GET['cmd']) && ($_GET['cmd'] == 'delete'))
		{
			if (isset($_GET['id']))
			{
				$id = intval($_GET['id']);
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
				exit;
			}
			$list = $tie->deleteForm($id, DB_PREFIX . 'templates', 'title', 'delete');
		}
	}
	$output = str_replace('<admin_templates>', $list, $output);
}
else
{
	$output = $tie->parseTemplates($output);
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{	
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>