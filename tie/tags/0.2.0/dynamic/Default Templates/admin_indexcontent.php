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
if ($suit->loggedIn() == 2)
{
	$layered[$layer+1] = $suit->templates->getDynamicTemplate('admin_indexcontentskeleton', $GLOBALS['id'], $GLOBALS['pass'], $layer+1);
	$lcontent = $suit->language->getLanguage('adminwelcome', $GLOBALS['id'], $GLOBALS['pass']);
	$layered[$layer+1] = str_replace('{1}', $lcontent, $layered[$layer+1]);
	$layered[$layer+2] = $suit->templates->getDynamicTemplate('admin_notes', $GLOBALS['id'], $GLOBALS['pass'], $layer+2);
	$notes_options = array('');
	$notes = $suit->mysql->select('notes', '*', $notes_options);
	if ($notes)
	{
		while ($row = mysql_fetch_assoc($notes))
		{
			$layered[$layer+2] = str_replace('{1}', $row['content'], $layered[$layer+2]);
		}
	}
	else
	{
		$layered[$layer+2] = str_replace('{1}', '', $layered[$layer+2]);
	}
	$layered[$layer+1] = str_replace('{2}', $layered[$layer+2], $layered[$layer+1]);
	$layered[$layer] = str_replace('{1}', $layered[$layer+1], $layered[$layer]);
}
else
{
	$lcontent = $lcontent = $suit->language->getLanguage('notauthorized', $GLOBALS['id'], $GLOBALS['pass']);
	$layered[$layer] = str_replace('{1}', $lcontent, $layered[$layer]);
}
?>
