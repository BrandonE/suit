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
	public $version = '1.0.5';
	
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
	
	public function adminArea($path, $redirect, $post, $type)
	{
		$error = '';
		if ($type == 'code')
		{
			$filetype = 'inc.php';
		}
		elseif ($type == 'glue')
		{
			$filetype = 'txt';
		}
		else
		{
			$filetype = 'tpl';
		}
		if ($type != 'code')
		{
			if (isset($_POST['import']) && isset($_FILES['file']))
			{
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
								$filepath = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
								if (!file_exists($filepath) || (isset($_POST['overwrite']) && $_POST['overwrite'] && is_writable($filepath)))
								{
									$error = $this->checkWritable($title, $type, $filetype);
									if (!$error)
									{
										if ($type == 'glue')
										{
											$content = $this->fileFormat($row->content) . '=' . $this->fileFormat($row->code);
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
					$this->navigation->redirect($this->language['importedsuccessfully'], $this->config['redirect']['interval'], $redirect);
				}
				else
				{
					$error = '<center><p>' . $error . '</p></center>';
				}
			}
			elseif ((isset($_GET['cmd']) && ($_GET['cmd'] == 'export')) || (isset($_POST['exportselected']) && is_array($_POST['entry'])))
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
					$filepath = $this->owner->templates . '/' . $type . '/' . $value . '.' . $filetype;
					if (file_exists($filepath))
					{
						if ($type == 'glue')
						{
							$array = explode('=', file_get_contents($filepath), 2);
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
			elseif (isset($_POST['deleteselected']) && isset($_POST['entry']) && is_array($_POST['entry']))
			{
				$get = implode('&file[]=', $_POST['entry']); //Implode the array into comma separated values, for explosion later in the $_GET
				$this->navigation->redirect('', 0, $path . 'start=' . $this->navigation->settings['start'] . '&limit=' . $this->navigation->settings['limit'] . '&orderby=' . $this->navigation->settings['orderby_type'] . '&search=' . $this->navigation->settings['search'] . '&cmd=delete&file[]=' . $get);
			}
			$posted = array(); //Create an empty array for the data.
			$status = 0; //This controls if there are any errors.
			foreach ($post as $value)
			{
				if (isset($_POST[$value]))
				{
					//Apply the value of the post data to the $posted array.
					$posted[$value] = $_POST[$value];
				}
				else
				{
					$posted[$value] = '';
					$status = (!$status && ($value == 'id')) ? 1 : 2;
				}
			}
		}
		$pages = array('add', 'edit', 'delete'); //Valid pages that can be requested.
		if (isset($_GET['cmd']) && (in_array($_GET['cmd'], $pages)) && $type != 'code')
		{
			if (isset($_POST['add']) && $status != 2)
			{
				$title = $this->fileFormat($posted['title']);
				$filepath = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
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
					$error = $this->checkWritable($title, $type, $filetype);
					if (!$error)
					{
						if ($type == 'content')
						{
							$char = $this->breakConvert($posted['content'], PHP_OS);
							$posted['content'] = preg_replace('/(\\r\\n)|\\r|\\n/', $char, $posted['content']);
						}
						else
						{
							$posted['content'] = $this->fileFormat($posted['content']) . '=' . $this->fileFormat($posted['code']);
						}
						file_put_contents($filepath, $posted['content']);
						$this->navigation->redirect($this->language['addedsuccessfully'], $this->config['redirect']['interval'], $redirect);
					}
				}
			}
			elseif (isset($_POST['edit']) && !strcmp($status, 0))
			{
				$title = $this->fileFormat($posted['title']);
				$filepath = $this->owner->templates . '/' . $type . '/' . $posted['oldtitle'] . '.' . $filetype;
				$filepath2 = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
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
					$error = $this->checkWritable($title, $type, $filetype);
					if (!$error)
					{
						if (is_writable($filepath2))
						{
							if ($type == 'glue')
							{
								$posted['content'] = $this->fileFormat($posted['content']) . '=' . $this->fileFormat($posted['code']);
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
				}
			}
			elseif (isset($_POST['delete']) && isset($posted['title']))
			{
				$return = '';
				$error = '';
				foreach ($posted['title'] as $value)
				{
					$filepath = $this->owner->templates . '/' . $type . '/' . $value . '.' . $filetype;
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
						$filepath = $this->owner->templates . '/' . $type . '/' . $value . '.' . $filetype;
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
			elseif ($type == 'content' && (isset($_POST['escape']) && !strcmp($status, 0)))
			{
				//Escaping replaces symbols contained in this array with a more parser-friendly format.
				$array = array
				(
					array('{', '{openingbrace}'),
					array('}', '{closingbrace}'),
					array('[', '{openingbracket}'),
					array(']', '{closingbracket}'),
					array('(', '{openingparenthesis}'),
					array(')', '{closingparenthesis}')
				);
				$posted['content'] = $this->replace($posted['content'], $array);
				$error = $this->language['escaped'];
			}
			if ($error)
			{
				$error = '<p>' . $error . '</p>';
			}
			if (!strcmp($_GET['cmd'], 'add'))
			{
				$return = $this->owner->getTemplate('admin_form_' . $type);
				if (!$error)
				{
					//Template Cloning
					if (isset($_GET['file']) && ($_GET['file']))
					{
						$posted['title'] = $_GET['file'];
						$filepath = $this->owner->templates . '/' . $type . '/' . $_GET['file'] . '.' . $filetype;
						if (file_exists($filepath))
						{
							if ($type == 'glue')
							{
								$array = explode('=', file_get_contents($filepath), 2);
								if (isset($array[0]))
								{
									$posted['content'] = $array[0];
								}
								if (isset($array[1]))
								{
									$posted['code'] = $array[1];
								}
							}
							else
							{
								$posted['content'] = file_get_contents($filepath);
							}
						}
					}
				}
				if (!isset($posted['code']))
				{
					$posted['code'] = '';
				}
				$array = array
				(
					array('<code>', htmlentities($posted['code'])),
					array('<content>', htmlentities($this->firstLine($posted['content']))),
					array('<error>', $error),
					array('<name>', 'add'),
					array('<title>', htmlentities($posted['title'])),
					array('<value>', $this->language['add'])
				);
				$return = $this->replace($return, $array);
				$section = $this->language['add'];
			}
			elseif (!strcmp($_GET['cmd'], 'edit'))
			{
				if (!isset($_GET['file']))
				{
					$this->owner->getTemplate('badrequest');
				}
				$filepath = $this->owner->templates . '/' . $type . '/' . $_GET['file'] . '.' . $filetype;
				if (!file_exists($filepath))
				{
					$this->owner->getTemplate('badrequest');
				}
				$return = $this->owner->getTemplate('admin_form_' . $type);
				if (!$error)
				{
					$posted['title'] = $_GET['file'];
					if ($type == 'glue')
					{
						$array = explode('=', file_get_contents($filepath), 2);
						if (isset($array[0]))
						{
							$posted['content'] = $array[0];
						}
						if (isset($array[1]))
						{
							$posted['code'] = $array[1];
						}
					}
					else
					{
						$posted['content'] = file_get_contents($filepath);
					}
				}
				if (!isset($posted['code']))
				{
					$posted['code'] = '';
				}
				$array = array
				(
					array('<code>', htmlentities($posted['code'])),
					array('<content>', htmlentities($this->firstLine($posted['content']))),
					array('<error>', $error),
					array('<oldtitle>', $_GET['file']),
					array('<name>', 'edit'),
					array('<title>', htmlentities($posted['title'])),
					array('<value>', $this->language['edit'])
				);
				$return = $this->replace($return, $array);
				$section = $this->language['edit'];
			}
			elseif (!strcmp($_GET['cmd'], 'delete'))
			{
				if (!isset($_GET['file']))
				{
					//You have to specify an ID.
					$this->owner->getTemplate('badrequest');
				}
				$id = $_GET['file'];
				//Check if the provided value is an array.
				if (!is_array($id))
				{
					//Turn it into a one-dimensional array with that single ID as an element.
					$id = array($id);
				}
				$rows = array();
				$ids = '';
				foreach ($id as $value)
				{
					$filepath = $this->owner->templates . '/' . $type . '/' . $value . '.' . $filetype;
					if (file_exists($filepath))
					{
						$rows[] = $value;
						$ids .= str_replace('<title>', $value, $this->owner->getTemplate('admin_delete_input'));
					}
				}
				if (empty($rows))
				{
					//No template(s) found.
					$this->owner->getTemplate('badrequest');
				}
				$error = str_replace('<name>', implode(', ', $rows), $error . '<p>' . $this->language['deleteconfirm'] . '</p>');
				$array = array
				(
					array('<error>', $error),
					array('<id>', $ids),
					array('<name>', 'delete')
				);
				$return = $this->replace($this->owner->getTemplate('admin_delete'), $array);
				$section = $this->language['delete'];
			}
		}
		elseif (isset($_GET['cmd']) && (!strcmp($_GET['cmd'], 'view')) && $type == 'code')
		{
			if (!isset($_GET['file']))
			{
				$this->owner->getTemplate('badrequest');
			}
			$filepath = $this->owner->templates . '/' . $type . '/' . $_GET['file'] . '.' . $filetype;
			if (!file_exists($filepath))
			{
				$this->owner->getTemplate('badrequest');
			}
			$return = $this->owner->getTemplate('admin_form_code');
			$array = array
			(
				array('<code>', htmlentities($this->firstLine(file_get_contents($filepath)))),
				array('<title>', htmlentities($_GET['file']))
			);
			$return = $this->replace($return, $array);
			$section = $this->language['edit'];
		}
		else
		{
			$return = ($type == 'code') ? $this->owner->getTemplate('admin_list_code') : $this->owner->getTemplate('admin_list');
			$entry = ($type == 'code') ? $this->owner->getTemplate('admin_list_entry_code') : $this->owner->getTemplate('admin_list_entry');
			//Create an empty variable for when we have to concatenate results to this.
			$entries = '';
			$templates = scandir($this->owner->templates . '/' . $type);
			foreach ($templates as $key => $value)
			{
				$preg = array('NULL');
				if ($this->navigation->settings['search'])
				{
					preg_match_all('/(.*?)' . $this->navigation->settings['search'] . '(.*?)/', basename($value, '.' . $filetype), $preg, PREG_SET_ORDER);
				}
				if ($value == '.' || $value == '..' || $value == (basename($value, '.' . $filetype)) || !$preg)
				{
					unset($templates[$key]);
				}
			}
			$count = count($templates);
			if ($this->navigation->settings['start'] && ($this->navigation->settings['start'] > $count-1))
			{
				$this->owner->getTemplate('badrequest');
			}
			if ($this->navigation->settings['orderby_type'] == 'asc')
			{
				asort($templates);
			}
			else
			{
				arsort($templates);
			}
			$iterations = 0;
			if (!empty($templates))
			{
				foreach ($templates as $value)
				{
					if ($iterations >= $this->navigation->settings['start'])
					{
						$title = htmlspecialchars(basename($value, '.' . $filetype));
						$checked = ($this->navigation->settings['select']) ? ' checked' : '';
						$array = array
						(
							array('<checked>', $checked),
							array('<limit>', $this->navigation->settings['limit']),
							array('<orderby>', $this->navigation->settings['orderby_type']),
							array('<path>', htmlentities($path)),
							array('<search>', $this->navigation->settings['search']),
							array('<start>', $this->navigation->settings['start']),
							array('<title>', $title)
						);
						$entries .= $this->replace($entry, $array);
					}
					$iterations++;
					if ($iterations == $this->navigation->settings['start'] + $this->navigation->settings['limit'])
					{
						break;
					}
				}
			}
			$link = $this->navigation->pagination($count);
			//Finalize replacements for the list.
			$array = array
			(
				array('<1>', $link[2]),
				array('<2>', $link[3]),
				array('<3>', $link[4]),
				array('<4>', $link[5]),
				array('<5>', $link[6]),
				array('<entries>', $entries),
				array('<count>', $count),
				array('<error>', $error),
				array('<First>', $link[1]),
				array('<Last>', $link[7]),
				array('<limit>', $this->navigation->settings['limit']),
				array('<orderby>', $this->navigation->settings['orderby_type']),
				array('<path>', htmlentities($path)),
				array('<search>', $this->navigation->settings['search']),
				array('<start>', $this->navigation->settings['start'])
			);
			$return = $this->replace($return, $array);
			$section = $this->language['page'] . ' ' . preg_replace('/\<[a](.*)\>(.*)\\<\/[a]\>/', '$2', $link[4]);
		}
		$return = array($return, $section);
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