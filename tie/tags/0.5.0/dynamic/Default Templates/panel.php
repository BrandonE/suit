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
if ($suit->loggedIn() != 0)
{
	$menu = $suit->templates->getTemplate('menu', $rows);
	$admin_menuprint = $suit->templates->getTemplate('admin_menuprint', $rows);
	$array = array
	(
		array('{1}', basename($_SERVER['SCRIPT_FILENAME'])),
		array('{2}', $admin_menuprint),
	);
	$menu = $suit->templates->implosion($menu, $array);
	$output = str_replace('{1}', $menu, $output);
}
else
{
	$login = $suit->templates->getTemplate('login', $rows);
	$login_post = $suit->templates->getTemplate('login_post', $rows);
	$loginmessage = $suit->templates->getTemplate('loginmessage', $rows);
	$array = array
	(
		array('{1}', $loginmessage),
		array('{2}', basename($_SERVER['SCRIPT_FILENAME'])),
	);
	$login = $suit->templates->implosion($login, $array);
	$output = str_replace('{1}', $login, $output);
}
?>
