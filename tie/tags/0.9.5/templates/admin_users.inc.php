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
		$redirect = $suit->templates->getTemplate('path_url') . '/index.php?page=admin_users&start=' . $start . '&limit=' . $limit . '&orderby=' . $orderby_type . '&search=' . $search;
	}
	if (isset($_POST['add']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['language']) && isset($_POST['admin']))
	{
		$username = $suit->db->escape($_POST['username']);
		$query = 'SELECT * FROM ' . DB_PREFIX . 'users WHERE username = \'' . $username . '\'';
		$error = $tie->addSubmit($query, $username);
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
			$password = md5($_POST['password'] . DB_SALT);
			$email = $suit->db->escape($_POST['email']);
			$language = $suit->db->escape($_POST['language']);
			$admin = $suit->db->escape($_POST['admin']);
			$query = 'INSERT INTO ' . DB_PREFIX . 'users (username, password, email, language, admin) VALUES (\'' . $username . '\', \'' . $password . '\', \'' . $email . '\', \'' . $language . '\', \'' . $admin . '\');';
			$suit->db->query($query);
			$tie->redirect($tie->getPhrase('addedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['edit']) && isset($_POST['username']) && isset($_POST['id']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['language']) && isset($_POST['admin']))
	{
		$username = $suit->db->escape($_POST['username']);
		$id = intval($_POST['id']);
		$oldtitle = '';
		$query = 'SELECT * FROM ' . DB_PREFIX . 'users WHERE id = \'' . $id . '\'';
		$query2 = 'SELECT * FROM ' . DB_PREFIX . 'users WHERE username = \'' . $username . '\'';
		$error = $tie->editSubmit($query, $query2, DB_PREFIX . 'users', $username, &$oldtitle, 'username');
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
			$email = $suit->db->escape($_POST['email']);
			$language = $suit->db->escape($_POST['language']);
			$admin = $suit->db->escape($_POST['admin']);
			if ($_POST['password'])
			{
				$passwordset = ' password = \'' . md5($_POST['password'] . DB_SALT) . '\',';
			}
			else
			{
				$passwordset = '';
			}
			$query = 'UPDATE ' . DB_PREFIX . 'users SET' . $passwordset . ' username = \'' . $username . '\', email = \'' . $email . '\', language = \'' . $language . '\', admin = \'' . $admin . '\' WHERE id = \'' . $id . '\'';
			$suit->db->query($query);
			$tie->redirect($tie->getPhrase('editedsuccessfully'), 2, $redirect);
		}
	}
	if (isset($_POST['delete']) && isset($_POST['id']))
	{
		$id = array_map('intval', $_POST['id']); //Because we cannot trust if these values are actually numeric by default.
		$tie->deleteSubmit($id, DB_PREFIX . 'users');
		$tie->redirect($tie->getPhrase('deletedsuccessfully'), 2, $redirect);
	}
	if (isset($_POST['limit']) && isset($_POST['limitval']))
	{
		$limitval = intval($_POST['limitval']);
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_users&start=0&limit=' . $limitval . '&orderby=' . $orderby_type . '&search=' . $search);
	}
	if (isset($_POST['search']) && isset($_POST['searchval']) && (strlen($_POST['searchval']) >= 4))
	{
		$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/index.php?page=admin_users&start=0&limit=' . $limit . '&orderby=' . $orderby_type . '&search=' . $_POST['searchval']);
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
		$query = 'SELECT * FROM ' . DB_PREFIX . 'users WHERE 1';
		$list = $tie->createList($query, 'username', $orderby_type, $range, $search, 'username', $suit->templates->getTemplate('path_url') . '/index.php?page=admin_users', 'id', 0, 0, 0, $start, $limit, $select, $search, 0);
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
				$query = 'SELECT * FROM ' . DB_PREFIX . 'users WHERE id = \'' . $id . '\'';
				$check = $suit->db->query($query);
				if ($check && (mysql_num_rows($check)))
				{
					while ($row = $suit->db->fetch($check))
					{
						$username = $row['username'];
						$email = $row['email'];
						$languages = $tie->languageForm($row['language'], 1);
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
				$languages = $tie->languageForm('', 1);
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
			$query = 'SELECT * FROM ' . DB_PREFIX . 'users WHERE id = \'' . $id . '\'';
			$check = $suit->db->query($query);
			if (!(isset($message) && ($message)))
			{
				$message = '';
			}
			$list = $suit->templates->getTemplate('admin_users_form');	
			while ($row = $suit->db->fetch($check))
			{
				$languages = $tie->languageForm($row['language'], 1);
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
			if (!isset($_GET['id']))
			{
				$suit->templates->getTemplate('badrequest');
				exit;
			}
			$list = $tie->deleteForm($_GET['id'], DB_PREFIX . 'users', 'username', 'delete');
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