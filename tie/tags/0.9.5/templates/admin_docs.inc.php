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
	if (!empty($_POST))
	{
		$redirect = $suit->templates->getTemplate('path_url') . '/index.php?page=admin_docs&start=' . $start . '&limit=' . $limit . '&orderby=' . $orderby_type . '&search=' . $search;
	}
	if (isset($_POST['add']) && isset($_POST['title']) && isset($_POST['template']) && isset($_POST['category']))
	{
		$title = $suit->db->escape($_POST['title']);
		$query = 'SELECT * FROM docs WHERE title = \'' . $title . '\'';
		$error = $tie->addSubmit($query, $title);
		if (!$error)
		{
			$template = $suit->db->escape($_POST['template']);
			$category = $suit->db->escape($_POST['category']);
			$query = 'INSERT INTO docs (title, template, category) VALUES (\'' . $title . '\', \'' . $template . '\', \'' . $category . '\');';
			$suit->db->query($query);
			$tie->redirect($tie->getPhrase('addedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['edit']) && isset($_POST['title']) && isset($_POST['id']) && isset($_POST['template']) && isset($_POST['category']))
	{
		$title = $suit->db->escape($_POST['title']);
		$id = intval($_POST['id']);
		$oldtitle = '';
		$query = 'SELECT * FROM docs WHERE id = \'' . $id . '\'';
		$query2 = 'SELECT * FROM docs WHERE title = \'' . $title . '\'';
		$error = $tie->editSubmit($query, $query2, 'docs', $title, &$oldtitle, 'title');
		if (!$error)
		{
			$template = $suit->db->escape($_POST['template']);
			$category = $suit->db->escape($_POST['category']);
			$query = 'UPDATE docs SET title = \'' . $title . '\', template = \'' . $template . '\', category = \'' . $category . '\' WHERE id = \'' . $id . '\'';
			$suit->db->query($query);
			$tie->redirect($tie->getPhrase('editedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['delete']) && isset($_POST['id']))
	{
		$id = array_map('intval', $_POST['id']); //Because we cannot trust if these values are actually numeric by default.
		$tie->deleteSubmit($id, 'docs');
		$tie->redirect($tie->getPhrase('deletedsuccessfully'), 2, $redirect);
	}
	if (isset($_POST['limit']) && isset($_POST['limitval']))
	{
		$limitval = intval($_POST['limitval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_docs&start=0&limit=' . $limitval . '&orderby=' . $orderby_type . '&search=' . $search);
	}
	if (isset($_POST['search']) && isset($_POST['searchval']) && (strlen($_POST['searchval']) >= 4))
	{
		$searchval = $suit->db->escape($_POST['searchval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_docs&start=0&limit=' . $limit . '&orderby=' . $orderby_type . '&search=' . $searchval);
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
		$query = 'SELECT * FROM docs WHERE 1';
		$list = $tie->createList($query, 'title', $orderby_type, $range, $search, 'title, template', $suit->templates->getTemplate('path_url') . '/index.php?page=admin_docs', 'id', 0, 0, 0, $start, $limit, $select, $search, 0);
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
			$list = $suit->templates->getTemplate('admin_docs_form');
			$title = '';
			$template = '';
			$category = '';
			//Page Cloning
			if (isset($_GET['id']) && ($_GET['id']))
			{
				$id = intval($_GET['id']);
				$query = 'SELECT * FROM docs WHERE id = \'' . $id . '\'';
				$check = $suit->db->query($query);
				if ($check && (mysql_num_rows($check)))
				{
					while ($row = $suit->db->fetch($check))
					{
						$title = $row['title'];
						$template = $row['template'];
						$category = $row['category'];						
					}
				}
			}
			$array = Array
			(
				array('<message>', $message),
				array('<template>', htmlentities($template)),
				array('<category>', htmlentities($category)),
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
			$query = 'SELECT * FROM docs WHERE id = \'' . $id . '\'';
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
			$list = $suit->templates->getTemplate('admin_docs_form');
			while ($row = $suit->db->fetch($check))
			{			
				$array = array
				(
					array('<message>', $message),
					array('<id>', $row['id']),
					array('<title>', htmlentities($row['title'])),
					array('<template>', htmlentities($row['template'])),
					array('<category>', htmlentities($row['category'])),
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
			$list = $tie->deleteForm($_GET['id'], 'docs', 'title', 'delete');
		}
	}
	$output = str_replace('<admin_docs>', $list, $output);
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