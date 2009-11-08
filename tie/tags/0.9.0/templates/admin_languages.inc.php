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
	if (isset($_GET['language']))
	{
		$language = intval($_GET['language']);
	}
	else
	{
		$language = 0;
	}
	if (!empty($_POST))
	{
		$redirect = $suit->templates->getTemplate('path_url') . '/index.php?page=admin_languages&start=' . $start . '&limit=' . $limit . '&orderby=' . $orderby_type . '&select=' . $selectdisplay . '&search=' . $search . '&language=' . $language;
	}
	if (isset($_POST['add']) && isset($_POST['id']) && isset($_POST['title']))
	{
		$title = $suit->db->escape($tie->magic($_POST['title']));
		$error = $tie->addSubmit('title = \'' . $title . '\'', DB_PREFIX . 'languages', $title);
		if (!$error)
		{
			$query = 'INSERT INTO ' . DB_PREFIX . 'languages VALUES (\'\', \'' . $title . '\', \'\')';
			mysql_query($query);
			if ($_POST['id'] != '')
			{
				$id = intval($_POST['id']);
				$templatecheck_options = array
				(
					'where' => 'id = \'' . $id . '\''
				);
				$templatecheck = $suit->db->select(DB_PREFIX . 'languages', '*', $templatecheck_options);
				if ($templatecheck)
				{
					$options = array
					(
						'where' => 'language = \'' . $id . '\'',
						'orderby' => 'title',
						'orderby_type' => 'asc'
					);
					$check = $suit->db->select(DB_PREFIX . 'phrases', '*', $options);
					if ($check)
					{
						$values = '';
						while ($row = mysql_fetch_assoc($check))
						{
							$values .= '(\'\', \'' . addslashes($row['title']) . '\', \'' . addslashes($row['content']) . '\', LAST_INSERT_ID()),';
						}
						$values = substr_replace($values, '', strlen($values)-1, 1);
						$query = 'INSERT INTO ' . DB_PREFIX . 'phrases VALUES ' . $values;
						mysql_query($query);
					}
				}
			}
			$tie->redirect($tie->getPhrase('addedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['addphrase']) && isset($_POST['id']) && isset($_POST['language']) && isset($_POST['title']) && isset($_POST['content']))
	{
		$title = $suit->db->escape($tie->magic($_POST['title']));
		$language = $suit->db->escape($tie->magic($_POST['language']));
		$error = $tie->addSubmit('title = \'' . $title . '\' AND language = \'' . $language . '\'', DB_PREFIX . 'phrases', $title);
		if (!$error)
		{
			$content = $suit->db->escape($tie->magic($_POST['content']));
			$query = 'INSERT INTO ' . DB_PREFIX . 'phrases VALUES (\'\', \'' . $title . '\', \'' . $content . '\', \'' . $language . '\')';
			mysql_query($query);
			$tie->redirect($tie->getPhrase('addedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['edit']) && isset($_POST['id']) && isset($_POST['title']))
	{
		$title = $suit->db->escape($tie->magic($_POST['title']));
		$id = intval($_POST['id']);
		$oldtitle = '';
		$error = $tie->editSubmit('id = \'' . $id . '\'', 'title = \'' . $title . '\'', DB_PREFIX . 'languages', $title, &$oldtitle, 'title');
		if (!$error)
		{
			$query = 'UPDATE ' . DB_PREFIX . 'languages SET title = \'' . $title . '\' WHERE id = \'' . $id . '\'';	
			mysql_query($query);
			$tie->redirect($tie->getPhrase('editedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['editphrase']) && isset($_POST['id']) && isset($_POST['language']) && isset($_POST['title']) && isset($_POST['content']))
	{
		$title = $suit->db->escape($tie->magic($_POST['title']));
		$id = intval($_POST['id']);
		$language = intval($_POST['language']);
		$content = $suit->db->escape($tie->magic($_POST['content']));
		$error = $tie->editSubmit('id = \'' . $id . '\' AND language = \'' . $language . '\'', 'title = \'' . $title . '\' AND language = \'' . $language . '\'', DB_PREFIX . 'phrases', $title, &$oldtitle, 'title');
		if (!$error)
		{
			$query = 'UPDATE ' . DB_PREFIX . 'phrases SET title = \'' . $title . '\', content = \'' . $content . '\' WHERE id = \'' . $id . '\'';	
			mysql_query($query);
			$tie->redirect($tie->getPhrase('editedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['delete']) && isset($_POST['id']))
	{
		$id = intval($_POST['id']);
		$tie->deleteSubmit($id, DB_PREFIX . 'languages');
		$query = 'DELETE FROM ' . DB_PREFIX . 'phrases WHERE language = \'' . $id . '\'';
		mysql_query($query);
		$tie->redirect($tie->getPhrase('deletedsuccessfully'), 2, $redirect);
	}
	if (isset($_POST['deletephrase']) && isset($_POST['id']))
	{
		$id = intval($_POST['id']);
		$tie->deleteSubmit($id, DB_PREFIX . 'phrases');
		$tie->redirect($tie->getPhrase('deletedsuccessfully'), 2, $redirect);
	}
	if (isset($_GET['cmd']) && ($_GET['cmd'] == 'default') && isset($_GET['language']))
	{
		$id = intval($_GET['language']);
		$tie->defaultSubmit($id, DB_PREFIX . 'languages');
		$tie->redirect($tie->getPhrase('defaultedsuccessfully'), 2, $redirect);
	}
	if (isset($_POST['limit']) && isset($_POST['limitval']))
	{
		$limitval = intval($_POST['limitval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_languages&start=' . $start . '&limit=' . $limitval . '&orderby=' . $orderby_type . '&select=' . $selectdisplay . '&search=' . $search . '&language=' . $language);
	}
	if (isset($_POST['search']) && isset($_POST['searchval']) && (strlen($_POST['searchval']) >= 4))
	{
		$searchval = $suit->db->escape($_POST['searchval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_languages&start=' . $start . '&limit=' . $limit . '&orderby=' . $orderby_type . '&select=' . $selectdisplay . '&search=' . $searchval . '&language=' . $language);
	}
	$output = $tie->parseTemplates($output);
	//It's always safer to set a variable before use.
	$list = '';
	$pages = array('add', 'edit', 'delete');
	if (!(isset($_GET['cmd']) && in_array($_GET['cmd'], $pages)))
	{
		$range = $tie->setRange($start, $limit);
		if (!(isset($_GET['language']) && ($_GET['language'])))
		{
			$options = array
			(
				'orderby' => 'title',
				'orderby_type' => $orderby_type,
				'limit' => $range
			);
			if ($search)
			{
				$options['where'] = 'MATCH (title) AGAINST (\'' . $search . '\')';
			}
			$list = $tie->createList(DB_PREFIX . 'languages', $options, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_languages', 'language', 1, 1, 0, $start, $limit, $select, $search);
		}
		else
		{
			$options = array
			(
				'where' => 'id = \'' . intval($_GET['language']) . '\''
			);
			$check = $suit->db->select(DB_PREFIX . 'languages', '*', $options);
			if ($check)
			{
				$options = array
				(
					'orderby' => 'title',
					'orderby_type' => $orderby_type,
					'where' => 'language = \'' . intval($_GET['language']) . '\'',
					'limit' => $range
				);
				if ($search)
				{
					$options['where'] = 'MATCH (title, content) AGAINST (\'' . $search . '\')';
				}
				$list = $tie->createList(DB_PREFIX . 'phrases', $options, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_languages&amp;language=' . $_GET['language'], 'id', 0, 0, 0, $start, $limit, $select, $search);
			}
			else
			{
				$this->suit->templates->getTemplate('badrequest');
			}
		}
		if (!$list)
		{
			$list = '';
		}
	}
	else
	{
		if ($_GET['cmd'] == 'add')
		{
			if (!isset($_GET['id']))
			{
				if (isset($error) && ($error))
				{
					$message = $tie->errorForm($error);
				}
				else
				{
					$message = '';
				}
				$list = $suit->templates->getTemplate('admin_languages_form');
				$title = '';
				$id = '';
				//Template Cloning
				if (isset($_GET['language']) && ($_GET['language']))
				{
					$id = intval($_GET['language']);
					$locate_options = array
					(
						'where' => 'id = \'' . $id . '\''
					);
					$locate = $suit->db->select(DB_PREFIX . 'languages', '*', $locate_options); 
					if ($locate)
					{
						while ($row = mysql_fetch_assoc($locate))
						{
							$title = $row['title'];
							$id = $row['id'];
						}
					}
				}
				$array = Array
				(
					array('<message>', $message),
					array('<id>', htmlentities($id)),
					array('<title>', htmlentities($title)),
					array('<name>', 'add'),
					array('<value>', $tie->getPhrase('add'))
				);
				$list = $tie->replace($list, $array);
			}
			else
			{
				if (isset($_GET['language']))
				{
					$language = intval($_GET['language']);
				}
				else
				{
					$suit->templates->getTemplate('badrequest');
				}
				if (isset($error) && ($error))
				{
					$message = $tie->errorForm($error);
				}
				else
				{
					$message = '';
				}
				$list = $suit->templates->getTemplate('admin_languages_phrases_form');
				$title = '';
				$id = '';
				$content = '';
				//Template Cloning
				if (isset($_GET['id']))
				{
					$id = intval($_GET['id']);
					$options = array
					(
						'where' => 'id = \'' . $id . '\' AND language = \'' . $language . '\''
					);
					$check = $suit->db->select(DB_PREFIX . 'phrases', '*', $options); 
					if ($check)
					{
						while ($row = mysql_fetch_assoc($check))
						{
							$title = $row['title'];
							$id = $row['id'];
							if (!(isset($error) && ($error)))
							{
								$content = $row['content'];
							}
						}
					}
				}
				$array = Array
				(
					array('<message>', $message),
					array('<id>', htmlentities($id)),
					array('<language>', htmlentities($language)),
					array('<title>', htmlentities($title)),
					array('<content>', htmlentities($content)),
					array('<name>', 'addphrase'),
					array('<value>', $tie->getPhrase('add'))
				);
				$list = $tie->replace($list, $array);
			}
		}
		if (isset($_GET['cmd']) && ($_GET['cmd'] == 'edit'))
		{
			if (isset($_GET['language']))
			{
				$language = intval($_GET['language']);
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
			}
			if (!isset($_GET['id']))
			{
				$check = $tie->editForm('id = \'' . $language . '\'', DB_PREFIX . 'languages');
				if (isset($error) && ($error))
				{
					$message = $tie->errorForm($error);
				}
				else
				{
					$message = '';
				}
				$list = $suit->templates->getTemplate('admin_languages_form');
				while ($row = mysql_fetch_assoc($check))
				{
					$array = array
					(
						array('<message>', $message),
						array('<id>', htmlentities($row['id'])),
						array('<title>', htmlentities($row['title'])),
						array('<name>', 'edit'),
						array('<value>', $tie->getPhrase('edit'))
					);
					$list = $tie->replace($list, $array);
				}
			}
			else
			{
				$id = intval($_GET['id']);
				$check = $tie->editForm('id = \'' . $id . '\' AND language = \'' . $language . '\'', DB_PREFIX . 'phrases');
				$list = $suit->templates->getTemplate('admin_languages_phrases_form');
				if (isset($error) && ($error))
				{
					$message = $tie->errorForm($error);
				}
				else
				{
					$message = '';
				}
				while ($row = mysql_fetch_assoc($check))
				{
					if (!(isset($error) && ($error)))
					{
						$content = $row['content'];
					}
					$array = array
					(
						array('<message>', $message),
						array('<id>', htmlentities($row['id'])),
						array('<language>', htmlentities($language)),
						array('<title>', htmlentities($row['title'])),
						array('<content>', htmlentities($content)),
						array('<name>', 'editphrase'),
						array('<value>', $tie->getPhrase('edit'))
					);
					$list = $tie->replace($list, $array);
				}
			}
		}
		if (isset($_GET['cmd']) && ($_GET['cmd'] == 'delete'))
		{
			if (isset($_GET['language']))
			{
				$language = intval($_GET['language']);
			}
			else
			{
				$suit->templates->getTemplate('badrequest');
				exit;
			}
			if (!isset($_GET['id']))
			{
				$list = $tie->deleteForm($language, DB_PREFIX . 'languages', 'title', 'delete');
			}
			else
			{
				$id = intval($_GET['id']);
				$list = $tie->deleteForm($id, DB_PREFIX . 'phrases', 'title', 'deletephrase');
			}
		}
	}
	$output = str_replace('<admin_languages>', $list, $output);
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