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
		$redirect = $suit->templates->getTemplate('path_url') . '/index.php?page=admin_users&start=' . $start . '&limit=' . $limit . '&orderby=' . $orderby_type . '&select=' . $selectdisplay . '&search=' . $search;
	}
	if (isset($_POST['add']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['language']) && isset($_POST['admin']))
	{
		$username = $suit->db->escape($tie->magic($_POST['username']));
		$error = $tie->addSubmit('username = \'' . $username . '\'', DB_PREFIX . 'users', $username);
		if (isset($error) && ($error == 'duplicatetitle'))
		{
			$message = $tie->errorForm('duplicateusername');
		}
		else
		{
			$message = '';
		}
		//The username must be at least 7 characters, and it must not exceed 50 characters.
		if (!((strlen($_POST['username']) >= 7) && (strlen($_POST['username']) <= 50)))
		{
			$message .= $tie->getPhrase('usernamenotvalid');
		}
		//The password must be at least 7 characters long, and it must not exceed 32 characters.
		if (!((strlen($_POST['password']) > 7) && (strlen($_POST['password']) < 32)))
		{
			$message .= $tie->getPhrase('passwordnotvalid');
		}
		if (!$message)
		{
			$password = md5($tie->magic($_POST['password']) . DB_SALT);
			$email = $suit->db->escape($tie->magic($_POST['email']));
			$language = $suit->db->escape($tie->magic($_POST['language']));
			$admin = $suit->db->escape($tie->magic($_POST['admin']));
			$query = 'INSERT INTO ' . DB_PREFIX . 'users VALUES (\'\', \'' . $admin . '\', \'' . $username . '\', \'' . $password . '\', \'' . $email . '\', \'' . $language . '\', \'\', \'\')';
			mysql_query($query);
			$tie->redirect($tie->getPhrase('addedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['edit']) && isset($_POST['username']) && isset($_POST['id']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['language']) && isset($_POST['admin']))
	{
		$username = $suit->db->escape($tie->magic($_POST['username']));
		$id = intval($_POST['id']);
		$oldtitle = '';
		$error = $tie->editSubmit('id = \'' . $id . '\'', 'username = \'' . $username . '\'', DB_PREFIX . 'users', $username, &$oldtitle, 'username');
		if (isset($error) && ($error == 'duplicatetitle'))
		{
			$message = $tie->errorForm('duplicateusername');
		}
		else
		{
			$message = '';
		}
		//The username must be at least 7 characters, and it must not exceed 50 characters.
		if (!((strlen($_POST['username']) >= 7) && (strlen($_POST['username']) <= 50)))
		{
			$message .= $tie->getPhrase('usernamenotvalid');
		}
		//The password must be at least 7 characters long, and it must not exceed 32 characters.
		if (!((strlen($_POST['password']) > 7) && (strlen($_POST['password']) < 32)) && $_POST['password'])
		{
			$message .= $tie->getPhrase('passwordnotvalid');
		}
		if (!$message)
		{
			if ($_POST['password'])
			{
				$password = 'password = \'' . md5($tie->magic($_POST['password']) . DB_SALT) . '\', ';
			}
			else
			{
				$password = '';
			}
			$email = $suit->db->escape($tie->magic($_POST['email']));
			$language = $suit->db->escape($tie->magic($_POST['language']));
			$admin = $suit->db->escape($tie->magic($_POST['admin']));
			$query = 'UPDATE ' . DB_PREFIX . 'users SET ' . $password . 'username = \'' . $username . '\', email = \'' . $email . '\', language = \'' . $language . '\', admin = \'' . $admin . '\' WHERE id = \'' . $id . '\'';	
			mysql_query($query);
			$tie->redirect($tie->getPhrase('editedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['delete']) && isset($_POST['id']))
	{
		$id = intval($_POST['id']);
		$tie->deleteSubmit($id, DB_PREFIX . 'users');
		$tie->redirect($tie->getPhrase('deletedsuccessfully'), 2, $redirect);
	}
	if (isset($_POST['limit']) && isset($_POST['limitval']))
	{
		$limitval = intval($_POST['limitval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_users&start=' . $start . '&limit=' . $limitval . '&orderby=' . $orderby_type . '&select=' . $selectdisplay . '&search=' . $search);
	}
	if (isset($_POST['search']) && isset($_POST['searchval']) && (strlen($_POST['searchval']) >= 4))
	{
		$searchval = $suit->db->escape($_POST['searchval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_users&start=' . $start . '&limit=' . $limit . '&orderby=' . $orderby_type . '&select=' . $selectdisplay . '&search=' . $searchval);
	}
	$output = $tie->parseTemplates($output);
	//It's always safer to set a variable before use.
	$list = '';
	$pages = array('add', 'edit', 'delete');
	if (!(isset($_GET['cmd']) && in_array($_GET['cmd'], $pages)))
	{
		$range = $tie->setRange($start, $limit);
		$orderby_type = 'asc';
		if (isset($_GET['orderby']) && ($_GET['orderby'] == 'desc'))
		{
			$orderby_type = 'desc';
		}
		$options = array
		(
			'orderby' => 'username',
			'orderby_type' => $orderby_type,
			'limit' => $range
		);
		if ($search)
		{
			$options['where'] = 'MATCH (username) AGAINST (\'' . $search . '\')';
		}
		$list = $tie->createList(DB_PREFIX . 'users', $options, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_users', 'id', 0, 0, 0, $start, $limit, $select, $search);
		if (!$list)
		{
			$suit->templates->getTemplate('badrequest');
		}
	}
	else
	{
		if ($_GET['cmd'] == 'add')
		{
			$list = $suit->templates->getTemplate('admin_users_form');
			if (!(isset($message) && ($message)))
			{
				$message = '';
			}
			$username = '';
			$email = '';
			$yes = '';
			$no = '';
			//Template Cloning
			if (isset($_GET['id']) && ($_GET['id']))
			{
				$id = intval($_GET['id']);
				$options = array
				(
					'where' => 'id = \'' . $id . '\''
				);
				$check = $suit->db->select(DB_PREFIX . 'users', '*', $options); 
				if ($check)
				{
					while ($row = mysql_fetch_assoc($check))
					{
						$username = $row['username'];
						$email = $row['email'];
						$languages = $tie->languageForm($row['language']);
						if ($row['admin'])
						{
							$yes = ' selected';
							$no = '';
						}
						else
						{
							$yes = '';
							$no = ' selected';
						}
					}
				}
			}
			else
			{
				$languages = $tie->languageForm(0);
			}
			$array = Array
			(
				array('<message>', $message),
				array('<username>', htmlentities($username)),
				array('<email>', htmlentities($email)),
				array('<languages>', $languages),
				array('<yes>', $yes),
				array('<no>', $no),
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
			$check = $tie->editForm('id = \'' . $id . '\'', DB_PREFIX . 'users');
			if (!(isset($message) && ($message)))
			{
				$message = '';
			}
			$list = $suit->templates->getTemplate('admin_users_form');	
			while ($row = mysql_fetch_assoc($check))
			{
				$languages = $tie->languageForm($row['language']);
				if ($row['admin'])
				{
					$yes = ' selected';
					$no = '';
				}
				else
				{
					$yes = '';
					$no = ' selected';
				}
				$array = array
				(
					array('<message>', $message),
					array('<id>', $row['id']),
					array('<username>', htmlentities($row['username'])),
					array('<email>', htmlentities($row['email'])),
					array('<languages>', $languages),
					array('<yes>', $yes),
					array('<no>', $no),
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
			$list = $tie->deleteForm($id, DB_PREFIX . 'users', 'username', 'delete');
		}
	}
	$output = str_replace('<admin_users>', $list, $output);
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