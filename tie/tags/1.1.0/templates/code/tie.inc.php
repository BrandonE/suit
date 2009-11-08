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
	public $version = '1.1.0';
	
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
					//First things first, verify if this is a valid XML file.
					if ($_FILES['file']['type'] == 'text/xml')
					{
						$xml = file_get_contents($_FILES['file']['tmp_name']); //Grab contents of uploaded file from the temp directory.
						$xml = new SimpleXMLElement($xml); //We will be using SimpleXML.
						$xmlquery = $xml->xpath('/templates/template'); //Query for every child from 'templates' parent
						foreach ($xmlquery as $row)
						{
							$title = $this->fileFormat($row->title);
							$filepath = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
							if (isset($title) && isset($row->content) && !($type == 'glue' && !isset($row->code)))
							{
								if (file_exists($filepath) && isset($_POST['overwrite']) && !is_writable($filepath))
								{
									$error = $this->language['filenotchmod'];
								}
							}
							else
							{
								$error = $this->language['filenotvalid'];
								break;
							}
						}
						if (!$error)
						{
							foreach ($xmlquery as $row)
							{
								$title = $this->fileFormat($row->title);
								$filepath = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
								if (!(file_exists($filepath) && !isset($_POST['overwrite'])))
								{
									$error = $this->checkWritable($title, $type, $filetype);
									if (!$error)
									{
										$char = $this->breakConvert($row->content, PHP_OS);
										$content = ($type == 'glue') ? trim($this->fileFormat($row->content)) . '=' . trim($this->fileFormat($row->code)) : preg_replace('/(\\r\\n)|\\r|\\n/', $char, $row->content);
										file_put_contents($filepath, $content); //Write the contents to the file.
										if (isset($_POST['glue']) && $type == 'content')
										{
											$filepath = $this->owner->templates . '/glue/' . $title . '.txt';
											if (!(file_exists($filepath) && !isset($_POST['overwrite'])))
											{
												$error2 = $this->checkWritable($title, 'glue', 'txt');
												if (!$error2)
												{
													file_put_contents($filepath, $title . '=' . $title);
												}
											}
										}
									}
								}
							}
						}
					}
					else
					{
						$error = $this->language['filenotvalid'];
					}
				}
				else
				{
					$error = $this->language['simplexmlfail'];
				}
				if (!$error)
				{
					$redirectmessage = $this->language['importedsuccessfully'];
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
				$templates = array();
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
						
						$templates[] = array
						(
							array('<codetoken>', htmlentities($code)),
							array('<contenttoken>', htmlentities($content)),
							array('<titletoken>', htmlentities($value)),	
						);
					}
				}
				
				if (!$templates)
				{
					//Those templates do not exist.
					$this->owner->getTemplate('badrequest');
				}
				$xml = $this->parseLoop('templates', $templates, $this->owner->getTemplate('admin_xml'));
				$xml = $this->parseConditional('code', ($type == 'glue'), $xml);
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
				$title = trim($this->fileFormat($posted['title']));
				$filepath = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
				if (!file_exists($filepath))
				{
					if (!strcmp($title, ''))
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
						$char = $this->breakConvert($posted['content'], PHP_OS);
						$posted['content'] = ($type == 'glue') ? trim($this->fileFormat($posted['content'])) . '=' . trim($this->fileFormat($posted['code'])) : preg_replace('/(\\r\\n)|\\r|\\n/', $char, $posted['content']);
						file_put_contents($filepath, $posted['content']);
						if (isset($_POST['glue']) && $type == 'content')
						{
							$filepath = $this->owner->templates . '/glue/' . $title . '.txt';
							$error2 = $this->checkWritable($title, 'glue', 'txt');
							if (!$error2)
							{
								file_put_contents($filepath, $title . '=' . $title);
							}
						}
						$redirectmessage = $this->language['addedsuccessfully'];
					}
				}
			}
			elseif ((isset($_POST['edit']) || isset($_POST['editandcontinue'])) && ($status == 0))
			{
				$title = trim($this->fileFormat($posted['title']));
				$filepath = $this->owner->templates . '/' . $type . '/' . $posted['oldtitle'] . '.' . $filetype;
				$filepath2 = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
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
							if (isset($_POST['editandcontinue']))
							{
								$redirect .= '&cmd=edit&file=' . $posted['title'];
							}
							$char = $this->breakConvert($posted['content'], PHP_OS);
							$posted['content'] = ($type == 'glue') ? trim($this->fileFormat($posted['content'])) . '=' . trim($this->fileFormat($posted['code'])) : preg_replace('/(\\r\\n)|\\r|\\n/', $char, $posted['content']);
							file_put_contents($filepath2, $posted['content']);
							if ($title != $posted['oldtitle'])
							{
								unlink($filepath);
							}
							if (isset($_POST['glue']) && $type == 'content')
							{
								$templates = scandir($this->owner->templates . '/glue');
								foreach ($templates as $value)
								{
									if ($value != '.' && $value != '..' && $value != (basename($value, '.txt')))
									{
										$array = explode('=', file_get_contents($this->owner->templates . '/glue/' . $value), 2);
										if (isset($array[0]) && ($array[0] == $posted['oldtitle']))
										{
											$array[0] = $posted['title'];
											file_put_contents($this->owner->templates . '/glue/' . $value, implode('=', $array));
										}
									}
								}
							}
							$redirectmessage = $this->language['editedsuccessfully'];
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
						if (isset($_POST['glue']) && $type == 'content')
						{
							$templates = scandir($this->owner->templates . '/glue');
							foreach ($templates as $value2)
							{
								if ($value2 != '.' && $value2 != '..' && $value2 != (basename($value, '.txt')))
								{
									$array = explode('=', file_get_contents($this->owner->templates . '/glue/' . $value2), 2);
									if (isset($array[0]) && ($array[0] == $value))
									{
										$array[0] = '';
										file_put_contents($this->owner->templates . '/glue/' . $value2, implode('=', $array));
									}
								}
							}
						}
					}
					$redirectmessage = $this->language['deletedsuccessfully'];
				}
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
				$posted['content'] = $this->replace($array, $posted['content']);
				$error = $this->language['escaped'];
			}
			if (!isset($redirectmessage))
			{
				if (!strcmp($_GET['cmd'], 'add'))
				{
					$return = $this->owner->getTemplate('admin_form');
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
					$return = $this->replace($array, $return);
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
					$return = $this->owner->getTemplate('admin_form');
					$return = $this->parseConditional('editing', (!strcmp($_GET['cmd'], 'edit')), $return);
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
					$return = $this->replace($array, $return);
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
					$input = array();
					foreach ($id as $value)
					{
						$filepath = $this->owner->templates . '/' . $type . '/' . $value . '.' . $filetype;
						if (file_exists($filepath))
						{
							$input[] = array
							(
								array('<title>', htmlspecialchars($value))
							);
						}
					}
					if (empty($input))
					{
						//No template(s) found.
						$this->owner->getTemplate('badrequest');
					}
					$error = $this->parseLoop('names', $input, $error . '<p>' . $this->language['deleteconfirm'] . '</p>', ', ');
					$array = array
					(
						array('<error>', $error),
						array('<name>', 'delete')
					);
					$return = $this->replace($array, $this->owner->getTemplate('admin_delete'));
					$return = $this->parseLoop('input', $input, $return);
					$section = $this->language['delete'];
				}
				$return = $this->parseConditional('editing', (!strcmp($_GET['cmd'], 'edit')), $return);
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
			$return = $this->owner->getTemplate('admin_form');
			$array = array
			(
				array('<code>', htmlentities($this->firstLine(file_get_contents($filepath)))),
				array('<title>', htmlentities($_GET['file']))
			);
			$return = $this->replace($array, $return);
			$section = $this->language['view'];
		}
		else
		{
			$return = $this->owner->getTemplate('admin_list');
			//Create an empty variable for when we have to concatenate results to this.
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
			$link = $this->navigation->pagination($count);
			//Finalize replacements for the list.
			$array = array
			(
				array('<1>', $link[2]),
				array('<2>', $link[3]),
				array('<3>', $link[4]),
				array('<4>', $link[5]),
				array('<5>', $link[6]),
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
			$return = $this->replace($array, $return);
			$section = $this->language['page'] . ' ' . preg_replace('/\<[a](.*)\>(.*)\\<\/[a]\>/', '$2', $link[4]);
			natcasesort($templates);
			$templates = ($this->navigation->settings['orderby_type'] == 'desc') ? array_reverse($templates) : $templates;
			$iterations = 0;
			$entries = array();
			if (!empty($templates))
			{
				foreach ($templates as $value)
				{
					if ($iterations >= $this->navigation->settings['start'])
					{
						$title = basename($value, '.' . $filetype);
						$displaytitle = str_replace(htmlspecialchars($this->navigation->settings['search']), '<strong>' . $this->navigation->settings['search'] . '</strong>', htmlspecialchars($title));
						$entries[] = array
						(
							array('<displaytitle>', $displaytitle),
							array('<limit>', $this->navigation->settings['limit']),
							array('<orderby>', $this->navigation->settings['orderby_type']),
							array('<path>', htmlentities($path)),
							array('<search>', $this->navigation->settings['search']),
							array('<start>', $this->navigation->settings['start']),
							array('<title>', urlencode($title))
						);
					}
					$iterations++;
					if ($iterations == $this->navigation->settings['start'] + $this->navigation->settings['limit'])
					{
						break;
					}
				}
			}
			$return = $this->parseLoop('entries', $entries, $return);
			if (!empty($templates))
			{
				foreach ($templates as $value)
				{
					$return = $this->parseConditional(urlencode(basename($value, '.' . $filetype)), ($this->navigation->settings['select']), $return);
				}
			}
		}
		if (isset($redirectmessage))
		{
			$templates = scandir($this->owner->templates . '/' . $type);
			foreach ($templates as $key => $value)
			{
				if ($value == '.' || $value == '..' )
				{
					unset($templates[$key]);
				}
			}
			if (count($templates) <= $this->navigation->settings['start'])
			{
				$start = (count($templates) % $this->navigation->settings['limit'] == 0) ? $this->navigation->reduce(count($templates)-1) : $this->navigation->reduce(count($templates));
				$redirect = $path . 'start=' . $start . '&limit=' . $this->navigation->settings['limit'] . '&orderby=' . $this->navigation->settings['orderby_type'] . '&search=' . $this->navigation->settings['search'];
			}
			$this->navigation->redirect($redirectmessage, $this->config['redirect']['interval'], $redirect);
		}
		$return = $this->parseConditional('content', ($type == 'content'), $return);
		$return = $this->parseConditional('code', ($type == 'code'), $return);
		$return = $this->parseConditional('glue', ($type == 'glue'), $return);
		$return = $this->parseConditional('error', ($error), $return);
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
			$return = "\r\n";
		}
		elseif (stristr($source, 'LIN'))
		{
			$return = "\n";
		}
		elseif (stristr($source, 'MAC'))
		{
			$return = "\r";
		}
		else
		{
			//An unidentified OS, so we will resort to using Linux-style linebreaks.
			$return = "\n";
		}
		//Return the newly formatted string with the appropiate linebreaks for the OS.
		return $return;
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
			array(',', ''),
			array('<', ''),
			array('>', '')
		);
		$return = $this->replace($array, $return);
		$return = preg_replace('/(\\r\\n)|\\r|\\n/', '', $return);
		return $return;
	}
	
	public function firstLine($return)
	{
		if (strpos($return, "\n") == 1)
		{
			$return = "\n" . $return;
		}
		return $return;
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
	
	public function parseConditional($string, $bool, $return)
	{
		$string = preg_quote($string, '/');
		$string = str_replace(' ', '\s', $string);
		$string = str_replace('	', '\s', $string);
		$string = preg_replace('/(\\r\\n)|\\r|\\n/', '\s', $string);
		do
		{
			$array = array();
			$parse = array();
			preg_match_all('/\<if\s' . $string . '\>(.*?)\<\/if\s' . $string . '\>/msx', $return, $parse, PREG_SET_ORDER);
			foreach ($parse as $key => $value)
			{
				$replacement = ($bool) ? $parse[$key][1] : '';
				$array[] = array($parse[$key][0], $replacement);
			}
			preg_match_all('/\<else\s' . $string . '\>(.*?)\<\/else\s' . $string . '\>/msx', $return, $parse, PREG_SET_ORDER);
			foreach ($parse as $key => $value)
			{
				$replacement = ($bool) ? '' : $parse[$key][1];
				$array[] = array($parse[$key][0], $replacement);
			}
			$return = $this->replace($array, $return);
		}
		while(!empty($array));
		return $return;
	}
	
	public function parseLoop($string, $replace, $return, $implode = '')
	{
		$string = preg_quote($string, '/');
		$string = str_replace(' ', '\s', $string);
		$string = str_replace('	', '\s', $string);
		$string = preg_replace('/(\\r\\n)|\\r|\\n/', '\s', $string);
		do
		{
			$parse = array();
			$array = array();
			preg_match_all('/\<loop\s' . $string . '\>(.*?)\<\/loop\s' . $string . '\>/msx', $return, $parse, PREG_SET_ORDER);
			foreach ($parse as $key => $value)
			{
				$replacements = array();
				foreach ($replace as $value2)
				{
					$replacements[] = $this->replace($value2, $parse[$key][1]);
				}
				$array[] = array($parse[$key][0], implode($implode, $replacements));
			}
			$return = $this->replace($array, $return);
		}
		while(!empty($array));
		return $return;
	}

	public function parsePhrases($return)
	{
		do
		{
			$parse = array();
			//Match [expression_here] as phrases
			preg_match_all('/\[(.*?)\]/msx', $return, $parse, PREG_SET_ORDER);
			//Loop through the $parse array and run respective actions for them.
			foreach ($parse as $key => $value)
			{
				//Reference the $language array while iterating through the array, and then store the output of the phrases inside a 3-Dimensional array.
				$parse[$key][1] = $this->language[$parse[$key][1]];
			}
			$return = $this->replace($parse, $return);
		}
		while(!empty($array));
		return $return;
	}

	public function parseTemplates($return)
	{
		do
		{
			$parse = array();
			//Match {expression_here} as templates
			preg_match_all('/\{(.*?)\}/msx', $return, $parse, PREG_SET_ORDER);
			//Loop through the template parsing array and run respective actions for them.
			foreach ($parse as $key => $value)
			{
				//Run the getTemplate() function while iterating through the array, and then store the output of the templates inside a 3-Dimensional array.
				$parse[$key][1] = $this->owner->getTemplate($parse[$key][1]);
			}
			$return = $this->replace($parse, $return);
		}
		while(!empty($array));
		return $return;
	}
	
	public function parseVariables($return)
	{
		do
		{
			$parse = array();
			//Match {expression_here} as templates
			preg_match_all('/\((.*?)\)/msx', $return, $parse, PREG_SET_ORDER);
			//Loop through the template parsing array and run respective actions for them.
			foreach ($parse as $key => $value)
			{
				//Run the getTemplate() function while iterating through the array, and then store the output of the templates inside a 3-Dimensional array.
				$parse[$key][1] = $this->vars[$parse[$key][1]];
			}
			$return = $this->replace($parse, $return);
		}
		while(!empty($array));
		return $return;
	}

	/**
	Implodes values by concatenating from an array.
	@param string String
	@param array Placeholders and Replacements
	
	@returns string Imploded string
	**/
	public function replace($array, $return)
	{
		$pos = array();
		$add = 0;
		foreach ($array as $key => $value)
		{
			if ($return != str_replace($value[0], $value[1], $return))
			{
				if(stripos($return, $value[0], 0) == 0)
				{
					$pos[0] = $key;
				}
				$position = (stripos($return, $value[0], 0) == 0) ? 0 : -1;
				while($position = stripos($return, $value[0], $position+1)) 
				{
					$pos[$position] = $key;
				}
			}
		}
		ksort($pos);
		foreach ($pos as $key => $value)
		{
			$length = strlen($array[$value][0]);
			$return = substr_replace($return, $array[$value][1], $key+$add, $length);
			$add += strlen($array[$value][1]) - strlen($array[$value][0]);
		}
		return $return;
	}

	/**
	Set the language.
	**@returns int Language ID
	**/
	public function setLanguage()
	{
		$this->owner->getTemplate('languages');
		$returns = $this->vars['languages'];
		$return = -1;
		if (isset($_COOKIE[$this->config['cookie']['prefix'] . 'language']))
		{
			$return = $_COOKIE[$this->config['cookie']['prefix'] . 'language'];
			if (!(isset($returns[$return]) || $return == -1))
			{
				$return = -1;
				setcookie($this->config['cookie']['prefix'] . 'language', '', time() - $this->config['cookie']['length'], $this->config['cookie']['path'], $this->config['cookie']['domain']);
			}
		}
		if ($return != -1)
		{
			$this->owner->getTemplate($returns[$return][1]);
		}
		else
		{
			if (is_array($returns))
			{
				foreach ($returns as $value)
				{
					if ($value[2])
					{
						$this->owner->getTemplate($value[1]);
					}
				}
			}
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
//Attempt to disable register_globals().
ini_set('register_globals', 0);
//Turn off magic_quotes_runtime().
set_magic_quotes_runtime(0);
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
//If debug mode is on, then we will display all debug information if any errors/warnings/notices were to occur. Otherwise, show none.
if ($suit->tie->config['flag']['debug'])
{
	error_reporting(E_ALL);
}
else
{
	error_reporting(0);
}
?>