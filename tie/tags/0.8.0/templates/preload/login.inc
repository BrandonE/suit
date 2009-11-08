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
if (!isset($_COOKIE[COOKIE_PREFIX . 'id']) && !isset($_COOKIE[COOKIE_PREFIX . 'pass']) && isset($_POST['suit_login']))
{
	if (isset($_POST['suit_username']) && isset($_POST['suit_password']))
	{
		$username = $suit->db->escape($_POST['suit_username']);
		$password = md5($_POST['suit_password'] . SALT);
		$usercheck_options = array('where' => 'username = \'' . $username . '\' AND password = \'' . $password . '\'');
		$usercheck = $suit->db->select(TBL_PREFIX . 'users', '*', $usercheck_options);
		if ($usercheck)
		{
			while ($row = mysql_fetch_assoc($usercheck))
			{
				setcookie(COOKIE_PREFIX . 'id', $row['id'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				setcookie(COOKIE_PREFIX . 'pass', $row['password'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
			}
			header('refresh: 0; url=' . $suit->templates->getTemplate('file'));
			exit;
		}
 		if (!empty($_POST['suit_username']) && !empty($_POST['suit_password']))
     		{
			header('refresh: 0; url=?error=nomatch&username=' . $username);
			exit;
		}
	}
	header('refresh: 0; url=?error=requiredfields');
	exit;
}
$output = $suit->templates->vars['local']->parsePhrases($output);
?>