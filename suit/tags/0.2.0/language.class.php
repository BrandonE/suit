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
	Set a user's theme based on the database value
	**@param int User ID
	**@param string User's Password Hash
	**/
	function setLanguage($id, $pass)
	{
		global $suit;
		if (isset($id) && isset($pass))
		{
			$id = intval($id);
			$finduser_options = array(
			'where' => 'id = \'' . $id . '\' AND password = \'' . $pass . '\''
			);
			
			$finduser = $suit->mysql->select('' . TBL_PREFIX . 'users', 'language', $finduser_options);
			
			if ($finduser)
			{
				while ($row = mysql_fetch_assoc($result))
				{
					$language = intval($row['language']);
				}
			}
			else
			{
				$language = 0;
			}
		}
		else
		{
			$language = 0;
		}
		
		if ($language == 0)
		{
			$setlanguage_options = array('where' => 'defaults = \'1\'');
		}
		else
		{
			$setlanguage_options = array('where' => 'id = \'' . $language . '\'');
		}
		
		$setlanguage = $suit->mysql->select('' . TBL_PREFIX . 'languages', '*', $setlanguage_options);
		
		if ($setlanguage)
		{
			return $setlanguage;
		}
		else
		{
			return false;
		}
	}
	
	/**
	Query the database for a language string.
	**@param string The language
	**@param int User ID
	**@param string User's Password Hash
	**/
	function getLanguage($languages, $id, $pass)
	{
		global $language, $suit;
		
		$setlanguage = $this->setLanguage($id, $pass);
		if ($setlanguage)
		{
			while ($row = mysql_fetch_assoc($setlanguage))
			{
				$languages2 = $suit->mysql->escape($languages);
				$findlanguage_options = array(
				'where' => 'parent = \'' . $row['id'] . '\' AND title = \'' . $languages2 . '\''
				);
				$findlanguage = $suit->mysql->select('' . TBL_PREFIX . 'languages', '*', $findlanguage_options);
				
				if ($findlanguage)
				{
					while ($row2 = mysql_fetch_assoc($findlanguage))
					{
						$lcontent = $row2['content'];
					}
				}
				else
				{
					//That language does not exist. Of course, since we don't know which one is missing, we'll have to raw output it in English.
					$lcontent = 'Error: Language Not Found';
					$suit->logError($lcontent);//Oh yeah, and log the error.
				}
			}
		}
		else
		{
			//The language set doesn't exist, oddly. Again, output an error and log it.
			$lcontent = 'Error: Language Set Not Found';
			$suit->logError($lcontent);
		}
		return $lcontent;
	}
}
$mn = "Languages";
?>
