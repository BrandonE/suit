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
if ($tie->loggedIn() != 0)
{
	if (isset($_POST['changeinfo']))
	{
		//Validate the email
		if (isset($_POST['email']) && $tie->validateEmail($_POST['email']))
		{
			$email = $_POST['email'];
			//Check if the username is taken.
			$query = 'SELECT email FROM ' . DB_PREFIX  . 'users WHERE email = \'' . $email . '\'';
			$check = $suit->db->query($query);
			if (($check && (mysql_num_rows($check))) && $email != $tie->user['email'])
			{
				$message .= $tie->getPhrase('emailexists');
			}
		}
		else
		{
			//Email Error
			$message .= $tie->getPhrase('emailnotvalid');
		}
		//The username must be at least 7 characters, and it must not exceed 50 characters.
		if ((strlen($_POST['username']) >= 7) && (strlen($_POST['username']) <= 50))
		{
			$username = $suit->db->escape($_POST['username']);
			//Check if the username is taken.
			$query = 'SELECT * FROM ' . DB_PREFIX . 'users WHERE username = \'' . $username . '\'';
			$check = $this->suit->db->query($query);
			if (($check && (mysql_num_rows($check))) && $username != $tie->user['username'])
			{
				$message .= $tie->getPhrase('usernametaken');
			}
		}
		else
		{
			//Username error
			$message .= $tie->getPhrase('usernamenotvalid');
		}
		//The password must be at least 7 characters long, and it must not exceed 32 characters.
		if ((strlen($_POST['password']) > 7) && (strlen($_POST['password']) < 32))
		{
			$password = md5($_POST['password'] . DB_SALT);
		}
		else
		{
			//Password error.
			$message .= $tie->getPhrase('passwordnotvalid');
		}
		
		if (!$message)
		{
			$query = 'UPDATE ' . DB_PREFIX . 'users SET username = \'' . $username . '\', password = \'' . $password . '\', email = \'' . $email . '\' WHERE id = \'' . $tie->user['id'] . '\';';
			$suit->db->query($query);
			//Log the user in now.
			setcookie(COOKIE_PREFIX . 'username', $username, time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(COOKIE_PREFIX . 'password', $password, time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
			$tie->redirect($tie->getPhrase('updatedsuccessfully'), 2, $suit->templates->getTemplate('path_url') . '/index.php?page=changeinfo');
		}
	}
	$output = $tie->parsePhrases($output);
	$output = $tie->parseTemplates($output);
	$array = Array
	(
		array('<message>', $message),
		array('<username>', $tie->user['username']),
		array('<email>', $tie->user['email']),
	);
	$output = $tie->replace($output, $array);
}
else
{
	$suit->templates->getTemplate('notauthorized');
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>