<?php/****@This file is part of The SUIT Framework.**@SUIT is free software: you can redistribute it and/or modify**@it under the terms of the GNU General Public License as published by**@the Free Software Foundation, either version 3 of the License, or**@(at your option) any later version.**@SUIT is distributed in the hope that it will be useful,**@but WITHOUT ANY WARRANTY; without even the implied warranty of**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the**@GNU General Public License for more details.**@You should have received a copy of the GNU General Public License**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.**/

if ($suit->loggedIn() != 0)
{
	if (isset($_POST['password']))
	{	
		$getsalt = $suit->db->select(TBL_PREFIX . 'salt', '*', '');
		
		if ($getsalt)
		{
			while ($row = mysql_fetch_assoc($getsalt))
			{
				$salt = $row['content'];
			}
		}
	
		$password = md5($_POST['old'] . $salt);
		
		$usercheck_options = array('where' => 'id = \'' . $suit->userinfo['id'] . '\' AND password = \'' . $password . '\'');
		
		$usercheck = $suit->db->select(TBL_PREFIX . 'users', '*', $usercheck_options);
		
		if ($usercheck)
		{
			while ($row = mysql_fetch_assoc($usercheck))
			{
				$newpassword = md5($_POST['new'] . $salt);
				$query = 'UPDATE ' . TBL_PREFIX . 'users SET password = \'' . $newpassword . '\' WHERE id = \'' . $row['id'] . '\'';
				mysql_query($query);

				setcookie(COOKIE_PREFIX . 'id', $row['id'], time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				setcookie(COOKIE_PREFIX . 'pass', $newpassword, time() + COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				header('refresh: 0; url=?cmd=password&submitted=1');
				exit;
			}
		}
		else
		{
			header('refresh: 0; url=?error=wrongpassword');
			exit;
		}
	}

	if ((isset($_GET['error']) && ($_GET['error'] == 'wrongpassword')) || (isset($_GET['cmd']) && ($_GET['cmd'] == 'password')))
	{
		if ((isset($_GET['error']) && ($_GET['error'] == 'wrongpassword')))
		{
			$lcontent = $suit->languages->getLanguage('wrongpassword');
			$output = str_replace('<1>', $lcontent, $output);
		}
		else
		{
			$lcontent = $suit->languages->getLanguage('changedsuccessfully');
			$success_vars = $suit->templates->getTemplate('success');
			$success = $success_vars['output'];
			$success = str_replace('<1>', $lcontent, $success);
			$output = str_replace('<1>', $success, $output);
		}
	}
	else
	{
		$output = str_replace('<1>', '', $output);
	}
}
else
{
	header('refresh: 0; url=./index.php');
	exit;
}

?>
