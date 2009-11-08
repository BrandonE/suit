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
	public $config = array();

	/**
	ID of he currently loaded language.
	**@var array
	**/
	public $languageid = array();

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
	Version
	**@var string
	**/
	public $version = '1.2.3';

	/**
	Constructor
	**@param object SUIT Reference
	**@param array Configuration Settings
	**@returns string Created Object
	**/
	public function __construct(&$owner, $config)
	{
		$this->owner = &$owner;
		$this->config = $config;
		if (isset($this->config['cookie']['domain']) && isset($this->config['cookie']['length']) && isset($this->config['cookie']['path']) && isset($this->config['cookie']['prefix']))
		{
			//Grab the template holding an array of available languages, and store it in $returns.
			$this->owner->getTemplate('languages');
			$this->languageid = -1; //By default, we'll return a negative value until we verify the language exists.
			if (isset($_COOKIE[$this->config['cookie']['prefix'] . 'language']))
			{
				//If the user already has a language in cookies, then we can just set the language from there.
				$this->languageid = $_COOKIE[$this->config['cookie']['prefix'] . 'language'];
				if (!(isset($this->owner->vars['languages'][$this->languageid]) || $this->languageid == -1))
				{
					//Language doesn't exist; remove the cookie and set the language to a negative integer.
					$this->languageid = -1;
					setcookie($this->config['cookie']['prefix'] . 'language', '', time() - $this->config['cookie']['length'], $this->config['cookie']['path'], $this->config['cookie']['domain']);
				}
			}
			if ($this->languageid != -1)
				//Grab the language file since it exists.
				$this->owner->getTemplate($this->languageids[$this->languageid][1]);
			else
				if (is_array($this->owner->vars['languages']))
					foreach ($this->owner->vars['languages'] as $value)
						//Use the first language, for our alternative.
						if ($value[2])
							$this->owner->getTemplate($value[1]);
			$this->language = $this->owner->vars['language'];
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
				foreach ($v as $key => $value)
				{
					if (!is_array($value))
					{
						//Undo magic_quotes_sybase effects.
						$in[$k][$key] = (ini_get('magic_quotes_sybase')) ?
							$in[$k][$key] = str_replace('\'\'', '\'', $value) :
							$in[$k][$key] = stripslashes($value);
						//Move on.
						continue;
					}
					$in[] =& $in[$k][$key];
				}
			//Clean-up.
			unset($in);
		}
		//If debug mode is on, then we will display all debug information if any errors/warnings/notices were to occur. Otherwise, show none.
		error_reporting(($this->config['flag']['debug']) ?
			E_ALL :
			false);
	}

	/**
	Displays the admin area
	**@param string Type of Admin Area
	**@param string Delete Template
	**@param string Form Template
	**@param string List Template
	**@param string XML Template
	**@param string Bad Request Template
	**@returns string Created Admin Area
	**/
	public function adminArea($type, $admin_delete = false, $admin_form = false, $admin_list = false, $admin_xml = false, $badrequest = false)
	{
		$type = strval($type);
		$admin_delete = ($admin_delete !== false) ?
			strval($admin_delete) :
			$this->config['templates']['admin_delete'];
		$admin_form = ($admin_form !== false) ?
			strval($admin_form) :
			$this->config['templates']['admin_form'];
		$admin_list = ($admin_list !== false) ?
			strval($admin_list) :
			$this->config['templates']['admin_list'];
		$admin_xml = ($admin_xml !== false) ?
			strval($admin_xml) :
			$this->config['templates']['admin_xml'];
		$badrequest = ($badrequest !== false) ?
			strval($badrequest) :
			$this->config['templates']['badrequest'];
		$error = '';
		//Exclude these from the querystring.
		$path = $this->navigation->path($_SERVER['SCRIPT_NAME'], array('boxes', 'cmd', 'file', 'limit', 'order', 'search', 'select', 'start'));
		//Run the checks for illegal conditions..
		$this->navigation->logistics();
		$redirect = (!empty($_POST)) ?
			$path . 'start=' . $this->navigation->settings['start'] . '&limit=' . $this->navigation->settings['limit'] . '&order=' . $this->navigation->settings['order'] . '&search=' . $this->navigation->settings['search'] :
			'';
		$post = ($type == 'glue') ?
			array('code', 'content', 'oldtitle', 'title') :
			array('content', 'oldtitle', 'title');
		//Depending on the section we are in, we will set the file extension neccesary.
		$filetype = ($type != 'code') ?
			(
				($type == 'glue') ?
					'txt' :
					'tpl'
			) :
			'inc.php';
		//Because line breaks throughout different OS' are inconsistent, we have to manually weed these out by rewriting them to be consistent with the OS being used.
		$char = (!stristr(PHP_OS, 'WIN')) ?
			(
				!stristr(PHP_OS, 'MAC') ?
					"\r" :
					"\n"
			) :
			"\r\n";
		//The code section is a special case, so we make sure we are not there.
		if ($type != 'code')
		{
			if (isset($_POST['import']) && isset($_FILES['file']))
			{
				//First things first, verify if this is a valid XML file.
				if ($_FILES['file']['type'] == 'text/xml')
				{
					$file = file_get_contents($_FILES['file']['tmp_name']); //Grab contents of the uploaded file.
					$results = $this->getSection('template', $file); //Now grab
					foreach ($results as $row)
					{
						//We have a resultset to work with, but now we need to grab the sections with the actual information.
						$title = $this->getSection('title', $row);
						$content = $this->getSection('content', $row);
						$code = $this->getSection('code', $row);
						//Check if the neccesary array keys are available to allow for import to continue.
						if (isset($title[0]) && isset($content[0]) && !($type == 'glue' && !isset($code)))
						{
							$title = str_replace('/', '', str_replace('\\', '', $title[0])); //Format the filename.
							$filepath = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
							//File cannot be overwritten, because it is not writable.
							$error = (file_exists($filepath) && isset($_POST['overwrite']) && !is_writable($filepath)) ?
								$this->language['filenotchmod']:
								$error;
						}
						else
						{
							//The file is not XML.
							$error = $this->language['filenotvalid'];
							break;
						}
					}
					if (!$error)
						//There are no errors, so it is safe to continue.
						//Now we will iterate through the resultset and begin adding templates.
						foreach ($results as $row)
						{
							//Grab the content from the sections now.
							$title = $this->getSection('title', $row);
							$content = $this->getSection('content', $row);
							$code = $this->getSection('code', $row);
							//Now we have to decode the encoded HTML entities to write them in properly readable format.
							$title = (!empty($title)) ?
								str_replace('/', '', str_replace('\\', '', html_entity_decode($title[0]))) :
								'';
							$content = (!empty($content)) ?
								str_replace('=', '\=', html_entity_decode($content[0])) :
								'';
							$filepath = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
							if (!(file_exists($filepath) && !isset($_POST['overwrite'])))
							{
								//The file is not set to overwrite, and it does not exist. We can just move on.
								$error = $this->checkWritable($title, $type, $filetype);
								if (!$error)
								{
									//It is writable, so we may continue.
									if ($type == 'glue')
										foreach ($code as $key => $value)
											$code[$key] = str_replace('=', '\=', html_entity_decode($value));
									$content = ($type == 'glue') ?
										$content .
										(
											(!empty($code)) ?
												'=' . implode('=', $code) :
												''
										) :
										preg_replace('/(\\r\\n)|\\r|\\n/', $char, $content);
									file_put_contents($filepath, $content);
									//We're checking if the glue checkbox is ticked.
									if (isset($_POST['glue']) && $type == 'content')
									{
										$filepath = $this->owner->templates . '/glue/' . $title . '.txt';
										if (!(file_exists($filepath) && !isset($_POST['overwrite'])))
										{
											//If we're not overwriting, the file(s) should not exist.
											$error2 = $this->checkWritable($title, 'glue', 'txt');
											//Write the contents formatted in glue-style if the file is writable.
											if (!$error2)
												file_put_contents($filepath, $title . '=' . $title);
										}
									}
								}
							}
						}
				}
				else
					//The file is not a valid XML file.
					$error = $this->language['filenotvalid'];
				//The import has completed.
				$redirectmessage = (!$error) ?
					$this->language['importedsuccessfully'] :
					'';
			}
			elseif ((isset($_GET['cmd']) && ($_GET['cmd'] == 'export')) || isset($_POST['exportselected']) && isset($_POST['entry']) && (is_array($_POST['entry'])))
			{
				$id = (isset($_GET['cmd']) && $_GET['cmd'] == 'export') ?
					$_GET['file'] :
					$_POST['entry'];
				//If the value isn't an array, turn it into a one-dimensional array with that single ID as an element.
				$id = (!is_array($id)) ?
					array($id) :
					$id;
				$templates = array(); //Create an empty array for XML output afterwards.
				$xml = $this->owner->getTemplate($admin_xml);
				$xml = $this->replace($this->parseConditional('if code', ($type == 'glue'), $xml, 'else code'), $xml); //If the type of file requested was a glue file, then add the code entry to it.
				foreach ($id as $value)
				{
					$filepath = $this->owner->templates . '/' . $type . '/' . str_replace('/', '', str_replace('\\', '', $value)) . '.' . $filetype;
					if (file_exists($filepath))
					{
						if ($type == 'glue')
						{
							//We want the glue, so we'll split by the = delimiter in the file.
							$array = explode('=', file_get_contents($filepath));
							$array = $this->owner->glueEscape($array);
							//We only need 2 fields, being the content and code filenames.
							$content = (isset($array[0])) ?
								$array[0] :
								'';
							unset($array[0]);
							$code = array();
							foreach ($array as $value2)
								$code[] = array
								(
									array('<codetoken>', htmlentities($value2))
								);
						}
						else
						{
							//It isn't the glue, so we just need the content area.
							$content = file_get_contents($filepath);
							$code = array();
						}
						$templates[] = array
						(
							array_merge
							(
								array
								(
									array('<contenttoken>', htmlentities($content)),
									array('<titletoken>', htmlentities($value))
								)
							),
							$this->parseLoop('loop code', $code, $xml)
						);
					}
				}
				if (!$templates)
					$this->owner->getTemplate($badrequest); //None of the selected templates exist.
				$xml = $this->replace($this->parseLoop('loop templates', $templates, $xml), $xml); //Loop through the templates array in order to create the proper formatting.
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Content-type: text/xml');
				header('Content-Disposition: attachment; filename=' . $type . '.xml');
				header('Content-Length: ' . strlen($xml));
				exit($xml); //Output the XML; it will prompt for a download, due to the headers supplied.
			}
			elseif (isset($_POST['deleteselected']) && isset($_POST['entry']) && is_array($_POST['entry']))
			{
				$get = implode('&file[]=', $_POST['entry']); //Implode the selected entries to mark for deletion.
				$this->navigation->redirect($path . 'start=' . $this->navigation->settings['start'] . '&limit=' . $this->navigation->settings['limit'] . '&order=' . $this->navigation->settings['order'] . '&search=' . $this->navigation->settings['search'] . '&cmd=delete&file[]=' . $get, '', 0);
			}
			$posted = array(); //Create an empty array for the data.
			$status = 0; //This controls if there are any errors.
			foreach ($post as $value)
				//Collect all field data that was specified on $post, if applicable.
				if (isset($_POST[$value]))
					//Apply the value of the post data to the $posted array.
					$posted[$value] = $_POST[$value];
				else
				{
					$posted[$value] = NULL;
					if ($value != 'code')
						$status = (!$status && ($value == 'id')) ?
							1 :
							2;
				}
		}
		if (isset($_GET['cmd']) && ((in_array($_GET['cmd'], array('add', 'delete', 'edit')) && $type != 'code') || ($_GET['cmd'] == 'view' && $type == 'code')))
		{
			if ((isset($_POST['add']) && $status != 2) || (isset($_POST['edit']) || isset($_POST['editandcontinue'])) && ($status == 0))
			{
				$title = str_replace('/', '', str_replace('\\', '', $posted['title'])); //Make sure the filename has no illegal characters.
				$posted['oldtitle'] = str_replace('/', '', str_replace('\\', '', $posted['oldtitle']));
				$filepath = $this->owner->templates . '/' . $type . '/' . $posted['oldtitle'] . '.' . $filetype;
				$filepath2 = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
				if (isset($_POST['add']))
					$error = (!file_exists($filepath2)) ? 
						(
							($title == '') ? 
								$this->language['missingtitle'] :
								$error
						) :
						$this->language['duplicatetitle'];
				else
					if (file_exists($filepath))
						$error = (!file_exists($filepath2) || $title == $posted['oldtitle']) ?
							(
								($title == '') ?
									$this->language['missingtitle'] :
									$error
							) :
							$this->language['duplicatetitle'];
					else
						//The original file should exist.
						$this->owner->getTemplate($badrequest);
				if (!$error)
				{
					$error = $this->checkWritable($title, $type, $filetype);
					if (!$error)
						if (is_writable($filepath2))
						{
							if ($type == 'glue')
							{
								if (!empty($posted['code']))
									foreach ($posted['code'] as $key => $value)
									{
										$count = 0;
										while (isset($value[strlen($value) - ($count + 1)]) && ($value[strlen($value) - ($count + 1)] == '\\'))
											$count++;
										$posted['code'][$key] = str_replace('=', '\=', $value) . str_repeat('\\', $count);
									}
								$count = 0;
								while (isset($posted['content'][strlen($posted['content']) - ($count + 1)]) && ($posted['content'][strlen($posted['content']) - ($count + 1)] == '\\'))
									$count++;
							}
							$posted['content'] = ($type == 'glue') ?
								str_replace('=', '\=', $posted['content']) . str_repeat('\\', $count) .
								(
									(!empty($posted['code'])) ?
										'=' . implode('=', $posted['code']) :
										''
								) :
								preg_replace('/(\\r\\n)|\\r|\\n/', $char, $posted['content']);
							file_put_contents($filepath2, $posted['content']);
							//Delete the old file if it was a rename.
							if ($title != $posted['oldtitle'] && !isset($_POST['add']))
								unlink($filepath);
							if (isset($_POST['glue']) && $type == 'content')
								if (isset($_POST['add']))
								{
									$filepath2 = $this->owner->templates . '/glue/' . $title . '.txt';
									$error2 = $this->checkWritable($title, 'glue', 'txt');
									//This is writable, so write to the glue formatted file.
									if (!$error2)
										file_put_contents($filepath2, $title . '=' . $title);
								}
								else
								{
									$templates = scandir($this->owner->templates . '/glue');
									if (is_array($templates))
										foreach ($templates as $value)
											if ($value != '.' && $value != '..' && $value != (basename($value, '.txt')) && !(is_array($value)))
											{
												//We can't be having any invalid files presented in the array.
												$array = explode('=', file_get_contents($this->owner->templates . '/glue/' . $value));
												$array = $this->owner->glueEscape($array);
												if (isset($array[0]) && ($array[0] == $posted['oldtitle']))
												{
													$array[0] = str_replace('=', '\=', $posted['title']);
													file_put_contents($this->owner->templates . '/glue/' . $value, implode('=', $array));
												}
											}
								}
							//Shall we continue editing?
							$redirect .= (isset($_POST['editandcontinue'])) ? 
								'&cmd=edit&file=' . $posted['title'] :
								'';
							$redirectmessage = (isset($_POST['add'])) ?
								$this->language['addedsuccessfully'] :
								$this->language['editedsuccessfully'];
						}
						else
							$error = $this->language['filenotchmod'];
				}
			}
			elseif (isset($_POST['delete']) && isset($posted['title']))
			{
				if (is_array($posted['title']))
					foreach ($posted['title'] as $value)
					{
						$filepath = $this->owner->templates . '/' . $type . '/' . str_replace('/', '', str_replace('\\', '', $value)) . '.' . $filetype;
						if (file_exists($filepath))
								//Looks like the file is not writable.
								$error = (!is_writable($filepath)) ?
									$this->language['filenotchmod'] :
									$error;
						else
							//That file does not exist.
							$this->owner->getTemplate($badrequest);
					}
				else
					$this->owner->getTemplate($badrequest);
				if (!$error)
				{
					foreach ($posted['title'] as $value)
					{
						//Iterate through the provided entries marked for deletion.
						$filepath = $this->owner->templates . '/' . $type . '/' . str_replace('/', '', str_replace('\\', '', $value)) . '.' . $filetype;
						unlink($filepath);
						if (isset($_POST['glue']) && $type == 'content')
						{
							$templates = scandir($this->owner->templates . '/glue');
							foreach ($templates as $value2)
								if ($value2 != '.' && $value2 != '..' && $value2 != (basename($value, '.txt')))
								{
									//We can't be having any invalid files in the array.
									$array = explode('=', file_get_contents($this->owner->templates . '/glue/' . $value2));
									$array = $this->owner->glueEscape($array);
									if (isset($array[0]) && ($array[0] == $value))
									{
										$array[0] = '';
										file_put_contents($this->owner->templates . '/glue/' . $value2, implode('=', $array));
									}
								}
						}
					}
					$redirectmessage = $this->language['deletedsuccessfully'];
				}
			}
			elseif (isset($_POST['boxes']) && ($_POST['boxes'] >= 0) && isset($_POST['boxes_submit']) && in_array($_GET['cmd'], array('add', 'edit')) && $type == 'glue')
				$error = $this->language['displayed'];
			if (!isset($redirectmessage))
			{
				$section = $this->language[$_GET['cmd']];
				//You need to specify a file.
				if (($_GET['cmd'] == 'edit' || $_GET['cmd'] == 'delete') && !isset($_GET['file']))
					$this->owner->getTemplate($badrequest);
				else
					$filepath = $this->owner->templates . '/' . $type . '/' . str_replace('/', '', str_replace('\\', '', $_GET['file'])) . '.' . $filetype;
				//Nonexistant file specified.
				if ($_GET['cmd'] != 'add' && !is_array($_GET['file']) && !file_exists($filepath))
					$this->owner->getTemplate($badrequest);
				if ($_GET['cmd'] == 'delete')
				{
					//If the value provided was not an array, turn it into a one-dimensional array with that single ID as an element to prevent any errors.
					$id = (!is_array($_GET['file'])) ?
						array($_GET['file']) :
						$_GET['file'];
					$input = array();
					foreach ($id as $value)
					{
						$filepath = $this->owner->templates . '/' . $type . '/' . str_replace('/', '', str_replace('\\', '', $value)) . '.' . $filetype;
						if (file_exists($filepath))
							//Considering that the template exists, add it to the input array for deletion mark.
							$input[] = array
							(
								array('<title>', htmlspecialchars($value))
							);
					}
					//No template(s) found.
					if (empty($input))
						$this->owner->getTemplate($badrequest);
				}
				$return = $this->owner->getTemplate(($_GET['cmd'] == 'delete') ?
					$admin_delete :
					$admin_form);
				$delimeter = $this->getSection('section delimeter', $return);
				$delimeter = (!empty($delimeter)) ?
					$delimeter[0] :
					'';
				$array = array
				(
					array_merge
					(
						$this->parseConditional('section delimeter', false, $return),
						$this->parseConditional('if content', ($type == 'content'), $return), //Whether or not to show the content box.
						$this->parseConditional('if code', ($type == 'code'), $return, 'else code'), //Are we going to use the code box?
						$this->parseConditional('if glue', ($type == 'glue'), $return), //Are we doing anything with glue?
						$this->parseConditional('if error', ($error), $return) //Are there any errors at all?
					),
					array_merge
					(
						$this->parseConditional('if glue', ($type == 'glue'), $return),
						$this->parseConditional('if content', ($type == 'content'), $return),
						$this->parseConditional('if error', ($error), $return),
						$this->parseConditional('if editing', ($_GET['cmd'] == 'edit'), $return)
					)
				);
				$return = $this->replace($array, $return);
				if ($_GET['cmd'] == 'delete')
				{
					$message = $this->replace($this->parseLoop('loop names', $input, $this->language['deleteconfirm'], $delimeter), $this->language['deleteconfirm']);
					$array = array_merge
					(
						$array = array
						(
							array('<error>', $error),
							array('<name>', 'delete'),
							array('<message>', $message)
						),
						$this->parseLoop('loop input', $input, $return)
					);
					$return = $this->replace($array, $return);
				}
				else
				{
					$posted['title'] = (!isset($posted['title'])) ?
						(
							(isset($_GET['file'])) ?
								$_GET['file'] :
								''
						) :
						$posted['title'];
					if ($type == 'glue')
					{
						$array = (isset($filepath) && file_exists($filepath)) ?
							explode('=', file_get_contents($filepath)) :
							array('', '');
						$array = $this->owner->glueEscape($array);
						$posted['content'] = (isset($array[0]) && !$posted['content']) ?
							$array[0] :
							$posted['content'];
						unset($array[0]);
						$posted['code'] = (!$posted['code']) ?
							$array :
							$posted['code'];
					}
					else
					{
						if (!isset($posted['content']))
							$posted['content'] = '';
						$posted['content'] = (isset($filepath) && file_exists($filepath) && !$posted['content']) ?
							file_get_contents($filepath) :
							$posted['content'];
					}
					$posted['code'] = (!isset($posted['code'])) ?
						array('') :
						$posted['code'];
					if ($type == 'glue')
					{
						$code = array();
						$boxes = (isset($_POST['boxes']) && (intval($_POST['boxes']) >= 0)) ?
							intval($_POST['boxes']) :
							count($posted['code']);
						$number = 1;
						foreach ($posted['code'] as $value)
						{
							if ($number > $boxes)
								break;
							$code[] = array
							(
								array
								(
									array('<code>', $value),
									array('<number>', $number)
								)
							);
							$number++;
						}
						for ($number; $number <= $boxes; $number++)
							$code[] = array
							(
								array('<code>', ''),
								array('<number>', $number)
							);
					}
					else
						$boxes = '0';
					$array = array_merge
					(
						array
						(
							array('<boxes>', $boxes),
							array('<content>', htmlentities($posted['content'])),
							array('<error>', $error),
							array('<oldtitle>', ((isset($_GET['file'])) ? $_GET['file'] : '')),
							array('<name>',  (($_GET['cmd'] == 'add') ? 'add' : 'edit')),
							array('<title>', htmlentities($posted['title'])),
							array('<value>', (($_GET['cmd'] == 'add') ? $this->language['add'] : $this->language['edit']))
						),
						((isset($code)) ? $this->parseLoop('loop code', $code, $return) : array())
					);
					$return = $this->replace($array, $return);
				}
			}
		}
		else
		{
			$return = $this->owner->getTemplate($admin_list);
			$array = array
			(
				$this->parseConditional('if code', ($type == 'code'), $return, 'else code'),
				array_merge
				(
					$this->parseConditional('if content', ($type == 'content'), $return), //Whether or not to show the content box.
					$this->parseConditional('if checked', ($this->navigation->settings['select']), $return), //Is this submission's checkbox ticked?
					$this->parseConditional('if glue', ($type == 'glue'), $return), //Are we doing anything with the glue?
					$this->parseConditional('if error', ($error), $return) //Are there any errors at all?
				)
			);
			$return = $this->replace($array, $return);
			$templates = scandir($this->owner->templates . '/' . $type);
			if (is_array($templates))
				foreach ($templates as $key => $value)
				{
					$preg = array('');
					//File searching regular expression.
					if ($this->navigation->settings['search'])
						preg_match_all('/(.*?)' . $this->pregFormat($this->navigation->settings['search']) . '(.*?)/', basename($value, '.' . $filetype), $preg, PREG_SET_ORDER);
					//Keep these out of the array, as they are links to directories.
					if ($value == '.' || $value == '..' || $value == (basename($value, '.' . $filetype)) || !$preg)
						unset($templates[$key]);
				}
			if ($this->navigation->settings['start'] > (($count = count($templates))-1) && $this->navigation->settings['start'])
				$this->owner->getTemplate($badrequest);
			$link = $this->navigation->pagination($count);
			$page = $this->getSection('section page', $return);
			$page = (!empty($page)) ?
				$page[0] :
				'';
			$return = $this->replace($this->parseConditional('section page', false, $return), $return);
			$section = $this->language['page'] . $page . preg_replace('/\<[a](.*)\>(.*)\\<\/[a]\>/', '$2', $link['current']);
			natcasesort($templates); //Case insensitive sorting.
			//Sorting order for natcasesort is Ascending, so to simulate, we reverse the array. Otherwise, leave the variable as is.
			$templates = ($this->navigation->settings['order'] == 'desc') ?
				array_reverse($templates) :
				$templates;
			$iterations = 0;
			$entries = array();
			if (!empty($templates))
			{
				$highlightstart = $this->getSection('section highlightstart', $return);
				$highlightstart = (!empty($highlightstart)) ?
					$highlightstart[0] :
					'';
				$highlightend = $this->getSection('section highlightend', $return);
				$highlightend = (!empty($highlightend)) ?
					$highlightend[0] :
					'';
				$array = array_merge
				(
					$this->parseConditional('section highlightstart', false, $return),
					$this->parseConditional('section highlightend', false, $return)
				);
				$return = $this->replace($array, $return);
				foreach ($templates as $value)
				{
					if ($iterations >= $this->navigation->settings['start'])
					{
						$title = basename($value, '.' . $filetype); //Remove the file extension for proper listing.
						$displaytitle = str_replace(htmlspecialchars($this->navigation->settings['search']), $highlightstart . $this->navigation->settings['search'] . $highlightend, htmlspecialchars($title)); //Add bold text to the matching search results.
						$entries[] = array
						(
							array('<displaytitle>', $displaytitle),
							array('<limit>', $this->navigation->settings['limit']),
							array('<order>', $this->navigation->settings['order']),
							array('<path>', htmlentities($path)),
							array('<search>', $this->navigation->settings['search']),
							array('<start>', $this->navigation->settings['start']),
							array('<title>', urlencode($title))
						);
					}
					$iterations++;
					//This loop has gone as far as the starting and ending point, so end it.
					if ($iterations == $this->navigation->settings['start'] + $this->navigation->settings['limit'])
						break;
				}
			}
			$array = array_merge
			(
				array
				(
					array('<previous>', $link['previous']),
					array('<current>', $link['current']),
					array('<next>', $link['next']),
					array('<count>', $count),
					array('<error>', $error),
					array('<limit>', $this->navigation->settings['limit']),
					array('<order>', $this->navigation->settings['order']),
					array('<path>', htmlentities($path)),
					array('<search>', $this->navigation->settings['search']),
					array('<start>', $this->navigation->settings['start'])
				),
				$this->parseLoop('loop entries', $entries, $return)
			);
			$return = $this->replace($array, $return);
		}
		if (isset($redirectmessage))
		{
			$templates = scandir($this->owner->templates . '/' . $type);
			foreach ($templates as $key => $value)
				//Remove directories from listing.
				if ($value == '.' || $value == '..' )	
					unset($templates[$key]);
			if (count($templates) <= $this->navigation->settings['start'])
			{
				$start = $this->navigation->reduce(count($templates), true);
				$redirect = $path . 'start=' . $start . '&limit=' . $this->navigation->settings['limit'] . '&order=' . $this->navigation->settings['order'] . '&search=' . $this->navigation->settings['search'];
			}
			$this->navigation->redirect($redirect, $redirectmessage);
		}
		$return = array($return, $section);
		return $return;
	}

	/**
	Checks that there is a file, and attempts to create it if it does not.
	**@param string Template
	**@param string Templates Subdirectory
	**@param string File Type
	**@returns boolean False, unless the directory is unwritable in which it returns an error
	**/
	public function checkWritable($template, $directory, $filetype)
	{
		$return = false;
		$template = strval($template);
		$directory = strval($directory);
		$filetype = strval($filetype);
		if (!file_exists($this->owner->templates . '/' . $directory . '/' . $template . '.' . $filetype))
			//The file doesn't exist, so now we check that the directory itself is writable.
			if (is_writable($this->owner->templates . '/' . $directory))
			{
				//It is, so we will create the file and CHMOD it so it is writable by our script.
				@touch($this->owner->templates . '/' . $directory . '/' . $template . '.' . $filetype) or $return = $this->language['filecouldnotbecreated'];
				@chmod($this->owner->templates . '/' . $directory . '/' . $template . '.' . $filetype, 0666);
			}
			else
				//It is not, so return an error.
				$return = $this->language['directorynotchmod'];
		return $return;
	}

	/**
	Retrieve a Section
	**@param string Section Name
	**@param string Content
	**@param string Opening String
	**@param string Closing String
	**@param string Ending String
	**@param string Escaping String
	**@returns string Section
	**/
	public function getSection($string, &$content, $open = false, $close = false, $end = false, $escapestring = false)
	{
		$return = array();
		$string = strval($string);
		$content = strval($content);
		$open = ($open !== false) ?
			strval($open) :
			$this->config['parse']['sections']['open'];
		$close = ($close !== false) ?
			strval($close) :
			$this->config['parse']['sections']['close'];
		$end = ($end !== false) ?
			strval($end) :
			$this->config['parse']['sections']['end'];
		$escapestring = ($escapestring !== false) ?
			strval($escapestring) :
			$this->config['parse']['escape'];
		$parse = $this->parseMatch($open . $string . $close, $open . $end . $string . $close, $content, $escapestring);
		foreach ($parse as $value)
			$return[] = $value[1];
		return $return;
	}

	/**
	Parse
	**@param string Code to execute on cases
	**@param string Opening String
	**@param string Closing String
	**@param string Content
	**@param string Escaping String
	**@returns array Parsed String
	**/
	public function parse($eval, $open, $close, &$content, $escapestring = false)
	{
		$return = array();
		$eval = strval($eval);
		$open = strval($open);
		$close = strval($close);
		$content = strval($content);
		$escapestring = ($escapestring !== false) ?
			strval($escapestring) :
			$this->config['parse']['escape'];
		//Match [expression_here] as phrases
		$return = $this->parseMatch($open, $close, $content, $escapestring);
		//Loop through the $parse array and run respective actions for them.
		foreach ($return as $key => $value)
		{
			$case = $value[1];
			$return[$key][1] = eval('return' . $eval . ';');
		}
		return $return;
	}

	/**
	Parses conditional statements in templates
	**@param string True Name
	**@param boolean Condition to evaluate
	**@param string Content
	**@param string False Name
	**@param string Opening String
	**@param string Closing String
	**@param string Ending String
	**@param string Escaping String
	**@returns array Parsed Conditional(s)
	**/
	public function parseConditional($if, $bool, &$content, $else = false, $open = false, $close = false, $end = false, $escapestring = false)
	{
		$return = array();
		$if = strval($if);
		$bool = strval($bool);
		$content = strval($content);
		$open = ($open !== false) ?
			strval($open) :
			$this->config['parse']['sections']['open'];
		$close = ($close !== false) ?
			strval($close) :
			$this->config['parse']['sections']['close'];
		$end = ($end !== false) ?
			strval($end) :
			$this->config['parse']['sections']['end'];
		$escapestring = ($escapestring !== false) ?
			strval($escapestring) :
			$this->config['parse']['escape'];
		$parse = $this->parseMatch($open . $if . $close, $open . $end . $if . $close, $content, $escapestring);
		foreach ($parse as $value)
		{
			//Now we evaluate the conditional for if, making sure the expression is true.
			$replacement = ($bool) ?
				$value[1] :
				'';
			$return[] = array($value[0], $replacement);
		}
		if ($else !== false)
		{
			$parse = $this->parseMatch($open . $else . $close, $open . $end . $else . $close, $content, $escapestring);
			foreach ($parse as $value)
			{
				//Now we evaluate the conditional for else, making sure the expression is false.
				$replacement = ($bool) ?
					'' :
					$value[1];
				$return[] = array($value[0], $replacement);
			}
		}
		return $return;
	}

	/**
	Escapes Parsing Strings
	**@param integer Position of String to Escape
	**@param string String to Escape
	**@param string Content
	**@param string Escaping String
	**@returns array If it has been escaped and the replace array for the escape string.
	**/
	private function parseEscape($pos, $escape, $content, $escapestring)
	{
		$replace = array();
		$pos = intval($pos);
		$escape = strval($escape);
		$content = strval($content);
		$escapestring = strval($escapestring);
		$count = 0;
		while (abs($start = $pos - ($count + strlen($escapestring))) == $start && substr($content, $start, strlen($escapestring)) == $escapestring)
			$count += strlen($escapestring);
		$count = $count / strlen($escapestring);
		$originalcount = $count;
		$condition = $count % 2;
		if ($condition)
			$count++;
		if ($count)
			$replace = array(str_repeat($escapestring, $originalcount) . $escape, str_repeat($escapestring, $originalcount - ($count / 2)) . $escape);
		return array($condition, $replace);
	}

	/**
	Parses conditional statements in templates
	**@param string Conditional Name
	**@param array Replacements
	**@param string Content
	**@param string Delimeter
	**@param string Opening String
	**@param string Closing String
	**@param string Ending String
	**@param string Escaping String
	**@returns array Parsed Loop(s)
	**/
	public function parseLoop($string, $replace, &$content, $implode = '', $open = false, $close = false, $end = false, $escapestring = false)
	{
		$return = array();
		$string = strval($string);
		$content = strval($content);
		$implode = strval($implode);
		$open = ($open !== false) ?
			strval($open) :
			$this->config['parse']['sections']['open'];
		$close = ($close !== false) ?
			strval($close) :
			$this->config['parse']['sections']['close'];
		$end = ($end !== false) ?
			strval($end) :
			$this->config['parse']['sections']['end'];
		$escapestring = ($escapestring !== false) ?
			strval($escapestring) :
			$this->config['parse']['escape'];
		$parse = $this->parseMatch($open . $string . $close, $open . $end . $string . $close, $content, $escapestring);
		foreach ($parse as $value)
		{
			$replacements = array();
			if (is_array($replace))
				foreach ($replace as $value2)
					$replacements[] = $this->replace($value2, $value[1]);
			else
				$this->warning($this->language['invalidtypearray']);
			//Separate the loops by use of a certain key, in the case that it is needed.
			$return[] = array($value[0], implode($implode, $replacements));
		}
		return $return;
	}

	/**
	Matching
	**@param string Name
	**@param string Content
	**@param string Escaping String
	**@returns array Matches
	**/
	public function parseMatch($open, $close, &$content, $escapestring = false)
	{
		$return = array();
		$array = array();
		$open = strval($open);
		$close = strval($close);
		$content = strval($content);
		$escapestring = ($escapestring !== false) ?
			strval($escapestring) :
			$this->config['parse']['escape'];
		$pos = -1;
		while (($pos = (($this->config['flag']['insensitive']) ? stripos($content, $open, $pos + 1) : strpos($content, $open, $pos + 1))) !== false)
		{
			$call = array();
			$escape = array();
			$openpos = $pos;
			$closepos = $pos;
			$call[0] = true;
			while ($call[0] && (($openpos = (($this->config['flag']['insensitive']) ? stripos($content, $open, $openpos + 1) : strpos($content, $open, $openpos + 1))) !== false))
			{
				$call = $this->parseEscape($openpos, $open, $content, $escapestring);
				if ($call[1])
					$escape[] = $call[1];
			}
			$call[0] = true;
			while ($call[0] && (($closepos = (($this->config['flag']['insensitive']) ? stripos($content, $close, $closepos + 1) : strpos($content, $close, $closepos + 1))) !== false))
			{
				$call = $this->parseEscape($closepos, $close, $content, $escapestring);
				if ($call[1])
					$escape[] = $call[1];
			}
			$call = $this->parseEscape($pos, $open, $content, $escapestring);
				if ($call[1])
					$array[] = $call[1];
			if ((($closepos !== false && $openpos === false) || ($openpos > $closepos)) && !$call[0])
			{
				$match = substr($content, $pos + strlen($open), $closepos - ($pos + strlen($open)));
				$return[] = array($open . $match . $close, $this->replace($escape, $match));
			}
		}
		$content = $this->replace($array, $content);
		return $return;
	}

	/**
	Implodes values by concatenating from an array.
	**@param array Placeholders and Replacements
	**@param string String
	**@returns string Imploded string
	**/
	public function replace($array, $return)
	{
		if (is_array($array))
		{
			$return = strval($return);
			if (isset($array[0][0]) && !is_array($array[0][0]))
				$array = array($array);
			foreach ($array as $value)
			{
				$add = 0;
				$pos = array();
				$taken = array();
				usort($value, array('TIE', 'replaceSort'));
				foreach ($value as $key => $value2)
				{
					$unset = true;
					if (is_array($value2) && (array_key_exists(0, $value2) && array_key_exists(1, $value2)))
					{
						$position = -1;
						while (($position = (($this->config['flag']['insensitive']) ? stripos($return, strval($value2[0]), $position+1) : strpos($return, strval($value2[0]), $position+1))) !== false)
						{
							$pass = true;
							for ($x = 0; $x < strlen(strval($value2[0])); $x++)
								if (in_array($position + $x, $taken))
								{
									$pass = false;
									break;
								}
							if ($pass)
							{
								$pos[$position] = $key;
								for ($x = 0; $x < strlen(strval($value2[0])); $x++)
									$taken[] = $position + $x;
								$unset = false;
							}
						}
					}
					else
						$this->warning($this->language['invalidtypearray']);
					if ($unset)
						unset($value2[$key]);
				}
				ksort($pos);
				foreach ($pos as $key => $value2)
				{
					$length = strlen(strval($value[$value2][0]));
					$return = substr_replace($return, strval($value[$value2][1]), $key + $add, $length);
					$add += strlen(strval($value[$value2][1])) - strlen(strval($value[$value2][0]));
				}
			}
		}
		else
			$this->warning($this->language['invalidtypearray']);
		return $return;
	}

	/**
	Sorts Replace Tokens by Length Descending
	**@param array First Array
	**@param array Second Array
	**@returns bool Whether or not the First Array is smaller than the Second Array.
	**/
	private function replaceSort($a, $b)
	{
		$return = false;
		if (is_array($a) && is_array($b))
		{
			if (array_key_exists(0, $a) && array_key_exists(0, $b))
				$return = (strlen(strval($a[0])) < strlen(strval($b[0])));
		}
		else
			$this->warning($this->language['invalidtypearray']);
		return $return;
	}

	/**
	Display an Warning.
	**@param string Warning Message
	**@returns none
	**/
	private function warning($warning)
	{
		$warning = strval($warning);
		$backtrace = debug_backtrace();
		$array = array
		(
			array('<warning>', $warning),
			array('<file>', $backtrace[1]['file']),
			array('<line>', $backtrace[1]['line'])
		);
		echo $this->replace($array, $this->language['tiewarning']);
	}
}
$suit->getTemplate('config');
$suit->tie = new TIE($suit, $suit->vars['config']);
?>