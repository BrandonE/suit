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
if ($tie->loggedIn() != 0)
{
	if (isset($_POST['password']))
	{	
		$password = md5($tie->magic($_POST['old']) . DB_SALT);
		$userinfo = $tie->setUser($suit);
		$usercheck_options = array('where' => 'id = \'' . $userinfo['id'] . '\' AND password = \'' . $password . '\'');
		$usercheck = $suit->db->select(DB_PREFIX . 'users', '*', $usercheck_options);
		if ($usercheck)
		{
			while ($row = mysql_fetch_assoc($usercheck))
			{
				$newpassword = md5($tie->magic($_POST['new']) . DB_SALT);
				$query = 'UPDATE ' . DB_PREFIX . 'users SET password = \'' . $newpassword . '\' WHERE id = \'' . $row['id'] . '\'';
				mysql_query($query);
				setcookie(COOKIE_PREFIX . 'id', $row['id'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				setcookie(COOKIE_PREFIX . 'pass', $newpassword, time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				$tie->redirect($tie->getPhrase('changedsuccessfully'), 2, $suit->templates->getTemplate('path_url') . '/index.php?page=password');
			}
		}
		else
		{
			$output = $tie->parseTemplates($output);
			$lcontent = $tie->getPhrase('wrongpassword');
			$output = str_replace('<message>', $lcontent, $output);
		}
	}
	else
	{
		$output = $tie->parseTemplates($output);
		$output = str_replace('<message>', '', $output);
	}
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