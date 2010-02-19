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
	var $current_language;
	var $current_language_title;

	/**
	The currently loaded languages.
	**@var array
	**/
	var $loaded_language = array();

	/**
	Whether or not to recache
	**@var bool
	**/
	var $recache = false;
	
	/**
	The __construct()'s main use is to set-up a reference to SUIT, so we can avoid globalizing it.
	@param object SUIT Reference
	**/
	function __construct(&$reference)
	{
		$this->suit = &$reference;
	}
	
	/**
	Set a user's theme.
	**/
	function setLanguage()
	{
		if (isset($this->suit->userinfo['id']) && isset($this->suit->userinfo['language']))
		{
			$language = $this->suit->userinfo['language'];
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
		
		$setlanguage = $this->suit->mysql->select(TBL_PREFIX . 'languages', '*', $setlanguage_options);
		
		if ($setlanguage)
		{
			while ($language = mysql_fetch_assoc($setlanguage))
			{
				$return = $language['id'];
				$this->current_language_title = $language['title'];
			}
		}
		else
		{
			$return = 0;
		}
		
		return $return;
	}
	
	/**
	Query the database for a language string.
	**@param string The language string key
	**/
	function getLanguage($language)
	{
		$lcontent = '';
		//If the current language was valid, then proceed.
		if ($this->current_language)
		{
			//Save some querying: was the language string already loaded?
			if (!array_key_exists($language, $this->loaded_language))
			{
				$languages = $this->suit->mysql->escape($language);
				$findlanguage_options = array(
				'where' => 'parent = \'' . $this->current_language . '\' AND title = \'' . $languages . '\''
				);
				$findlanguage = $this->suit->mysql->select(TBL_PREFIX . 'languages', '*', $findlanguage_options);
				
				if ($findlanguage)
				{
					$rows = array();
					$row = mysql_fetch_assoc($findlanguage);
					foreach ($row as $key => $value)
					{
						$rows[$row['title']][$key] = $value;
					}
					$lcontent = $this->parseLanguage($row['title'], $rows);
				}
				else
				{
					//That language does not exist. Of course, since we don't know which one is missing, we'll have to raw output it in English.
					$lcontent = 'Error: Language ' . $language . ' not found';
					$this->suit->logError($lcontent);//Oh yeah, and log the error.
				}
				$this->loaded_language[$language] = $lcontent;
				$this->recache = true;
			}
			else
			{
				$lcontent = $this->loaded_language[$language];
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

        /**
	Parse Template
	**@param string Language Title
	**@param array Rows
	**/
	function parseLanguage($language, $rows)
	{
		$lcontent = $rows[$language]['content'];
		//Add this to the array of already loaded languages to save a query if it is reused.
		$this->loaded_language[$rows[$language]['title']] = $lcontent;
		return $lcontent;
	}

        /**
	Generate Cache
	**/
	function generateCache()
	{
		$filepath = PATH_HOME . 'cache/languages/' . $this->current_language_title;
		//If the directory does not exist, then the directory shall be created and CHMOD'd to 0777.
		if (!is_dir($filepath))
		{
			mkdir($filepath);
			chmod($filepath, 0777);
		}
		//Concatanate the current path with to form the file.
		$filepath = $filepath . '/' . $this->suit->setID . '.php';
		if (file_exists($filepath))
		{
			require $filepath;
		}
		if (isset($languages))
		{
			//Add values from the $templates array into a variable called $in for future the MySQL function IN().
			$in = '';
			end($languages);
			$lastkey = key($languages);
			foreach ($languages as $key => $value)
			{
				$value = addslashes($value);
				$in .= '\'' . $value . '\'';
				if ($key != $lastkey)
				{
					$in .= ', ';
				}
			}
			//Query using the IN() function to shorten query numbers.
			$languagecheck_options = array('where' => 'title IN(' . $in . ') AND parent = \'' . $this->current_language . '\'');	
			$languagecheck = $this->suit->mysql->select(TBL_PREFIX . 'languages', '*', $languagecheck_options);
			if ($languagecheck)
			{
				$rows = array();
				while ($row = mysql_fetch_assoc($languagecheck))
				{
					foreach ($row as $key => $value)
					{
						$rows[$row['title']][$key] = $value;
					}
				}
				foreach ($rows as $key => $value)
				{
					$output = $this->parseLanguage($key, $rows);
					$this->loaded_language[$key] = $output;
				}
			}
		}
	}
}
$mn = "Languages";
?>
