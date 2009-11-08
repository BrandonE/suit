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
require_once '../inc/adminheader.php';

if (!isset($_COOKIE['id']) && !isset($_COOKIE['pass']) && isset($_POST['submit']))
{
	$username = $system->mysql->escape($_POST['username']);
	$password = md5($_POST['password']);
	
	$usercheck_options = array(
	'where' => 'username = \'' . $username . '\' AND password = \'' . $password . '\''
	);
	
	$usercheck = $system->mysql->select(
	'users',
	'*',
	$usercheck_options
	);
	
	if ($usercheck)
	{
		while ($row = mysql_fetch_assoc($usercheck))
		{
			setcookie('id', $row['id'], time()+3600, ($COOKIE_PATH  . 'admin/'), $COOKIE_DOMAIN);
			setcookie('pass', $row['password'], time()+3600, ($COOKIE_PATH . 'admin/'), $COOKIE_DOMAIN);
			header('refresh: 0; url=./');
			exit;
		}
	}
	else
	{
		if (!empty($_POST['username']) && !empty($_POST['password']))
		{
			$message = 'nomatch';
		}
		else
		{
			$message = 'requiredfields';
		}
	}	 
}

if (loggedIn() == 2)
{
	$system->templates->getTemplate('index', $id, $pass, 0, 0);
}
else
{
	$system->templates->getTemplate('login', $id, $pass, 0, 0);
}
print $content;

require '../inc/adminfooter.php';
?>
