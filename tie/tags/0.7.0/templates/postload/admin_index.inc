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

	if (isset($_GET['cmd']) && ($_GET['cmd'] == 'updatenotes'))

	{

		$updatedmessage = $suit->templates->getTemplate('success');

		$updatedmessage = str_replace('<message>', $suit->templates->vars['local']->getPhrase('updatedsuccessfully'), $updatedmessage);		

	}

	else

	{

		$updatedmessage = '';

	}

	$admin_index_notes = $suit->templates->getTemplate('admin_index_notes');

	

	$notes = $suit->db->select(TBL_PREFIX . 'notes', '*');

	if ($notes)

	{

		while ($row = mysql_fetch_assoc($notes))

		{

			$admin_index_notes = str_replace('<notes>', $row['content'], $admin_index_notes);

		}

	}

	else

	{

		$admin_index_notes = str_replace('<notes>', '', $admin_index_notes);

	}

	$array = array

	(

		array('<updatedmessage>', $updatedmessage),

		array('<welcome>', $suit->templates->vars['local']->getPhrase('adminwelcome')),

		array('<notes>', $admin_index_notes)

	);

	$output = $suit->templates->replace($output, $array);

}
?>