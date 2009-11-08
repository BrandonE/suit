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
if ($suit->templates->vars['local']->loggedIn() != 0)

{

	$menu = $suit->templates->getTemplate('menu');

	$admin_menu = $suit->templates->getTemplate('admin_menu');

	$array = array

	(

		array('<admin_menu>', $admin_menu),

	);

	$menu = $suit->templates->replace($menu, $array);

	$output = str_replace('<menu>', $menu, $output);

}

else

{

	$login = $suit->templates->getTemplate('login');

	if (isset($_GET['error']) && ($_GET['error'] == 'nomatch' || $_GET['error'] == 'requiredfields'))

	{

		$lcontent = $suit->templates->vars['local']->getPhrase($_GET['error']);

		$login_message = $lcontent;

		

		if (isset($_GET['username']) && $_GET['error'] = 'nomatch')

		{

			$login_message = str_replace('<username>', htmlspecialchars($_GET['username']), $login_message);

		}

	}

	else

	{

		$login_message = '';

	}

	$array = array

	(

		array('<message>', $login_message),

	);

	$login = $suit->templates->replace($login, $array);

	$output = str_replace('<menu>', $login, $output);

}

?>