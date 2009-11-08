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
	}
	else
	{
		$select = false;
	}
	if (!empty($_POST) || (isset($_GET['cmd']) && ($_GET['cmd'] == 'default')))
	{
		$redirect = $suit->templates->getTemplate('path_url') . '/index.php?page=admin_pages&start=' . $start . '&limit=' . $limit . '&orderby=' . $orderby_type . '&search=' . $search;
	}
	if (isset($_POST['add']) && isset($_POST['title']) && isset($_POST['template']))
	{
		$title = $suit->db->escape($_POST['title']);
		$query = 'SELECT * FROM ' . DB_PREFIX . 'pages WHERE title = \'' . $title . '\'';
		$error = $tie->addSubmit($query, $title);
		if (!$error)
		{
			$template = $suit->db->escape($_POST['template']);
			$query = 'INSERT INTO ' . DB_PREFIX . 'pages (title, template) VALUES (\'' . $title . '\', \'' . $template . '\');';
			$suit->db->query($query);
			$tie->redirect($tie->getPhrase('addedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['edit']) && isset($_POST['title']) && isset($_POST['id']) && isset($_POST['template']))
	{
		$title = $suit->db->escape($_POST['title']);
		$id = intval($_POST['id']);
		$oldtitle = '';
		$query = 'SELECT * FROM ' . DB_PREFIX . 'pages WHERE id = \'' . $id . '\'';
		$query2 = 'SELECT * FROM ' . DB_PREFIX . 'pages WHERE title = \'' . $title . '\'';
		$error = $tie->editSubmit($query, $query2, DB_PREFIX . 'pages', $title, &$oldtitle, 'title');
		if (!$error)
		{
			$template = $suit->db->escape($_POST['template']);
			$query = 'UPDATE ' . DB_PREFIX . 'pages SET title = \'' . $title . '\', template = \'' . $template . '\' WHERE id = \'' . $id . '\'';
			$suit->db->query($query);
			$tie->redirect($tie->getPhrase('editedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['delete']) && isset($_POST['id']))
	{
		$id = array_map('intval', $_POST['id']); //Because we cannot trust if these values are actually numeric by default.
		$tie->deleteSubmit($id, DB_PREFIX . 'pages');
		$tie->redirect($tie->getPhrase('deletedsuccessfully'), 2, $redirect);
	}
	if (isset($_GET['cmd']) && ($_GET['cmd'] == 'default') && isset($_GET['id']))
	{
		$id = intval($_GET['id']);
		$tie->defaultSubmit($id, DB_PREFIX . 'pages');
		$tie->redirect($tie->getPhrase('defaultedsuccessfully'), 2, $redirect);
	}
	if (isset($_POST['limit']) && isset($_POST['limitval']))
	{
		$limitval = intval($_POST['limitval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_pages&start=0&limit=' . $limitval . '&orderby=' . $orderby_type . '&search=' . $search);
	}
	if (isset($_POST['search']) && isset($_POST['searchval']) && (strlen($_POST['searchval']) >= 4))
	{
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_pages&start=0&limit=' . $limit . '&orderby=' . $orderby_type . '&search=' . $_POST['searchval']);
	}
	if (isset($_POST['deleteselected']) && isset($_POST['entry']) && is_array($_POST['entry']))
	{
		$get = array_map('intval', $_POST['entry']); //Because we cannot trust if these values are actually numeric by default.
		$get = implode('&id[]=', $_POST['entry']); //Implode the array into comma separated values, for explosion later in the $_GET
		$tie->redirect('', 0, $redirect . '&cmd=delete&id[]=' . $get);
	}
	$output = $tie->parseTemplates($output);
	//It's always safer to set a variable before use.
	$list = '';
	$pages = array('add', 'edit', 'delete');
	if (!(isset($_GET['cmd']) && in_array($_GET['cmd'], $pages)))
	{
		$range = $tie->setRange($start, $limit);
		$query = 'SELECT * FROM ' . DB_PREFIX . 'pages WHERE 1';
		$list = $tie->createList($query, 'title', $orderby_type, $range, $search, 'title, template', $suit->templates->getTemplate('path_url') . '/index.php?page=admin_pages', 'id', 1, 0, 0, $start, $limit, $select, $search, 0);
		if (!$list)
		{
			$suit->templates->getTemplate('badrequest');
		}
	}
	else
	{
		if ($_GET['cmd'] == 'add')
		{
			if (isset($error) && ($error))
			{
				$message = $tie->errorForm($error);
			}
			else
			{
				$message = '';
			}
			$list = $suit->templates->getTemplate('admin_pages_form');
			$title = '';
			$template = '';
			//Page Cloning
			if (isset($_GET['id']) && ($_GET['id']))
			{
				$id = intval($_GET['id']);
				$query = 'SELECT * FROM ' . DB_PREFIX . 'pages WHERE id = \'' . $id . '\'';
				$check = $suit->db->query($query);
				if ($check && (mysql_num_rows($check)))
				{
					while ($row = $suit->db->fetch($check))
					{
						$title = $row['title'];
						$template = $row['template'];
						$filepath = $suit->templates->checkFile($row['title']);
					}
				}
			}
			$array = Array
			(
				array('<message>', $message),
				array('<template>', htmlentities($template)),
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
			$query = 'SELECT * FROM ' . DB_PREFIX . 'pages WHERE id = \'' . $id . '\'';
			$check = $suit->db->query($query);
			if (!($check && (mysql_num_rows($check))))
			{
				$this->suit->templates->getTemplate('badrequest');
			}
			if (isset($error) && ($error))
			{
				$message = $tie->errorForm($error);
			}
			else
			{
				$message = '';
			}
			$list = $suit->templates->getTemplate('admin_pages_form');
			while ($row = $suit->db->fetch($check))
			{			
				$array = array
				(
					array('<message>', $message),
					array('<id>', $row['id']),
					array('<title>', htmlentities($row['title'])),
					array('<template>', htmlentities($row['template'])),
					array('<name>', 'edit'),
					array('<value>', $tie->getPhrase('edit'))
				);
				$list = $tie->replace($list, $array);
			}
		}
		if (isset($_GET['cmd']) && ($_GET['cmd'] == 'delete'))
		{
			if (!isset($_GET['id']))
			{
				$suit->templates->getTemplate('badrequest');
				exit;
			}
			$list = $tie->deleteForm($_GET['id'], DB_PREFIX . 'pages', 'title', 'delete');
		}
	}
	$output = str_replace('<admin_pages>', $list, $output);
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