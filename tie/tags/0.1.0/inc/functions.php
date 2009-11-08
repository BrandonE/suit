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
function loggedIn()
{
	if (isset($_COOKIE['id']) && isset($_COOKIE['pass']))
	{
		global $system;
		
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
			//You're a user.
			$return = 1;
			
			while ($row = mysql_fetch_assoc($admincheck))
			{
				if ($row['admin'] == 1)
				{
					$return = 2;
				}
			}
			
			return $return;
		}
		else
		{
			//The user is not a member, so we return 0.
			$return = 0;
		}
		
		return $return;
	}
}
function logOut()
{
	global $COOKIE_PATH, $COOKIE_DOMAIN;
	
	setcookie('id', '', time()-3600, $COOKIE_PATH, $COOKIE_DOMAIN);
	setcookie('pass', '', time()-3600, $COOKIE_PATH, $COOKIE_DOMAIN);
	header('refresh: 0; url=./');
	exit;
}
?>
