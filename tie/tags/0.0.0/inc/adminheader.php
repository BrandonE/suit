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
require '../config.php';
require 'init.php';
global $content, $layered, $lcontent, $dcontent, $adminid, $adminpass, $message;

$system->mysql->connect($SQL_HOST, $SQL_USER, $SQL_PASS);

if (isset($_GET['logout']) && $_GET['logout'] == true)
{
	logOut();
}

if (!isset($_COOKIE['adminid']) && !isset($_COOKIE['adminpass']) && isset($_POST['submit']))
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
			setcookie('adminid', $row['id'], time()+3600, ($COOKIE_PATH  . 'admin/'), $COOKIE_DOMAIN);
			setcookie('adminpass', $row['password'], time()+3600, ($COOKIE_PATH . 'admin/'), $COOKIE_DOMAIN);
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
$authorized = false;
if (isset($_COOKIE['adminid']) && isset($_COOKIE['adminpass']))
{
	$adminid = $system->mysql->escape($_COOKIE['adminid']);
	$adminpass = $system->mysql->escape($_COOKIE['adminpass']);
	
	$admincheck_options = array(
	'where' => 'id = \'' . $adminid . '\' AND password =\'' . $adminpass . '\''
	);
	
	$admincheck = $system->mysql->select(
	'users',
	'*',
	$admincheck_options
	);
	
	if ($admincheck)
	{
		while ($row = mysql_fetch_assoc($admincheck))
		{
			$groupcheck_options = array(
			'where', 'id = \'' . $row['group'] . '\''
			);
			
			$groupcheck = $system->mysql->select(
			'groups',
			'*',
			$groupcheck_options
			);
			
			if ($groupcheck)
			{
				while ($row2 = mysql_fetch_assoc($groupcheck))
				{
					if ($row2['admin'] == 1)
					{
						$authorized = true;
					}
				}
			}
		}
	}
}
?>
