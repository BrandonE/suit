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
$tie = &$suit->templates->vars['tie'];
if (isset($_COOKIE[COOKIE_PREFIX . 'id']) && isset($_COOKIE[COOKIE_PREFIX . 'pass']) && isset($_GET['suit_logout']) && $_GET['suit_logout'] == 'true')
{	
	setcookie(COOKIE_PREFIX . 'id', '', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
	setcookie(COOKIE_PREFIX . 'pass', '', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
	$tie->redirect($tie->getPhrase('loggedout'), 2, $suit->templates->getTemplate('file'));
	exit;
}
if (!isset($_COOKIE[COOKIE_PREFIX . 'id']) && !isset($_COOKIE[COOKIE_PREFIX . 'pass']) && isset($_POST['suit_login']) && isset($_POST['suit_username']) && isset($_POST['suit_password']))
{
	$username = $suit->db->escape($tie->magic($_POST['suit_username']));
	$password = md5($tie->magic($_POST['suit_password']) . DB_SALT);
	$usercheck_options = array
	(
		'where' => 'username = \'' . $username . '\' AND password = \'' . $password . '\''
	);
	$usercheck = $suit->db->select(DB_PREFIX . 'users', '*', $usercheck_options);
	if ($usercheck)
	{
		while ($row = mysql_fetch_assoc($usercheck))
		{
			setcookie(COOKIE_PREFIX . 'id', $row['id'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(COOKIE_PREFIX . 'pass', $row['password'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
		}
		$tie->redirect($tie->getPhrase('loggedin'), 2, $suit->templates->getTemplate('file'));
	}
}
if (isset($_POST['suit_languages']) && isset($_POST['language']))
{
	$language = $suit->db->escape($tie->magic($_POST['language']));
	$options = array
	(
		'where' => 'id = \'' . $language . '\''
	);
	$check = $suit->db->select(DB_PREFIX . 'languages', '*', $options);
	if ($check || $language == 0)
	{
		if ($tie->loggedIn() != 0)
		{
			$query = 'UPDATE ' . DB_PREFIX . 'users SET language = \'' . $language . '\' WHERE id = \'' . $tie->user['id'] . '\'';	
			mysql_query($query);
		}
		else
		{
			setcookie(COOKIE_PREFIX . 'language', $language, time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
		}
		$tie->redirect($tie->getPhrase('updatedsuccessfully'), 2, $suit->templates->getTemplate('file'));
	}
}
$output = $tie->parsePhrases($output);
$output = $tie->parseTemplates($output);
if ($tie->loggedIn() != 0)
{
	$menu = $suit->templates->getTemplate('menu');
	if ($tie->loggedIn() == 2)
	{
		$admin_menu = $suit->templates->getTemplate('admin_menu');
	}
	else
	{
		$admin_menu = '';
	}
	$menu = str_replace('<admin_menu>', $admin_menu, $menu);
	$output = str_replace('<menu>', $menu, $output);
}
else
{
	$login = $suit->templates->getTemplate('login');
	if (isset($_POST['suit_username']) && isset($_POST['suit_password']))
	{
		if (!empty($_POST['suit_username']) && !empty($_POST['suit_password']))
     	{
				$login_message = str_replace('<username>', htmlspecialchars($tie->magic($_POST['suit_username'])), $tie->getPhrase('nomatch'));
		}
		else
		{
			$login_message = $tie->getPhrase('requiredfields');
		}
	}
	else
	{
		$login_message = '';
	}
	$login = str_replace('<message>', $login_message, $login);
	$output = str_replace('<menu>', $login, $output);
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>