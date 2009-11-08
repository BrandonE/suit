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
	var $user = array();
	
	/**
	The current page ID we are on.
	**@var array
	**/
	var $languages = array
	(
		array('English', '1')
	);

	/**
	The currently loaded phrases.
	**@var array
	**/
	var $phrases = array();
	
	var $suit;
	
	/**
	The __construct()'s main use is to set-up a reference to SUIT, so we can avoid globalizing it.
	**@param object SUIT Reference
	**/
	function __construct(&$suit)
	{
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
		if (isset($_COOKIE[COOKIE_PREFIX . 'username']) && isset($_COOKIE[COOKIE_PREFIX . 'password']))
		{
			$username = $this->suit->db->escape($_COOKIE[COOKIE_PREFIX . 'username']);
			$password = $this->suit->db->escape($_COOKIE[COOKIE_PREFIX . 'password']);
			//Query the database with the supplied information.
			$query = 'SELECT * FROM users WHERE username = \'' . $username . '\' AND password = \'' . $password . '\'';
			$check = $this->suit->db->query($query);
			if ($check && (mysql_num_rows($check)))
			{
				$return = mysql_fetch_assoc($check);
			}
			else
			{
				//The user was not found. You're a guest, and therefor, and you have a userid of 0. Your password is blank as well.
				$return['username'] = '';
				$return['password'] = '';
				//Delete the cookies now. They are useless.
				setcookie(COOKIE_PREFIX . 'username', '', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
				setcookie(COOKIE_PREFIX . 'password', '', time() - COOKIE_LENGTH, COOKIE_PATH, COOKIE_DOMAIN);
       		}
		}
		else
		{
			$return['id'] = 0;
			$return['password'] = '';
			if (isset($_COOKIE[COOKIE_PREFIX . 'language']))
			{
				$return['language'] = $_COOKIE[COOKIE_PREFIX . 'language'];
			}
			else
			{
				$return['language'] = -1;
			}
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
	Query the database for a language string.
	**@param string The language string key
	**/
	function getPhrase($phrase)
	{
		if (isset($this->phrases[$phrase]))
		{
			if ($this->user['language'] != -1)
			{
				$output = $this->phrases[$phrase][$this->user['language']];
			}
			else
			{
				foreach ($this->languages as $key => $value)
				{
					if ($value[1])
					{
						$output = $this->phrases[$phrase][$key];
					}
				}
			}
		}
		else
		{
			$output = '';
		}
		return $output;
	}

	function parsePhrases($output)
	{
		$parse = array();
		//Match {expression_here} as templates
		preg_match_all('/\[(.*?)\]/', $output, $parse, PREG_SET_ORDER);		
		//Foreach() the template parsing array and run respective actions for them.
		foreach ($parse as $key => $value)
		{
			//Run the getTemplate() function while iterating through the array, and then store the output of the templates inside a 3-Dimensional array.
			$parse[$key][1] = $this->getPhrase($parse[$key][1]);
		}
		$output = $this->replace($output, $parse);
		return $output;
	}
	
	function redirect($message, $refresh, $url)
	{
		$output = $this->suit->templates->getTemplate('redirect');
		if ($message != '')
		{
			$seconds = $this->getPhrase('seconds');
			if ($refresh != 0)
			{
				$s = 's';
			}
			else
			{
				$s = '';
			}
			$array = Array
			(
				array('<seconds>', $refresh),
				array('<s>', $s)
			);
			$seconds = $this->replace($seconds, $array);
			$output = str_replace('<message>', $message, $output);
			$array = Array
			(
				array('<message>', $message),
				array('<seconds>', $seconds),
				array('<url>', htmlentities($url))
			);
			$output = $this->replace($output, $array);
		}
		else
		{
			$output = '';
		}
		print $output;
		header('refresh: ' . $refresh . '; url=' . $url);
		exit;
	}
	
	function parseTemplates($output)
	{
		$parse = array();
		//Match {expression_here} as templates
		preg_match_all('/\{(.*?)\}/', $output, $parse, PREG_SET_ORDER);		
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
	Creates List
	**@param string Table
	**@param string Order By
	**@param string Order By Type
	**@returns string List
	**/
	function createList($query, $orderby, $orderby_type, $range, $search, $match, $url, $get, $default, $phrases, $errorlog, $start, $limit, $select, $search, $move)
	{
		if ($orderby_type == 'asc')
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
		$check = $this->suit->db->query($query);
		if ($check && (mysql_num_rows($check)))
		{
			$count = mysql_num_rows($check);
		}
		else
		{
			$count = 0;
		}
		if ($search)
		{
			$query .= ' AND MATCH (' . $match . ') AGAINST (\'' . $search . '\')';
		}
		$query .= ' ORDER BY ' . $orderby . ' ' . $orderby_type;
		$pagelink = $this->suit->templates->getTemplate('pagelink');
		$array = array
		(
			array('<limit>', $limit),
			array('<search>', $search),
			array('<orderby>', $currentorderby),
			array('<url>', $url),
		);
		$pagelink = $this->replace($pagelink, $array);
		$link_4 = $pagelink;
		$pagelink = str_replace('<class>', 'link', $pagelink);
		$array = array
		(
			array('<class>', 'current'),
			array('<display>', ($start / $limit) + 1),
			array('<start>', $start)
		);
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
		$link_4 = $this->replace($link_4, $array);
		$link_1 = $this->pageLink($query, ($start - ($limit * 3)), 0, $this->getPhrase('first'), 0, $pagelink);
		$link_2 = $this->pageLink($query, ($start - ($limit * 2)), -1, (($start / $limit) - 1), 0, $pagelink);
		$link_3 = $this->pageLink($query, ($start - $limit), -1, ($start / $limit), 0, $pagelink);
		$link_5 = $this->pageLink($query, ($start + $limit), -1, (($start / $limit) + 2), 1, $pagelink);
		$link_6 = $this->pageLink($query, ($start + ($limit * 2)), -1, (($start / $limit) + 3), 1, $pagelink);
		$link_7 = $this->pageLink($query, ($start + ($limit * 3)), strval($num), $this->getPhrase('last'), 1, $pagelink);
		$query .=  ' LIMIT ' . $range;
		if (!$errorlog)
		{
			$return = $this->suit->templates->getTemplate('list');
			$entry = $this->suit->templates->getTemplate('list_entry');
			if ($phrases)
			{
				$replace = $this->suit->templates->getTemplate('list_phrases');
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
		$check = $this->suit->db->query($query);
		if ($check && (mysql_num_rows($check)))
		{
			while ($row = mysql_fetch_assoc($check))
			{
				if (!$errorlog)
				{
					if ($default)
					{
						if ($row['defaults'])
						{
							$title = $this->suit->templates->getTemplate('list_default');
							$array = Array
							(
								array('<title>', htmlspecialchars($row[$orderby])),
								array('<get>', $get)
							);
							$title = $this->replace($title, $array);
						}
						else
						{
							$title = $this->suit->templates->getTemplate('list_notdefault');
							$array = Array
							(
								array('<url>', $url),
								array('<title>', htmlspecialchars($row[$orderby])),
								array('<id>', $row['id']),
								array('<get>', $get),
								array('<start>', $start),
								array('<limitval>', $limit),
								array('<currentsearch>', $search),
								array('<currentorderby>', $currentorderby)
							);
							$title = $this->replace($title, $array);
						}
					}
					else
					{
						$title = htmlspecialchars($row[$orderby]);
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
						array('<currentsearch>', $search),
						array('<currentorderby>', $currentorderby)
					);
					$list .= $this->replace($entry, $array);
				}
				else
				{
					$array = array
					(
						array('<error>', $row['content']),
						array('<time>', date('m/d/y H:i:s', $row['time'])),
						array('<location>', $row['location'])
					);
					$list .= $this->replace($entry, $array);
				}
			}
		}
		else
		{
			$list = '';
		}
		$searchform = $this->suit->templates->getTemplate('list_search');
		$searchform = str_replace('<currentsearch>', $search, $searchform);
		if ($move)
		{
			$languages = $this->suit->templates->getTemplate('language_move');
			$languages = str_replace('<languages>', $this->languageForm('', 0), $languages);
		}
		else
		{
			$languages = '';
		}
		$array = array
		(
			array('<search>', $searchform),
			array('<list>', $list),
			array('<url>', $url),
			array('<get>', $get),
			array('<count>', $count),
			array('<start>', $start),
			array('<limitval>', $limit),
			array('<currentsearch>', $search),
			array('<currentorderby>', $currentorderby),
			array('<languages>', $languages),
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
	function pageLink($query, $check, $start, $display, $db, $link)
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
			$query .=  ' LIMIT ' . $check . ', 1';
			$check2 = $this->suit->db->query($query);
			if ($check2 && (mysql_num_rows($check2)))
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
		if (!is_array($id))
		{
			$id = array($id);
		}
		$id = implode(', ', $id);
		$query = 'SELECT * FROM ' . $table . ' WHERE id IN(' . $id . ')';
		$check = $this->suit->db->query($query);
		if ($check && (mysql_num_rows($check)))
		{
			$rows = array();
			while ($row = mysql_fetch_assoc($check))
			{
				$rows[$row['id']] = $row[$display];
			}
			$return = $this->suit->templates->getTemplate('delete');
			$message = $this->getPhrase('deleteconfirm');
			$message = str_replace('<name>', implode(', ', $rows), $message);
			$ids = '';
			$inputarray = $this->suit->templates->getTemplate('inputarray');
			foreach ($rows as $key => $value)
			{
				$ids .= str_replace('<id>', $key, $inputarray);
			}
			$array = array
			(
				array('<message>', $message),
				array('<id>', $ids),
				array('<name>', $name)
			);
			$return = $this->replace($return, $array);
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
		$id = implode(', ', $id);
		$query = 'SELECT * FROM ' . $table . ' WHERE id IN(' . $id . ')';
		$check = $this->suit->db->query($query);
		if ($check && (mysql_num_rows($check)))
		{
			$query = 'DELETE FROM ' . $table . ' WHERE id IN(' . $id . ')';
			$this->suit->db->query($query);
		}
		else
		{
			exit;
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
			'where' => array
			(
				array('in', 'id', array($id))
			)
		);
		$check = $this->suit->db->select($table, $options);
		if ($check)
		{
			while ($row = mysql_fetch_assoc($check))
			{
				$options = array
				(
					'set' => array
					(
						array('defaults', '0')
					)
				);
				$this->suit->db->update($table, $options);
				$options = array
				(
					'where' => array
					(
						array('in', 'id', array($id))
					),
					'set' => array
					(
						array('defaults', '1')
					)
				);
				$this->suit->db->update($table, $options);
			}
		}
		else
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
	Edit Handling
	**@param string Where
	**@param string Where 2
	**@param string Table
	**@param string Title
	**@param variable Old Title
	**@returns string Error
	**/	
	function editSubmit($query, $query2, $table, $title, &$oldtitle, $column)
	{
		$check = $this->suit->db->query($query);
		if ($check && (mysql_num_rows($check)))
		{
			while ($row = mysql_fetch_assoc($check))
			{
				$oldtitle = $row[$column];
				$check2 = $this->suit->db->query($query2);
				if (!($check2 && (mysql_num_rows($check2))) || ($title == $oldtitle))
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
	function addSubmit($query, $title)
	{
		$check = $this->suit->db->query($query);
		if (!($check && (mysql_num_rows($check))))
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
			elseif ($domainLen < 1 || $domainLen > 255)
			{
				$return = false;
			}
			//The local part must not start or end with a dot (.) character.
			elseif ($tie[0] == '.' || $tie[$tieLen-1] == '.')
			{
				$return = false;
			}
			//It must also not have two consecutive dots.
			elseif (preg_match('/\\.\\./', $tie))
			{
				$return = false;
			}
			//We cannot allow any invalid characters in the domain name.
			elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
			{
				$return = false;
			}
			//It must also not have two consecutive dots.
			elseif (preg_match('/\\.\\./', $domain))
			{
				$return = false;
			}
			elseif (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace('\\\\', '', $tie)))
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
		return $start . ', ' . $limit;
	}
	
	/**
	Language Form
	**@param int Language Id
	**@returns string Form
	**/	
	function languageForm($id, $default)
	{
		$return = $this->suit->templates->getTemplate('language');
		$entry = '';
		$form = $this->suit->templates->getTemplate('language_entry');
		foreach ($this->languages as $key => $value)
		{
			if (intval($id) == $key)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
			$array = array
			(
				array('<id>', $key),
				array('<title>', htmlentities($value[0])),
				array('<selected>', $selected)
			);
			$entry .= $this->replace($form, $array);
		}
		if ($default)
		{
			$defaults = $this->suit->templates->getTemplate('language_default');
		}
		else
		{
			$defaults = '';
		}
		$array = array
		(
			array('<languages>', $entry),
			array('<default>', $defaults)
		);
		$return = $this->replace($return, $array);
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
if (get_magic_quotes_gpc())
{
	$in = array(&$_GET, &$_POST, &$_COOKIE);
	while (list($k,$v) = each($in))
	{
		foreach ($v as $key => $value)
		{
			if (!is_array($value))
			{
				//Detect magic_quotes_sybase
				if (ini_get('magic_quotes_sybase'))
				{
					//Yes, so we replace
					$in[$k][$key] = str_replace('\'\'', '\'', $value);
				}
				else
				{
					//No, so we strip the slashes
					$in[$k][$key] = stripslashes($value);
				}
				continue;
			}
			$in[] =& $in[$k][$key];
		}
	}
	unset($in);
}
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
$this->suit->templates->getTemplate('phrases');
?>