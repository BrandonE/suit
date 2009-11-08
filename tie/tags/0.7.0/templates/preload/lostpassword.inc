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
$suit->templates->getTemplate('init');
$output = $suit->templates->vars['local']->parsePhrases($output);
if ($suit->templates->vars['local']->loggedIn() == 0)
{
	if (isset($_POST['lostpassword']))
	{
		if (isset($_POST['email']))
		{	
			$usercheck_options = array('where' => 'email = \'' . $suit->db->escape($_POST['email'], 0) . '\'');
			$usercheck = $suit->db->select(TBL_PREFIX . 'users', '*', $usercheck_options);
			if ($usercheck)
			{
				while ($row = mysql_fetch_assoc($usercheck))
				{
					$string = substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5);
					$password = substr(md5(md5('1skafd;p32q0' . uniqid(md5(rand()), true))), 0, 5);
					$passwordsalted = md5($password . SALT);
					$query = 'UPDATE ' . TBL_PREFIX . 'users SET recover_string = \'' . $string . '\', recover_password = \'' . $passwordsalted . '\' WHERE id = \'' . $row['id'] . '\'';
					mysql_query($query);
					$body = $suit->templates->vars['local']->getPhrase('message_body');
					$body = str_replace('<password>', $password, $body);
					$body = str_replace('<base_url>', BASE_URL, $body);
					$body = str_replace('<string>', $string, $body);
					$body = str_replace('<id>', $row['id'], $body);
					mail($row['email'], $suit->templates->vars['local']->getPhrase('message_subject'), $body, $suit->templates->vars['local']->getPhrase('emailheaders')) or die ($suit->templates->vars['local']->getPhrase('maildeliveryfailed'));
				}
				$lostpassword_message = 'passwordsent';
			}
			else
			{
				$lostpassword_message = 'emailnotfound';
			}
		}
	}
	if (isset($_GET['id']) && isset($_GET['string']))
	{
		$usercheck_options = array('where' => 'id = \'' . $suit->db->escape($_GET['id'], 0) . '\' AND recover_string = \'' . $suit->db->escape($_GET['string'], 0) . '\'');
		$usercheck = $suit->db->select(TBL_PREFIX . 'users', '*', $usercheck_options);
		if ($usercheck)
		{
			while ($row = mysql_fetch_assoc($usercheck))
			{
				$query = 'UPDATE ' . TBL_PREFIX . 'users SET password = \'' . $row['recover_password'] . '\', recover_string = \'\', recover_password = \'\' WHERE id = \'' . $row['id'] . '\'';
				mysql_query($query);				
			}
			$lostpassword_message = 'passwordchanged';
		}
		else
		{
			$lostpassword_message = 'passwordexpired';
		}	
	}
}
else
{
	$suit->templates->getTemplate('notauthorized');
}
?>