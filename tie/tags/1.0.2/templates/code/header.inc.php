<?php
/**
**@This file is part of TIE.
**@TIE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@TIE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with TIE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parsePhrases($output);
$output = $suit->tie->parseTemplates($output);
$classselected = ' class="selected"';
$home = '';
$docs = '';
$community = '';
if ((!isset($_GET['page']) && !isset($suit->tie->vars['isadmin'])) || (isset($_GET['page']) && ($_GET['page'] == 'home')))
{
	$home = $classselected;
}
else
{
	if (isset($_GET['page']))
	{
		if ($_GET['page'] == 'docs')
		{
			$docs = $classselected;
		}
		elseif ($_GET['page'] == 'community')
		{
			$community = $classselected;
		}
	}
}
$menu = $suit->getTemplate('menu');
$admin_menu = (isset($suit->tie->vars['isadmin'])) ? $suit->getTemplate('menu_admin') : '';
$array = array
(
	array('<admin_menu>', $admin_menu),
	array('<welcome>', $suit->tie->language['welcome'])
);
$menu = $suit->tie->replace($menu, $array);
$exclude = array('cmd', 'limit', 'orderby', 'search', 'select', 'start', 'template');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$path = substr_replace($path, '', strlen($path)-1, 1);
$array = array
(
	array('<admin>', $path),
	array('<community>', $community),
	array('<docs>', $docs),
	array('<home>', $home),
	array('<menu>', $menu)
);
$output = $suit->tie->replace($output, $array);
?>