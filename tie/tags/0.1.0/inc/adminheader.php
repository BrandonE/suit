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
global $content, $layered, $lcontent, $dcontent, $id, $pass, $message;

$system->mysql->connect($SQL_HOST, $SQL_USER, $SQL_PASS);

if (isset($_GET['logout']) && $_GET['logout'] == true)
{
	logOut();
}

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
			setcookie('id', $row['id'], time()+3600, ($COOKIE_PATH), $COOKIE_DOMAIN);
			setcookie('pass', $row['password'], time()+3600, ($COOKIE_PATH), $COOKIE_DOMAIN);
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
if (isset($_COOKIE['id']) && isset($_COOKIE['pass']))
{
	$id = $system->mysql->escape($_COOKIE['id']);
	$pass = $system->mysql->escape($_COOKIE['pass']);
	
	$admincheck_options = array(
	'where' => 'id = \'' . $id . '\' AND password =\'' . $pass . '\''
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
			if ($row2['admin'] == 1)
			{
						$authorized = true;
			}
		}
	}
}
?>
