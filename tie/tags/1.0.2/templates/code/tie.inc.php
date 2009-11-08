<?php
/**
**@This file is part of TIE.
**@TIE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@TIE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with TIE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class TIE
{
	/**
	Configuration Settings
	**@var array
	**/
	public $config;

	/**
	ID of he currently loaded language.
	**@var array
	**/
	public $languageid;
	
	/**
	The currently loaded language.
	**@var array
	**/
	public $language = array();

	/**
	Owner
	**@var object
	**/
	public $owner;
	
	/**
	Variables
	**@var array
	**/
	var $vars = array();
	
	/**
	ID of he currently loaded language.
	**@var string
	**/
	public $version = '1.0.2';
	
	/**
	Constructor
	**@param object SUIT Reference
	**@param array Configuration Settings
	**/
	public function __construct(&$owner, $config)
	{
		$this->owner = &$owner;
		$this->config = $config;
	}

	/**
	Convert Line Breaks
	**@param string code
	**@param string source
	**@returns string Converted Code
	**/	
	public function breakConvert($code, $source)
	{
		//Because line breaks throughout different OS' are inconsistent, we have to manually weed these out by rewriting them to be consistent with the OS being used.
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
			//An unidentified OS, so we will resort to using Linux-style linebreaks.
			$char = "\n";
		}
		//Return the newly formatted string with the appropiate linebreaks for the OS.
		return $char;
	}
	
	public function checkWritable($template)
	{
		$return = false;
		if (!file_exists($this->owner->templates . '/content/' . $template . '.tpl'))
		{
			if (is_writable($this->owner->templates . '/content'))
			{
				touch($this->owner->templates . '/content/' . $template . '.tpl');
				chmod($this->owner->templates . '/content/' . $template . '.tpl', 0666);
			}
			else
			{
				$return = $this->language['directorynotchmod'];
			}
		}
		return $return;
	}
	
	public function firstLine($string)
	{
		if (strpos($string, "\n") == 1)
		{
			$string = "\n" . $string;
		}
		return $string;
	}
	
	public function getExternalTemplate($template, $url)
	{
		if (strstr($url, '?'))
		{
			$char = '&';
		}
		else
		{
			$char = '?';
		}
		return file_get_contents($url . $char . 'template=' . $template);
	}
	
	public function giveTemplates($templates, $message = '', $blacklist = false)
	{
		if (isset($_GET['template']))
		{
			$success = false;
			if ($blacklist)
			{
				if (!in_array($_GET['template'], $templates))
				{
					$success = true;
				}
			}
			else
			{
				if (in_array($_GET['template'], $templates))
				{
					$success = true;
				}
			}
			if ($success)
			{
				$message = $this->owner->getTemplate($_GET['template']);
			}
		}
		echo $message;
		exit;
	}

	public function parsePhrases($output)
	{
		$parse = array();
		//Match [expression_here] as phrases
		preg_match_all('/\[(.*?)\]/msx', $output, $parse, PREG_SET_ORDER);
		//Loop through the $parse array and run respective actions for them.
		foreach ($parse as $key => $value)
		{
			//Reference the $language array while iterating through the array, and then store the output of the phrases inside a 3-Dimensional array.
			$parse[$key][1] = $this->language[$parse[$key][1]];
		}
		$output = $this->replace($output, $parse);
		return $output;
	}

	public function parseTemplates($output)
	{
		$parse = array();
		//Match {expression_here} as templates
		preg_match_all('/\{(.*?)\}/msx', $output, $parse, PREG_SET_ORDER);
		//Loop through the template parsing array and run respective actions for them.
		foreach ($parse as $key => $value)
		{
			//Run the getTemplate() function while iterating through the array, and then store the output of the templates inside a 3-Dimensional array.
			$parse[$key][1] = $this->owner->getTemplate($parse[$key][1]);
		}
		$output = $this->replace($output, $parse);
		return $output;
	}
	
	public function parseVariables($output)
	{
		$parse = array();
		//Match {expression_here} as templates
		preg_match_all('/\((.*?)\)/msx', $output, $parse, PREG_SET_ORDER);
		//Loop through the template parsing array and run respective actions for them.
		foreach ($parse as $key => $value)
		{
			//Run the getTemplate() function while iterating through the array, and then store the output of the templates inside a 3-Dimensional array.
			$parse[$key][1] = $this->vars[$parse[$key][1]];
		}
		$output = $this->replace($output, $parse);
		return $output;
	}

	/**
	Implodes values by concatenating from an array.
	@param string Placeholders
	@param array Values
	
	@returns string Imploded string
	**/
	public function replace($string, $array)
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
				}
				$position = (stripos($string, $value[0], 0) == 0) ? 0 : -1;
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
	Set the language.
	**@returns int Language ID
	**/
	public function setLanguage()
	{
		$this->owner->getTemplate('languages');
		$languages = $this->vars['languages'];
		$language = -1;
		if (isset($_COOKIE[$this->config['cookie']['prefix'] . 'language']))
		{
			$language = $_COOKIE[$this->config['cookie']['prefix'] . 'language'];
			if (!(isset($languages[$language]) || $language == -1))
			{
				$language = -1;
				setcookie($this->config['cookie']['prefix'] . 'language', '', time() - $this->config['cookie']['length'], $this->config['cookie']['path'], $this->config['cookie']['domain']);
			}
		}
		if ($language != -1)
		{
			$this->owner->getTemplate($languages[$language][1]);
		}
		else
		{
			if (is_array($languages))
			{
				foreach ($languages as $value)
				{
					if ($value[2])
					{
						$this->owner->getTemplate($value[1]);
					}
				}
			}
		}
		return $language;
	}
}
include $suit->templates . '/code/config.inc.php';
$suit->tie = new TIE($suit, $config);
$suit->getTemplate('navigation'); //Load the methods for the admin class.
if (isset($suit->tie->config['cookie']['domain']) && isset($suit->tie->config['cookie']['length']) && isset($suit->tie->config['cookie']['path']) && isset($suit->tie->config['cookie']['prefix']))
{
	$suit->tie->languageid = $suit->tie->setLanguage();
}
//Check for magic quotes.
if (get_magic_quotes_gpc())
{
	//Let's begin cleaning out what magic_quotes left on the strings.
	$in = array(&$_GET, &$_POST, &$_COOKIE); //Create an array storing a reference (for altering) these superglobals
	while (list($k, $v) = each($in))
	{
		foreach ($v as $key => $value)
		{
			if (!is_array($value))
			{
				$in[$k][$key] = (ini_get('magic_quotes_sybase')) ? $in[$k][$key] = str_replace('\'\'', '\'', $value) : $in[$k][$key] = stripslashes($value);
				//Move on.
				continue;
			}
			$in[] =& $in[$k][$key];
		}
	}
	//Clean-up.
	unset($in);
}
?>