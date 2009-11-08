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
if ($suit->templates->vars['local']->loggedIn() == 2)
{
	if (isset($_POST['notes']))
	{
		$updatedmessage = $suit->templates->getTemplate('success');
		$updatedmessage = str_replace('<message>', $suit->templates->vars['local']->getPhrase('updatedsuccessfully'), $updatedmessage);		
	}
	else
	{
		$updatedmessage = '';
	}
	$notes_get = $suit->db->select(TBL_PREFIX . 'notes', '*');
	if ($notes_get)
	{
		while ($row = mysql_fetch_assoc($notes_get))
		{
			$notes = $row['content'];
		}
	}
	else
	{
		$notes = '';
	}
	$array = array
	(
		array('<updatedmessage>', $updatedmessage),
		array('<welcome>', $suit->templates->vars['local']->getPhrase('adminwelcome')),
		array('<notes>', $notes)
	);
	$output = $suit->templates->replace($output, $array);
}
?>