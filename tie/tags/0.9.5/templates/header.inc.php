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
if (isset($_COOKIE[COOKIE_PREFIX . 'username']) && isset($_COOKIE[COOKIE_PREFIX . 'password']) && isset($_GET['suit_logout']) && $_GET['suit_logout'] == 'true')
{	
	setcookie(COOKIE_PREFIX . 'username', '', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
	setcookie(COOKIE_PREFIX . 'password', '', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
	$tie->redirect($tie->getPhrase('loggedout'), 2, $suit->templates->getTemplate('file'));
	exit;
}
if (!isset($_COOKIE[COOKIE_PREFIX . 'username']) && !isset($_COOKIE[COOKIE_PREFIX . 'password']) && isset($_POST['suit_login']) && isset($_POST['suit_username']) && isset($_POST['suit_password']))
{
	$username = $suit->db->escape($_POST['suit_username']);
	$password = md5($_POST['suit_password'] . DB_SALT);
	$query = 'SELECT * FROM users WHERE username = \'' . $username . '\' AND password = \'' . $password . '\'';
	$check = $suit->db->query($query);
	if ($check && (mysql_num_rows($check)))
	{
		while ($row = $suit->db->fetch($check))
		{
			setcookie(COOKIE_PREFIX . 'username', $row['username'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
			setcookie(COOKIE_PREFIX . 'password', $row['password'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
		}
		$tie->redirect($tie->getPhrase('loggedin'), 2, $suit->templates->getTemplate('file'));
	}
}
if (isset($_POST['suit_languages']) && isset($_POST['language']))
{
	$language = intval($_POST['language']);
	if (isset($tie->languages[$language]) || $language == -1)
	{
		if ($tie->loggedIn() != 0)
		{
			$query = 'UPDATE users SET language = \'' . $language . '\' WHERE id = \'' . $tie->user['id'] . '\'';
			$suit->db->query($query);
		}
		else
		{
			setcookie(COOKIE_PREFIX . 'language', $language, time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
		}
		$tie->redirect($tie->getPhrase('updatedsuccessfully'), 2, $suit->templates->getTemplate('file'));
	}
}
if (isset($_POST['suit_search']) && isset($_POST['suit_searchval']))
{
	$tie->redirect('', 0, $suit->templates->getTemplate('path_url') . '/docs/' . $_POST['suit_searchval']);
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
	$welcome = $tie->getPhrase('welcome');
	$welcome = str_replace('<name>', $tie->user['username'], $welcome);
	$array = Array
	(
		array('<admin_menu>', $admin_menu),
		array('<welcome>', htmlentities($welcome))
	);
	$menu = $tie->replace($menu, $array);
	$output = str_replace('<menu>', $menu, $output);
}
else
{
	$login = $suit->templates->getTemplate('login');
	if (isset($_POST['suit_username']) && isset($_POST['suit_password']))
	{
		if (!empty($_POST['suit_username']) && !empty($_POST['suit_password']))
		{
				$login_message = str_replace('<username>', htmlspecialchars($_POST['suit_username']), $tie->getPhrase('nomatch'));
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
$form = $tie->languageForm($tie->user['language'], 1);
$classselected = $suit->templates->getTemplate('classselected');
$home = '';
$docs = '';
$community = '';
if ($suit->page['title'] == 'home')
{
	$home = $classselected;
}
elseif ($suit->page['title'] == 'docs')
{
	$docs = $classselected;
}
elseif ($suit->page['title'] == 'community')
{
	$community = $classselected;
}
$output = str_replace('<languages>', $form, $output);
$array = Array
(
	array('<languages>', $form),
	array('<home>', $home),
	array('<docs>', $docs),
	array('<community>', $community)
);
$output = $tie->replace($output, $array);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>