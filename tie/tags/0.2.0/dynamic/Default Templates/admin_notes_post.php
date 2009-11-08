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
	if (isset($_POST['notes']))
	{
		$content = $suit->mysql->escape($_POST['content']);
		
		$notes_options = array('');
		
		$notes = $suit->mysql->select('notes', '*', $notes_options);
		
		if ($notes)
		{
			$query = 'UPDATE ' . TBL_PREFIX . 'notes SET content = \'' . $content . '\'';
			mysql_query($query);
		}
		
		$GLOBALS['message'] = 'updatedsuccessfully';
	}
}
?>                                                   
