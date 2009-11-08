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
	The current set language.
	**@var integer
	**/
	var $language_id;
	var $language_title;

	/**
	The currently loaded languages.
	**@var array
	**/
	var $loaded;

	/**
	Content of cached columns
	**@var array
	**/
	var $content;
	
	/**
	The __construct()'s main use is to set-up a reference to SUIT, so we can avoid globalizing it.
	@param object SUIT Reference
	**/
	function __construct(&$reference)
	{
		$this->suit = &$reference;
		$this->loaded = array();
		$this->content = array();
	}
	
	/**
	Query the database for a language string.
	**@param string The language string key
	**/
	function getLanguage($language)
	{
		//Pre-set variable.
		$lcontent = '';
		//If the current language was valid, then proceed.
		if ($this->suit->language['id'])
		{
			//Save some querying: was the language string already loaded?
			if (!array_key_exists($language, $this->loaded))
			{
				if (!(isset($this->content[$language])))
				{
					$languages = $this->suit->db->escape($language);
					$findlanguage_options = array('where' => 'parent = \'' . $this->suit->language['id'] . '\' AND title = \'' . $languages . '\'');
					$findlanguage = $this->suit->db->select(TBL_PREFIX . 'languages', 'title, content', $findlanguage_options);
					
					if ($findlanguage)
					{
						while ($row = mysql_fetch_assoc($findlanguage))
						{
							$this->content[$language] = $row['content'];
						}
					}
					else
					{
						//That language does not exist. Of course, since we don't know which one is missing, we'll have to raw output it in English.
						$this->content[$language] = 'Error: Language ' . $language . ' not found';
						$this->suit->logError($this->content[$language]); //Oh yeah, and log the error.
					}
				}

				$lcontent = $this->content[$language];

				//Add this to the array of already loaded languages to save a query if it is reused.
				$this->loaded[$language] = $lcontent;
			}
			else
			{
				$lcontent = $this->loaded[$language];
			}
		}
		else
		{
			//The language set doesn't exist, oddly. Again, output an error and log it.
			$lcontent = 'Error: Language Set "' . $this->suit->language['title'] .'" Not Found';
			$this->suit->logError($lcontent);
		}
		return $lcontent;
	}
}
$mn = 'Languages';
?>
