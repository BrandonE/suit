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
	$admin_indexcontentskeleton = $suit->templates->getTemplate('admin_indexcontentskeleton',$rows);
	$lcontent = $suit->language->getLanguage('adminwelcome');
	$admin_indexcontentskeleton = str_replace('{1}', $lcontent, $admin_indexcontentskeleton);
	$admin_notes = $suit->templates->getTemplate('admin_notes',$rows);
	
	$notes = $suit->mysql->select(TBL_PREFIX . 'notes', '*');
	if ($notes)
	{
		while ($row = mysql_fetch_assoc($notes))
		{
			$admin_notes = str_replace('{1}', $row['content'], $admin_notes);
		}
	}
	else
	{
		$admin_notes = str_replace('{1}', '', $admin_notes);
	}
	$admin_indexcontentskeleton = str_replace('{2}', $admin_notes, $admin_indexcontentskeleton);
	$output = str_replace('{1}', $admin_indexcontentskeleton, $output);
}
else
{
	$lcontent = $lcontent = $suit->language->getLanguage('notauthorized');
	$output = str_replace('{1}', $lcontent, $output);
}
?>
