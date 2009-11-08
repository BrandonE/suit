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
	$menu_vars = $suit->templates->getTemplate('menu', $chains);
	$menu = $menu_vars['output'];
	$admin_menu_vars = $suit->templates->getTemplate('admin_menu', $chains);
	$admin_menu = $admin_menu_vars['output'];
	$array = array
	(
		array('<1>', basename($_SERVER['SCRIPT_FILENAME'])),
		array('<2>', $admin_menu),
	);
	$menu = $suit->templates->replace($menu, $array);
	$output = str_replace('<1>', $menu, $output);
}
else
{
	$login_vars = $suit->templates->getTemplate('login', $chains);
	$login = $login_vars['output'];
	if (isset($_GET['error']) && ($_GET['error'] == 'nomatch' || $_GET['error'] == 'requiredfields'))
	{
		$lcontent = $suit->languages->getLanguage($_GET['error']);
		$message = $lcontent;
		
		if (isset($_GET['username']) && $_GET['error'] = 'nomatch')
		{
			$message = str_replace('<1>', htmlspecialchars($_GET['username']), $message);
		}
	}
	else
	{
		$message = '';
	}
	$array = array
	(
		array('<1>', $message),
		array('<2>', basename($_SERVER['SCRIPT_FILENAME']))
	);
	$login = $suit->templates->replace($login, $array);
	$output = str_replace('<1>', $login, $output);
}
?>
