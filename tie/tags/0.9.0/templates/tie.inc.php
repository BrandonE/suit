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
class TIE
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
	**@param object SUIT Reference
	**/
	function __construct(&$suit)
	{
		$this->loaded = array();
		$this->user = array();
		$this->language = array();
		$this->suit = &$suit;
	}

	/**
	Implodes values by concatenating from an array.
	@param array Values
	
	@returns string Imploded string
	**/
	function replace($string, $array)
	{
		$pos = array();
		$add = 0;
		foreach ($array as $key => $value)
		{
			if ($string != str_replace($value[0], $value[1], $string))
			{
				if(stripos($string, $value[0], 0) == 0)
				{
					$pos[0] = $key;
					$position = 0;
				}
				else
				{
					$position = -1;
				}
				while($position = stripos($string, $value[0], $position+1)) 
				{
					$pos[$position] = $key;
				}
			}
		}
		ksort($pos);
		foreach ($pos as $key => $value)
		{
			$length = strlen($array[$value][0]);
			$string = substr_replace($string, $array[$value][1], $key+$add, $length);
			$add += strlen($array[$value][1]) - strlen($array[$value][0]);
		}
		
		return $string;
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
			$options = array
			(
				'where' => 'id = \'' . $id . '\' AND password =\'' . $pass . '\''
			);
			$check = $this->suit->db->select(DB_PREFIX . 'users', '*', $options);
			if ($check)
			{
				$return = mysql_fetch_assoc($check);
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
		if (isset($this->user['language']))
		{
			$language = $this->user['language'];
		}
		elseif (isset($_COOKIE[COOKIE_PREFIX . 'language']))
		{
			$language = $this->suit->db->escape($this->magic($_COOKIE[COOKIE_PREFIX . 'language']));
		}
		else
		{
			$language = 0;
		}
		if ($language > 0)
		{
			$options = array
			(
				'where' => 'id = \'' . $language . '\''
			);
			$realid = $language;
		}
		else
		{
			$options = array
			(
				'where' => 'defaults = \'1\''
			);
			$realid = 0;
		}
		$check = $this->suit->db->select(DB_PREFIX . 'languages', 'id', $options);
		if ($check)
		{
			while ($row = mysql_fetch_assoc($check))
			{
				//Create a return value.
				$return = $row;
			}
			$return['realid'] = $realid;
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
				$options = array
				(
					'where' => 'language = \'' . $this->language['id'] . '\' AND title = \'' . $this->suit->db->escape($language) . '\''
				);
				$check = $this->suit->db->select(DB_PREFIX . 'phrases', 'title, content', $options);
				if ($check)
				{
					while ($row = mysql_fetch_assoc($check))
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
			$string = $this->replace($string, $parse);
		}
		else
		{
			$string = 'Error: Illegal Content.';
			$this->suit->logError($output);
		}
		return $string;
	}
	
	function redirect($message, $refresh, $url)
	{
		$output = $this->suit->templates->getTemplate('success');
		$output = str_replace('<message>', $message, $output);
		print $output;
		header('refresh: ' . $refresh . '; url=' . $url);
		exit;
	}
	
	function parseTemplates($output)
	{
		$parse = array();
		//Match {expression_here} as templates
		preg_match_all('/\{((?:[^{}]*|(?R))*)\}/', $output, $parse, PREG_SET_ORDER);		
		//Foreach() the template parsing array and run respective actions for them.
		foreach ($parse as $key => $value)
		{
			//Run the getTemplate() function while iterating through the array, and then store the output of the templates inside a 3-Dimensional array.
			$parse[$key][1] = $this->suit->templates->getTemplate($parse[$key][1]);
		}
		$output = $this->replace($output, $parse);
		return $output;
	}
	
	/**
	Undoes what magic_quotes does
	**@param string The string to magically strip of slashes
	**@returns string PHP
	**/
	function magic($string)
	{
		$return = $string;
		//Detect magic_quotes_gpc
		if (get_magic_quotes_gpc())
		{
			//Detect magic_quotes_sybase
			if (ini_get('magic_quotes_sybase') == 'On')
			{
				//Yes, so convert
				$return = str_replace('\'\'', '\'', $var);
				$return = str_replace('""', '"', $var);
			}
			else
			{
				//No, so we'll run stripslashes() now.
				$return = stripslashes($return);
			}
		}
		//Return the value.
		return $return;
	}

	/**
	Creates List
	**@param string Table
	**@param string Order By
	**@param string Order By Type
	**@returns string List
	**/
	function createList($table, $options, $url, $get, $default, $phrases, $errorlog, $start, $limit, $select, $search)
	{
		$check = $this->suit->db->select($table, '*', '');
		if ($check)
		{
			$count = mysql_num_rows($check);
		}
		else
		{
			$count = 0;
		}
		if ($options['orderby_type'] == 'asc')
		{
			$orderbydisplay = $this->getPhrase('desc');
			$orderbylink = 'desc';
			$currentorderby = 'asc';
		}
		else
		{
			$orderbydisplay = $this->getPhrase('asc');
			$orderbylink = 'asc';
			$currentorderby = 'desc';
		}
		if ($select)
		{
			$selectdisplay = 'true';
		}
		else
		{
			$selectdisplay = 'false';
		}
		
		if (!$errorlog)
		{
			$return = $this->suit->templates->getTemplate('list');
			$entry = $this->suit->templates->getTemplate('list_entry');
			if ($phrases)
			{
				$replace = $this->suit->templates->getTemplate('list_phrase');
			}
			else
			{
				$replace = '';
			}
			$entry = str_replace('<phrase>', $replace, $entry);
		}
		else
		{
			$return = $this->suit->templates->getTemplate('list_errorlog');
			$entry = $this->suit->templates->getTemplate('list_errorlog_entry');
		}
		$list = '';
		$check = $this->suit->db->select($table, '*', $options);
		if ($check)
		{
			while ($row = mysql_fetch_assoc($check))
			{
				$list .= $entry;
				if (!$errorlog)
				{
					if ($default)
					{
						if ($row['defaults'])
						{
							$title = $this->suit->templates->getTemplate('default');
							$array = Array
							(
								array('<title>', htmlspecialchars($row[$options['orderby']])),
								array('<get>', $get)
							);
							$title = $this->replace($title, $array);
						}
						else
						{
							$title = $this->suit->templates->getTemplate('notdefault');
							$array = Array
							(
								array('<url>', $url),
								array('<title>', htmlspecialchars($row[$options['orderby']])),
								array('<id>', $row['id']),
								array('<get>', $get)
							);
							$title = $this->replace($title, $array);
						}
					}
					else
					{
						$title = htmlspecialchars($row[$options['orderby']]);
					}
					if ($select)
					{
						$checked = ' checked';
					}
					else
					{
						$checked = '';
					}
					$array = array
					(
						array('<title>', $title),
						array('<id>', $row['id']),
						array('<checked>', $checked),
						array('<url>', $url),
						array('<get>', $get),
						array('<start>', $start),
						array('<limitval>', $limit),
						array('<select>', $selectdisplay),
						array('<currentsearch>', $search),
						array('<currentorderby>', $currentorderby)
					);
					$list = $this->replace($list, $array);
				}
				else
				{
					$array = array
					(
						array('<error>', wordwrap($row['content'], strlen($row['content'])/2, '<br />', true)),
						array('<time>', $row['date']),
						array('<location>', $row['location'])
					);
					$list = $this->replace($list, $array);
				}
			}
		}
		else
		{
			$list = '';
		}
		$limitform = $this->suit->templates->getTemplate('limitform');
		$limitform = str_replace('<currentlimit>', $limit, $limitform);
		$num = $count;
		if ($num != 0)
		{
			if (($num / $limit) == (round(($num / $limit))))
			{
				$num--;
			}
			if (($num / $limit) != (round(($num / $limit))))
			{
				do
				{
					$num--;
				}
				while (($num / $limit) != (round(($num / $limit))));
			}
		}
		if (isset($options['where']) && ($options['where']))
		{
			$where = $options['where'];
		}
		else
		{
			$where = '1';
		}
		$pagelink = $this->suit->templates->getTemplate('pagelink');
		$pagelink = str_replace('<url>', $url, $pagelink);
		$link_1 = $this->pageLink($table, $options['orderby'], $options['orderby_type'], ($start - ($limit * 3)), 0, $this->getPhrase('first'), 0, $limit, $where, $pagelink);
		$link_2 = $this->pageLink($table, $options['orderby'], $options['orderby_type'], ($start - ($limit * 2)), -1, (($start / $limit) - 1), 0, $limit, $where, $pagelink);
		$link_3 = $this->pageLink($table, $options['orderby'], $options['orderby_type'], ($start - $limit), -1, ($start / $limit), 0, $limit, $where, $pagelink);
		$link_4 = ($start / $limit) + 1;
		$link_5 = $this->pageLink($table, $options['orderby'], $options['orderby_type'], ($start + $limit), -1, (($start / $limit) + 2), 1, $limit, $where, $pagelink);
		$link_6 = $this->pageLink($table, $options['orderby'], $options['orderby_type'], ($start + ($limit * 2)), -1, (($start / $limit) + 3), 1, $limit, $where, $pagelink);
		$link_7 = $this->pageLink($table, $options['orderby'], $options['orderby_type'], ($start + ($limit * 3)), strval($num), $this->getPhrase('last'), 1, $limit, $where, $pagelink);
		$searchform = $this->suit->templates->getTemplate('list_search');
		$searchform = str_replace('<currentsearch>', $search, $searchform);
		$array = array
		(
			array('<search>', $searchform),
			array('<list>', $list),
			array('<url>', $url),
			array('<get>', $get),
			array('<count>', $count),
			array('<start>', $start),
			array('<limitval>', $limit),
			array('<select>', $selectdisplay),
			array('<currentsearch>', $search),
			array('<currentorderby>', $currentorderby),
			array('<limit>', $limitform),
			array('<orderby>', $orderbydisplay),
			array('<orderby_type>', $orderbylink),
			array('<First>', $link_1),
			array('<1>', $link_2),
			array('<2>', $link_3),
			array('<3>', $link_4),
			array('<4>', $link_5),
			array('<5>', $link_6),
			array('<Last>', $link_7)
		);
		$return = $this->replace($return, $array);
		return $return;
	}

	/**
	Creates List of Links
	**@param int Check
	**@param int Start
	**@param string Text to Display
	**@param int DB Check
	**@param int Limit
	**@param string Template
	**@returns string List of Links
	**/
	function pageLink($table, $orderby, $orderby_type, $check, $start, $display, $db, $limit, $where, $link)
	{
		$success = false;
		if (!$db)
		{
			if ($check >= 0)
			{
				$success = true;
			}
		}
		else
		{
			$options = array
			(
				'orderby' => $orderby,
				'orderby_type' => $orderby_type,
				'limit' => $check . ':1',
				'where' => $where
			);
			$check2 = $this->suit->db->select($table, '*', $options);
			if ($check2)
			{
				$success = true;
			}
		}
		if ($success)
		{
			if ($start == -1)
			{
				$start = $check;
			}
			$array = array
			(
				array('<start>', $start),
				array('<limit>', $limit),
				array('<display>', $display)
			);
			$return = $this->replace($link, $array);
		}
		else
		{
			$return = '';
		}
		return $return;
	}

	/**
	Creates Delete Form
	**@param int id
	**@param string Table
	**@param string Column To Display
	**@returns string Delete Form
	**/	
	function deleteForm($id, $table, $display, $name)
	{
		$options = array
		(
			'where' => 'id = \'' . $id . '\''
		);
		$check = $this->suit->db->select($table, '*', $options);
		if ($check)
		{
			$return = $this->suit->templates->getTemplate('delete');
			while ($row = mysql_fetch_assoc($check))
			{
				$lcontent = $this->getPhrase('deleteconfirm');
				$lcontent = str_replace('<name>', $row[$display], $lcontent);
				$array = array
				(
					array('<message>', $lcontent),
					array('<id>', $id),
					array('<name>', $name)
				);
				$return = $this->replace($return, $array);
			}
		}
		else
		{
			$this->suit->templates->getTemplate('badrequest');
		}
		return $return;
	}
	
	/**
	Delete Handling
	**@param int id
	**@param string Table
	**@returns array MySQL Results
	**/	
	function deleteSubmit($id, $table)
	{
		$options = array
		(
			'where' => 'id = ' . $id . ''
		);
		$check = $this->suit->db->select($table, '*', $options);
		if ($check)
		{
			$query = 'DELETE FROM ' . $table . ' WHERE id = \'' . $id . '\'';
			mysql_query($query);
		}
		else
		{
			$this->suit->templates->getTemplate('badrequest');
		}
		return $check;
	}
	
	/**
	Default Handling
	**@param int id
	**@param string Table
	**@returns array MySQL Results
	**/	
	function defaultSubmit($id, $table)
	{
		$options = array
		(
			'where' => 'id = \'' . $id . '\''
		);
		$check = $this->suit->db->select($table, '*', $options);
		if ($check)
		{
			while ($row = mysql_fetch_assoc($check))
			{
				$query = 'UPDATE ' . $table . ' SET defaults = \'0\'';	
				mysql_query($query);
				$query = 'UPDATE ' . $table . ' SET defaults = \'1\' WHERE id = \'' . $id . '\'';	
				mysql_query($query);
			}
		}
		else
		{
			$this->suit->templates->getTemplate('badrequest');
		}
		return $check;
	}

	/**
	Edit Form
	**@param string Where
	**@param string Table
	**@returns array MySQL Results
	**/	
	function editForm($where, $table)
	{
		$options = array
		(
			'where' => $where
		);
		$check = $this->suit->db->select($table, '*', $options);	
		if (!$check)
		{
			$this->suit->templates->getTemplate('badrequest');
		}
		return $check;
	}
	
	/**
	Error
	**@param string error
	**@returns string Error Content
	**/	
	function errorForm($error)
	{
		switch ($error)
		{
			case 'missingtitle':
				$return = $this->getPhrase('missingtitle');
				break;
			case 'duplicatetitle':
				$return = $this->getPhrase('duplicatetitle');
				break;
			case 'duplicateusername':
				$return = $this->getPhrase('duplicateusername');
				break;
			default:
				$return = $this->getPhrase('undefinederror');
				break;
		}
		return $return;
	}
	
	/**
	Convert Line Breaks
	**@param string code
	**@param string source
	**@returns string Converted Code
	**/	
	function breakConvert($code, $source)
	{
		if (stristr($source, 'WIN'))
		{
			$char = "\r\n";
		}
		elseif (stristr($source, 'LIN'))
		{
			$char = "\n";
		}
		elseif (stristr($source, 'MAC'))
		{
			$char = "\r";
		}
		else
		{
			$char = "\n";
		}
		return preg_replace('/(\\r\\n)|\\r|\\n/', $char, $code);
	}
	
	/**
	Edit Handling
	**@param string Where
	**@param string Where 2
	**@param string Table
	**@param string Title
	**@param variable Old Title
	**@returns string Error
	**/	
	function editSubmit($where, $where2, $table, $title, $oldtitle, $column)
	{
		$options = array
		(
			'where' => $where
		);
		$check = $this->suit->db->select($table, '*', $options);
		if ($check)
		{
			while ($row = mysql_fetch_assoc($check))
			{
				$oldtitle = $row[$column];
				$options = array
				(
					'where' => $where2
				);
				$check2 = $this->suit->db->select($table, '*', $options);
				if (!$check2 || ($title == $oldtitle))
				{
					if ($title == '')
					{
						$error = 'missingtitle';
					}
				}
				else
				{
					$error = 'duplicatetitle';
				}
			}
		}
		else
		{
			$this->suit->templates->getTemplate('badrequest');
		}
		if (isset($error))
		{
			$return = $error;
		}
		else
		{
			$return = '';
		}
		return $return;
	}
	
	/**
	Add Handling
	**@param string Where
	**@param string Table
	**@param string Title
	**/	
	function addSubmit($where, $table, $title)
	{
		$options = array
		(
			'where' => $where
		);
		$check = $this->suit->db->select($table, '*', $options);
		if (!$check)
		{		
			if ($title == '')
			{
				$error = 'missingtitle';
			}
		}
		else
		{
			$error = 'duplicatetitle';
		}
		if (isset($error))
		{
			$return = $error;
		}
		else
		{
			$return = '';
		}
		return $return;
	}
	//Create an empty error array
	/**
	Perform a validation for the provided email address
	**@param string E-mail
	**@returns boolean true if succesful, false if failed.
	**/
	function validateEmail($email)
	{
		//The result will start off as valid, and then we'll go down validating.
		$return = true;
		//Start looking for the @ in the email, for starters.
		$index = strrpos($email, '@');
		//Check for the @. If there is none, there is no doubt this e-mail is invalidly formatted.
		if (is_bool($index) && !$index)
		{
			$return = false;
		}
		else
		{
			$domain = substr($email, $index + 1); //Grab the domain. It comes after the @
			$tie = substr($email, 0, $index); //Grab the local part; which comes before the @
			$tieLen = strlen($tie); //Length of local part.
			$domainLen = strlen($domain); //Length of domain
			//Local length must at least be 1 characters long, and must not exceed 64 characters. If this condition is met, the local part must
			if ($tieLen < 1 || $tieLen > 64)
			{
				$return = false;
			}
			//A domain must at least be 1 characters long, and must not exceed 255 characters. If this condition is met, the domain name is not valid.
			else if ($domainLen < 1 || $domainLen > 255)
			{
				$return = false;
			}
			//The local part must not start or end with a dot (.) character.
			else if ($tie[0] == '.' || $tie[$tieLen-1] == '.')
			{
				$return = false;
			}
			//It must also not have two consecutive dots.
			else if (preg_match('/\\.\\./', $tie))
			{
				$return = false;
			}
			//We cannot allow any invalid characters in the domain name.
			else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
			{
				$return = false;
			}
			//It must also not have two consecutive dots.
			else if (preg_match('/\\.\\./', $domain))
			{
				$return = false;
			}
			else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace('\\\\', '', $tie)))
			{
				//Not valid unless local part is quoted.
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace('\\\\', '', $tie)))
				{
					$return = false;
				}
			}
			//Find the domain in DNS. We'll check for the MX and A records, as they're important in validating the domain.
			if ($return && !(checkdnsrr($domain, 'MX')) || !(checkdnsrr($domain, 'A')))
			{
				$return = false;
			}
	   }
	   //Return the final result.
	   return $return;
	}
	
	/**
	Set Range
	**@param int start
	**@param int limit
	**@returns string limit
	**/	
	function setRange($start, $limit)
	{
		if ($start != 0)
		{
			if (!($start == intval($start) && ($start >= 0) && (($start / $limit) == round(($start / $limit)))))
			{
				$this->suit->templates->getTemplate('badrequest');
			}
		}
		if ($limit <= 0)
		{
			$this->suit->templates->getTemplate('badrequest');
		}
		return $start . ':' . $limit;
	}
	
	/**
	Language Form
	**@param int Language Id
	**@returns string Form
	**/	
	function languageForm($id)
	{
		$return = $this->suit->templates->getTemplate('language');
		$entry = '';
		$check = $this->suit->db->select(DB_PREFIX . 'languages', '*', '');	
		if ($check)
		{
			$form = $this->suit->templates->getTemplate('language_entry');
			while ($row2 = mysql_fetch_assoc($check))
			{
				$entry .= $form;
				if (intval($id) == $row2['id'])
				{
					$selected = ' selected';
				}
				else
				{
					$selected = '';
				}
				$array = array
				(
					array('<id>', $row2['id']),
					array('<title>', htmlentities($row2['title'])),
					array('<selected>', $selected)
				);
				$entry = $this->replace($entry, $array);
			}
		}
		$return = str_replace('<languages>', $entry, $return);
		return $return;
	}
}
$suit->templates->getTemplate('config');
$tie = new TIE($suit);
ob_start();
$start = microtime();
if (defined('COOKIE_PREFIX') && defined('COOKIE_LENGTH') && defined('COOKIE_PATH') && defined('COOKIE_DOMAIN'))
{
	$tie->user = $tie->setUser();
}
$tie->language = $tie->setLanguage();
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>