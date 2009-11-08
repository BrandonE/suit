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
$suit->templates->getTemplate('init');
$output = $suit->templates->vars['local']->parsePhrases($output);
if ($suit->templates->vars['local']->loggedIn($suit) == 2)
{
	if (isset($_POST['notes']))
	{
		if (isset($_POST['content']))
		{
			$content = $suit->db->escape($_POST['content']);
			$notes = $suit->db->select(TBL_PREFIX . 'notes', '*');
			if ($notes)
			{
				$query = 'UPDATE ' . TBL_PREFIX . 'notes SET content = \'' . $content . '\'';
				mysql_query($query);
			}
		}
	}
}
?>