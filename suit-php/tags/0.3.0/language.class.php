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
class Languages
{
	/**
	Query the database for a language string.
	**@param string
	**@param int 
	**@param string
	**/
	function getLanguage($languages, $id, $pass)
	{
		global $tbl_prefix, $adminid, $adminpass, $system;
		
		$query = 'SELECT * FROM ' . $tbl_prefix . 'users WHERE id = \'' . $adminid . '\' AND password = \'' . $adminpass . '\'';
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$language = $row['language'];
			}
		}
		if ($language == 0)
		{
			$query = 'SELECT * FROM ' . $tbl_prefix . 'languages WHERE defaults = \'1\'';
		}
		else
		{
			$query = 'SELECT * FROM ' . $tbl_prefix . 'languages WHERE id = \'' . $language . '\'';
		}
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$languages2 = $system->mysql->escape($languages);
				$query = 'SELECT * FROM ' . $tbl_prefix . 'languages WHERE parent = \'' . $row['id'] . '\' AND title = \'' . $languages2 . '\'';
				$result2 = mysql_query($query);
				if (mysql_num_rows($result2)!=0)
				{
					while ($row2 = mysql_fetch_assoc($result2))
					{
						$GLOBALS['lcontent'] = $row2['content'];
					}
				}
				else
				{
					$GLOBALS['lcontent'] = 'Error: Language Not Found';
					$system->logError($GLOBALS['lcontent']);
				}
			}
		}
		else
		{
			$GLOBALS['lcontent'] = 'Error: Language Set Not Found';
			$system->logError($GLOBALS['lcontent']);
		}
	}
}
$mn = "Languages";
?>
