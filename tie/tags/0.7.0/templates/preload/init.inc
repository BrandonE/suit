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

$suit->templates->getTemplate('config');
class LOCAL
{
	/**
	The current user
	**@var array
	**/     

	var $user;

	/**
	The current page ID we are on.
	**@var array
	**/  
	var $language;

       	/**
	The currently loaded languages.
	**@var array
	**/
	var $loaded;
	
	var $suit;
	
	/**
	The __construct()'s main use is to set-up a reference to SUIT, so we can avoid globalizing it.
	@param object SUIT Reference
	**/
	function __construct(&$suit)
	{
		$this->loaded = array();
		$this->user = array();
		$this->language = array();
		$this->suit = &$suit;
	}

	/**
	Set the user information and store it in an associative array for easier uses in the script.
	**/
	function setUser()
	{
		//Begin with the user's cookies, first.
		if (isset($_COOKIE[COOKIE_PREFIX . 'id']) && isset($_COOKIE[COOKIE_PREFIX . 'pass']))
		{
			$id = intval($_COOKIE[COOKIE_PREFIX . 'id']);
			$pass = $this->suit->db->escape($_COOKIE[COOKIE_PREFIX . 'pass']);
			//Query the database with the supplied information.
			$usercheck_options = array('where' => 'id = \'' . $id . '\' AND password =\'' . $pass . '\'');
			$usercheck = $this->suit->db->select(TBL_PREFIX . 'users', '*', $usercheck_options);
			if ($usercheck)
			{
				$return = mysql_fetch_assoc($usercheck);
			}
			else
			{
				//The user was not found. You're a guest, and therefor, and you have a userid of 0. Your password is blank as well.
				$return['id'] = 0;
				$return['password'] = '';
				//Delete the cookies now. They are useless.
				setcookie(COOKIE_PREFIX . 'id', '', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				setcookie(COOKIE_PREFIX . 'pass', '', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
       			}
		}
		else
		{
			$return['id'] = 0;
			$return['password'] = '';
		}
		return $return;
	}

	/**
	Queries the database to check if the user is logged in.
	**@returns integer User Level
	**/
	function loggedIn()
	{
		//We'll verify by using the $user[] array which was set initially.
		//If the $user['id'] value is greater than zero, then this means you are a valid user.
		if (isset($this->user['id']) && $this->user['id'] > 0)
		{
			//You're an authorized normal user, in this case.
			$return = 1;
			//If the integer value for your user ID specifies you're an admin, then the return value is set to 2.
			if ($this->user['admin'] == 1)
			{
				//You're an authorized administrator, in this case.
				$return = 2;
			}
		}
		else
		{
			//The user is not a valid member, so in this case, we return a value of 0, which denotes the user is not logged in.
			$return = 0;
		}	
		//Return the user-level now.
		return $return;
	}

	/**
	Set a user's language.
	**/
	function setLanguage()
	{
		if (isset($this->user['id']) && isset($this->user['language']) && ($this->user['language'] > 0))
		{
			$setlanguage_options = array('where' => 'id = \'' . $this->user['language'] . '\'');
		}
		else
		{
			$setlanguage_options = array('where' => 'defaults = \'1\'');
		}
		$setlanguage = $this->suit->db->select(TBL_PREFIX . 'languages', 'id', $setlanguage_options);
		if ($setlanguage)
		{
			while ($row = mysql_fetch_assoc($setlanguage))
			{
				//Create a return value.
				$return = $row;
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
	function getPhrase($language)
	{
		//Pre-set variable.
		$lcontent = '';
		//If the current language was valid, then proceed.
		if (isset($this->language['id']))
		{
			//Save some querying: was the language string already loaded?
			if (!array_key_exists($language, $this->loaded))
			{
				$findlanguage_options = array('where' => 'language = \'' . $this->language['id'] . '\' AND title = \'' . $this->suit->db->escape($language) . '\'');
				$findlanguage = $this->suit->db->select(TBL_PREFIX . 'phrases', 'title, content', $findlanguage_options);
				if ($findlanguage)
				{
					while ($row = mysql_fetch_assoc($findlanguage))
					{
						$lcontent = $row['content'];
					}
				}
				else
				{
					//That language does not exist. Of course, since we don't know which one is missing, we'll have to raw output it in English.
					$lcontent = 'Error: Phrase ' . $language . ' not found';
					$this->suit->logError($lcontent); //Oh yeah, and log the error.
				}
				
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
			$lcontent = 'Error: Language Not Found';
			$this->suit->logError($lcontent);
		}
		return $lcontent;
	}

	function parsePhrases($string)
	{
		$pass = true;
		if ($string != '')
		{
			if (!(strstr('[', $string) == 0 || strstr(']', $string) == 0))
			{
				$pass = false;
			}
		}
		if ($pass)
		{
			//Match [expression_here] as languages.
			preg_match_all('/\[((?:[^\[\]]*|(?R))*)\]/', $string, $parse, PREG_SET_ORDER);
			//Foreach() the language parsing array  and run respective actions for them.
			foreach ($parse as $key => $value)
			{
				//Run the getLanguage() function.
				$parse[$key][1] = $this->getPhrase($parse[$key][1]);
			}
			$string = $this->suit->templates->replace($string, $parse);
		}
		else
		{
			$string = 'Error: Illegal Content.';
			$this->suit->logError($output);
		}
		return $string;
	}
}
$suit->templates->vars['local'] = new LOCAL($suit);
ob_start();
$start = microtime();
if (defined('COOKIE_PREFIX') && defined('COOKIE_LENGTH') && defined('COOKIE_PATH') && defined('COOKIE_DOMAIN'))
{
	$suit->templates->vars['local']->user = $suit->templates->vars['local']->setUser();
}
$suit->templates->vars['local']->language = $suit->templates->vars['local']->setLanguage();
?>