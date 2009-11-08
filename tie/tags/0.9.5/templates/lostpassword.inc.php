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
if ($tie->loggedIn() == 0)
{
	if (isset($_POST['lostpassword']))
	{
		if (isset($_POST['email']))
		{	
			$query = 'SELECT * FROM ' . DB_PREFIX . 'users WHERE email = \'' . $suit->db->escape($_POST['email'], 0) . '\'';
			$check = $suit->db->query($query);
			if ($check && (mysql_num_rows($check)))
			{
				while ($row = $suit->db->fetch($check))
				{
					$string = substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5);
					$password = substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5);
					$passwordDB_SALTed = md5($password . DB_SALT);
					$query = 'UPDATE ' . DB_PREFIX . 'users SET recover_string = \'' . $string . '\', recover_password = \'' . $passwordDB_SALTed . '\' WHERE id = \'' . $row['id'] . '\'';
					$suit->db->query($query);
					$body = $tie->getPhrase('lostpassword_body');
					$body = str_replace('<password>', $password, $body);
					$body = str_replace('<path_url>', PATH_URL, $body);
					$body = str_replace('<string>', $string, $body);
					$body = str_replace('<id>', $row['id'], $body);
					mail($row['email'], $tie->getPhrase('lostpassword_subject'), $body, $tie->getPhrase('emailheaders')) or die ($tie->getPhrase('maildeliveryfailed'));
				}
				$tie->redirect($tie->getPhrase('passwordsent'), 2, $suit->templates->getTemplate('path_url') . '/index.php?page=lostpassword');
			}
			else
			{
				$error = 'emailnotfound';
			}
		}
	}
	if (isset($_GET['id']) && isset($_GET['string']))
	{
		$query = 'SELECT * FROM ' . DB_PREFIX . 'users WHERE id = \'' . intval($_GET['id']) . '\' AND recover_string = \'' . $suit->db->escape($_GET['string'], 0) . '\'';
		$check = $suit->db->query($query);
		if ($check && (mysql_num_rows($check)))
		{
			while ($row = $suit->db->fetch($check))
			{
				$query = 'UPDATE ' . DB_PREFIX . 'users SET password = \'' . $row['recover_password'] . '\', recover_string = \'\', recover_password = \'\' WHERE id = \'' . $row['id'] . '\'';
				$suit->db->query($query);
			}
			$error = 'passwordchanged';
		}
		else
		{
			$error = 'passwordexpired';
		}	
	}
	if (isset($error))
	{
		//We'll use a switch() statement to determine what action to take for these errors.
		//When we have our error, we'll load the language string for it.
		switch ($error)
		{
			case 'emailnotfound':
				$lostpassword_message = $tie->getPhrase('emailnotfound'); break;
			case 'passwordchanged':
				$lostpassword_message = $tie->getPhrase('passwordchanged'); break;
			case 'passwordexpired':
				$lostpassword_message = $tie->getPhrase('passwordexpired'); break;
			default:
				$lostpassword_message = $tie->getPhrase('undefinederror'); break;
		}
		//Replace the value of $list with what we concluded in the error switch() statement.
	}
	else
	{
		$lostpassword_message = '';
	}
	$output = str_replace('<message>', $lostpassword_message, $output);
}
else
{
	$suit->templates->getTemplate('notauthorized');
}
$output = $tie->parseTemplates($output);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>