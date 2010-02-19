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
	SUIT Reference
	**@var object
	**/
	var $suit;	
	
	/**
	The __construct()'s main use is to set-up a reference to SUIT, so we can avoid globalizing it.
	@param object SUIT Reference
	**/
	function __construct(&$reference)
	{
		$this->suit =& $reference;
	}
	
	/**
	Set a user's theme.
	**/
	function setLanguage()
	{
		if (defined('ID') && defined('PASS'))
		{
			$finduser_options = array(
			'where' => 'id = \'' . ID . '\' AND password = \'' . PASS . '\''
			);
			
			$finduser = $this->suit->mysql->select('' . TBL_PREFIX . 'users', 'language', $finduser_options);
			
			if ($finduser)
			{
				while ($row = mysql_fetch_assoc($finduser))
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
		
		$setlanguage = $this->suit->mysql->select('' . TBL_PREFIX . 'languages', '*', $setlanguage_options);
		
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
	function getLanguage($languages)
	{	
		$setlanguage = $this->setLanguage();
		if ($setlanguage)
		{
			while ($row = mysql_fetch_assoc($setlanguage))
			{
				$languages2 = $this->suit->mysql->escape($languages);
				$findlanguage_options = array(
				'where' => 'parent = \'' . $row['id'] . '\' AND title = \'' . $languages2 . '\''
				);
				$findlanguage = $this->suit->mysql->select('' . TBL_PREFIX . 'languages', '*', $findlanguage_options);
				
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
					$this->suit->logError($lcontent);//Oh yeah, and log the error.
				}
			}
		}
		else
		{
			//The language set doesn't exist, oddly. Again, output an error and log it.
			$lcontent = 'Error: Language Set Not Found';
			$this->suit->logError($lcontent);
		}
		return $lcontent;
	}
}
$mn = "Languages";
?>
