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
	public $version = '1.0.4';
	
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

	function addFile($type, $posted, $redirect)
	{
		$title = $this->fileFormat($posted['title']);
		$filepath = ($type == 'glue') ? $this->owner->templates . '/glue/' . $title . '.txt' : $this->owner->templates . '/content/' . $title . '.tpl';
		$error = '';
		$return = '';
		if (!file_exists($filepath))
		{
			if (!strcmp(str_replace(' ', '', $title), ''))
			{
				//No title specified.
				$error = $this->language['missingtitle'];
			}
		}
		else
		{
			//Duplicate title
			$error = $this->language['duplicatetitle'];
		}
		
		if (!$error)
		{
			$error = ($type == 'glue') ? $this->checkWritable($title, 'glue', 'txt') : $this->checkWritable($title, 'content', 'tpl');
			if (!$error)
			{
				if ($type == 'content')
				{
					$char = $this->breakConvert($posted['content'], PHP_OS);
					$posted['content'] = preg_replace('/(\\r\\n)|\\r|\\n/', $char, $posted['content']);
				}
				else
				{
					$posted['content'] = $this->fileFormat($posted['content']) . "\n" . $this->fileFormat($posted['code']);
				}
				file_put_contents($filepath, $posted['content']);
				$this->navigation->redirect($this->language['addedsuccessfully'], $this->config['redirect']['interval'], $redirect);
			}
			else
			{
				$return = $error;
			}
		}
		else
		{
			$return = $error;
		}
		
		return $return;
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
	
	public function checkWritable($template, $folder, $filetype)
	{
		$return = false;
		if (!file_exists($this->owner->templates . '/' . $folder . '/' . $template . '.' . $filetype))
		{
			if (is_writable($this->owner->templates . '/' . $folder))
			{
				touch($this->owner->templates . '/' . $folder . '/' . $template . '.' . $filetype);
				chmod($this->owner->templates . '/' . $folder . '/' . $template . '.' . $filetype, 0666);
			}
			else
			{
				$return = $this->language['directorynotchmod'];
			}
		}
		return $return;
	}
	
	function deleteFile($type, $posted, $redirect)
	{
		$return = '';
		$error = '';
		foreach ($posted['title'] as $value)
		{
			$filepath = ($type == 'glue') ? $this->owner->templates . '/glue/' . $value . '.txt' : $this->owner->templates . '/content/' . $value . '.tpl';
			if (file_exists($filepath))
			{
				if (!is_writable($filepath))
				{
					$error = $this->language['filenotchmod'];
				}
			}
			else
			{
				$this->owner->getTemplate('badrequest');
			}
		}
		if (!$error)
		{
			foreach ($posted['title'] as $value)
			{
				$filepath = ($type == 'glue') ? $this->owner->templates . '/glue/' . $value . '.txt' : $this->owner->templates . '/content/' . $value . '.tpl';
				unlink($filepath);
			}
			$templates = ($type == 'glue') ? $this->owner->templates . '/glue' : $this->owner->templates . '/content';
			$templates = scandir($templates);
			if (count($templates) % $this->navigation->settings['limit'] == 0)
			{
				$redirect = $path . 'start=' . intval($this->navigation->settings['start'] - $this->navigation->settings['limit']) . '&limit=' . $this->navigation->settings['limit'] . '&orderby=' . $this->navigation->settings['orderby_type'] . '&search=' . $this->navigation->settings['search'];
			}
			$this->navigation->redirect($this->language['deletedsuccessfully'], $this->config['redirect']['interval'], $redirect);
		}
		else
		{
			$return = $error;
		}
		return $return;
	}
	
	function editFile($type, $posted, $redirect)
	{
		$title = $this->fileFormat($posted['title']);
		$filepath = ($type == 'glue') ? $this->owner->templates . '/glue/' . $posted['oldtitle'] . '.txt' : $this->owner->templates . '/content/' . $posted['oldtitle'] . '.tpl';
		$filepath2 = ($type == 'glue') ? $this->owner->templates . '/glue/' . $title . '.txt' : $this->owner->templates . '/content/' . $title . '.tpl';
		$return = '';
		$error = '';
		if (file_exists($filepath))
		{
			if (!file_exists($filepath2) || $title == $posted['oldtitle'])
			{
				if (!strcmp($title, ''))
				{
					$error = $this->language['missingtitle'];
				}
			}
			else
			{
				$error = $this->language['duplicatetitle'];
			}
		}
		else
		{
			$this->owner->getTemplate('badrequest');
		}
		if (!$error)
		{
			$error = ($type == 'glue') ? $this->checkWritable($title, 'glue', 'txt') : $this->checkWritable($title, 'content', 'tpl');
			if (!$error)
			{
				if (is_writable($filepath2))
				{
					
					if ($type == 'glue')
					{
						$posted['content'] = $this->fileFormat($posted['content']) . "\n" . $this->fileFormat($posted['code']);
					}
					else
					{
						$char = $this->breakConvert($posted['content'], PHP_OS);
						$posted['content'] = preg_replace('/(\\r\\n)|\\r|\\n/', $char, $posted['content']);
					}
					file_put_contents($filepath2, $posted['content']);
					if ($title != $posted['oldtitle'])
					{
						unlink($filepath);
					}
					$this->navigation->redirect($this->language['editedsuccessfully'], $this->config['redirect']['interval'], $redirect);	
				}
				else
				{
					$error = $this->language['filenotchmod'];
				}
			}
			else
			{
				$return = $error;
			}
		}
		else
		{
			$return = $error;
		}
		return $return;
	}
	
	public function fileFormat($return)
	{
		$array = array
		(
			array('*', ''),
			array('.', ''),
			array('"', ''),
			array('/', ''),
			array('\\', ''),
			array('[', ''),
			array(']', ''),
			array(':', ''),
			array(';', ''),
			array('|', ''),
			array('=', ''),
			array(',', '')
		);
		$return = $this->replace($return, $array);
		preg_replace('/(\\r\\n)|\\r|\\n/', '', $return);
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
	
	function xmlExporter($type)
	{
		$id = (isset($_GET['cmd']) && !strcmp($_GET['cmd'], 'export')) ? $_GET['file'] : $_POST['entry'];
		//Check if the provided value is an array.
		if (!is_array($id))
		{
			//Turn it into a one-dimensional array with that single ID as an element.
			$id = array($id);
		}
		$templates = '';
		$entry = ($type == 'glue') ? $this->owner->getTemplate('admin_xml_glue') : $this->owner->getTemplate('admin_xml_content');
		foreach ($id as $value)
		{
			$filepath = ($type == 'glue') ? $this->owner->templates . '/glue/' . $value . '.txt' : $this->owner->templates . '/content/' . $value . '.tpl';
			if (file_exists($filepath))
			{
				if ($type == 'glue')
				{
					$array = explode("\n", file_get_contents($filepath), 2);
					if (isset($array[0]))
					{
						$content = $array[0];
					}
					if (isset($array[1]))
					{
						$code = $array[1];
					}
				}
				else
				{
					$content = file_get_contents($filepath);
					$code = '';
				}
				
				$array = array
				(
					array('<codetoken>', htmlentities($code)),
					array('<contenttoken>', htmlentities($content)),
					array('<titletoken>', htmlentities($value)),	
				);
				$templates .= $this->replace($entry, $array);
			}
		}
		
		if (!$templates)
		{
			//Those templates do not exist.
			$this->owner->getTemplate('badrequest');
		}
		$xml = str_replace('<list>', $templates, $this->owner->getTemplate('admin_xml')); //Have the replacement done, for output afterwards.
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename=' . $type . '.xml');
		header('Content-Length: ' . strlen($xml));
		exit($xml); //Output the XML; it will prompt for a download, because of the headers supplied.
	}
	
	function xmlImporter($type, $redirect)
	{
		$error = '';
		//You can only use the importer if you have SimpleXML enabled on the server.
		if (class_exists('SimpleXMLElement'))
		{
			$error = $this->language['filenotvalid'];
			//First things first, verify if this is a valid XML file.
			if ($_FILES['file']['type'] == 'text/xml')
			{
				$xml = file_get_contents($_FILES['file']['tmp_name']); //Grab contents of uploaded file from the temp directory.
				$xml = new SimpleXMLElement($xml); //We will be using SimpleXML.
				$xmlquery = $xml->xpath('/templates/template'); //Query for every child from 'templates' parent
				foreach ($xmlquery as $row)
				{
					if (isset($row->title) && isset($row->content) && !($type == 'glue' && !isset($row->code)))
					{
						$title = $this->fileFormat($row->title);
						$filepath = ($type == 'glue') ? $this->owner->templates . '/glue/' . $title . '.txt' : $this->owner->templates . '/content/' . $title . '.tpl';
						if (!file_exists($filepath) || (isset($_POST['overwrite']) && $_POST['overwrite'] && is_writable($filepath)))
						{
							$error = ($type == 'glue') ? $this->checkWritable($title, 'glue', 'txt') : $this->checkWritable($title, 'content', 'tpl');
							if (!$error)
							{
								if ($type == 'glue')
								{
									$content = $this->fileFormat($row->content) . "\n" . $this->fileFormat($row->code);
								}
								else
								{
									//OS specific linebreak conversions to avoid linebreaking issues.
									$char = $this->breakConvert($row->content, PHP_OS);
									$content = preg_replace('/(\\r\\n)|\\r|\\n/', $char, $row->content);	
								}
								file_put_contents($filepath, $content); //Write the contents to the file.
							}
						}
						else
						{
							$error = $this->language['filenotchmod'];
						}
					}
				}
			}
		}
		else
		{
			$error = $this->language['simplexmlfail'];
		}
		if (!$error)
		{
			//The import is now complete.
			$return = '';
			$this->navigation->redirect($this->language['importedsuccessfully'], $this->config['redirect']['interval'], $redirect);
		}
		else
		{
			$return = '<center><p>' . $error . '</p></center>';
		}
		return $return;
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