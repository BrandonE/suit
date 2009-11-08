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
	ID of he currently loaded language.
	**@var string
	**/
	public $version = '1.1.8';

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
	Displays the admin area
	**@param string Base URL
	**@param string Redirection URL
	**@param array Fields to collect POST data from
	**@param string Section
	**/
	public function adminArea($type)
	{
		$error = '';
		//Exclude these from the querystring.
		$exclude = array('cmd', 'boxes', 'file', 'limit', 'orderby', 'search', 'select', 'start');
		$path = $this->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
		//Run the checks for illegal conditions..
		$this->navigation->logistics();
		$redirect = (!empty($_POST)) ? $path . 'start=' . $this->navigation->settings['start'] . '&limit=' . $this->navigation->settings['limit'] . '&orderby=' . $this->navigation->settings['orderby_type'] . '&search=' . $this->navigation->settings['search'] : '';
		$post = ($type != 'code') ? (($type == 'glue') ? array('code', 'content', 'oldtitle', 'title') : array('content', 'oldtitle', 'title')) : array();
		//Depending on the section we are in, we will set the file extension neccesary.
		$filetype = ($type != 'code') ? (($type == 'glue') ? 'txt' : 'tpl') : 'inc.php';
		//Because line breaks throughout different OS' are inconsistent, we have to manually weed these out by rewriting them to be consistent with the OS being used.
		$char = (!stristr(PHP_OS, 'WIN')) ? (!stristr(PHP_OS, 'MAC') ? "\r" : "\n") : "\r\n";
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
							$title = $this->fileFormat($title[0]); //Format the filename.
							$filepath = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
							if (file_exists($filepath) && isset($_POST['overwrite']) && !is_writable($filepath))
							{
								//File cannot be overwritten, because it is not writable.
								$error = $this->language['filenotchmod'];
							}
						}
						else
						{
							//The file is not XML.
							$error = $this->language['filenotvalid'];
							break;
						}
					}

					if (!$error)
					{
						//There are no errors, so it is safe to continue.
						//Now we will iterate through the resultset and begin adding templates.
						foreach ($results as $row)
						{
							//Grab the content from the sections now.
							$title = $this->getSection('title', $row);
							$content = $this->getSection('content', $row);
							$code = $this->getSection('code', $row);
							//Now we have to decode the encoded HTML entities to write them in properly readable format.
							$title = ($title) ? html_entity_decode($title[0]) : '';
							$content = ($content) ? html_entity_decode($content[0]) : '';
							$title = $this->fileFormat($title); //Make sure the title does not have illegal filename characters.
							$filepath = $this->owner->templates . '/' . $type . '/' . $title . '.' . $filetype;
							if (!(file_exists($filepath) && !isset($_POST['overwrite'])))
							{
								//The file is not set to overwrite, and it does not exist. We can just move on.
								$error = $this->checkWritable($title, $type, $filetype);
								if (!$error)
								{
									//It is writable, so we may continue.
									$content = ($type == 'glue') ? $this->fileFormat($content) . ((!empty($code)) ? '=' . implode('=', array_map(array('TIE', 'fileFormat'), array_map('html_entity_decode', $code))) : '') : preg_replace('/(\\r\\n)|\\r|\\n/', $char, $content);
									file_put_contents($filepath, $content);
									//We're checking if the glue checkbox is ticked.
									if (isset($_POST['glue']) && $type == 'content')
									{
										$filepath = $this->owner->templates . '/glue/' . $title . '.txt';
										if (!(file_exists($filepath) && !isset($_POST['overwrite'])))
										{
											//If we're not overwriting, the file(s) should not exist.
											$error2 = $this->checkWritable($title, 'glue', 'txt');
											if (!$error2)
											{
												//Write the contents formatted in glue-style, since the file is writable.
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
					//The file is not a valid XML file.
					$error = $this->language['filenotvalid'];
				}
				if (!$error)
				{
					//The import has completed.
					$redirectmessage = $this->language['importedsuccessfully'];
				}
			}
			elseif ((isset($_GET['cmd']) && ($_GET['cmd'] == 'export')) || isset($_POST['exportselected']) && isset($_POST['entry']) && (is_array($_POST['entry'])))
			{
				$id = (isset($_GET['cmd']) && !strcmp($_GET['cmd'], 'export')) ? $_GET['file'] : $_POST['entry'];
				//Check if the provided value is an array.
				if (!is_array($id))
				{
					//If the value isn't an array, turn it into a one-dimensional array with that single ID as an element.
					$id = array($id);
				}
				$templates = array(); //Create an empty array for XML output afterwards.
				$xml = $this->owner->getTemplate('admin_xml');
				$xml = $this->replace($this->parseConditional('if code', ($type == 'glue'), $xml, 'else code'), $xml); //If the type of file requested was a glue file, then add the code entry to it.
				foreach ($id as $value)
				{
					$filepath = $this->owner->templates . '/' . $type . '/' . $value . '.' . $filetype;
					if (file_exists($filepath))
					{
						if ($type == 'glue')
						{
							//We want the glue, so we'll split by the = delimiter in the file.
							$array = explode('=', file_get_contents($filepath));
							//We only need 2 fields, being the content and code filenames.
							$content = (isset($array[0])) ? $array[0] : '';
							unset($array[0]);
							$code = array();
							foreach ($array as $value2)
							{
								$code[] = array
								(
									array
									(
										array('<codetoken>', htmlentities($value2))
									)
								);
							}
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
				{
					//None of the selected templates exist.
					$this->owner->getTemplate('badrequest');
				}
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
				$this->navigation->redirect(0, $path . 'start=' . $this->navigation->settings['start'] . '&limit=' . $this->navigation->settings['limit'] . '&orderby=' . $this->navigation->settings['orderby_type'] . '&search=' . $this->navigation->settings['search'] . '&cmd=delete&file[]=' . $get, '');
			}
			$posted = array(); //Create an empty array for the data.
			$status = 0; //This controls if there are any errors.
			foreach ($post as $value)
			{
				//Collect all field data that was specified on $post, if applicable.
				if (isset($_POST[$value]))
				{
					//Apply the value of the post data to the $posted array.
					$posted[$value] = $_POST[$value];
				}
				else
				{
					$posted[$value] = NULL;
					if ($value != 'code')
					{
						$status = (!$status && ($value == 'id')) ? 1 : 2;
					}
				}
			}
		}
		$pages = array('add', 'edit', 'delete'); //Valid pages that may be requested.
		if (isset($_GET['cmd']) && (in_array($_GET['cmd'], $pages)) && $type != 'code')
		{
			if (isset($_POST['add']) && $status != 2)
			{
				$title = $this->fileFormat($posted['title']); //Make sure the filename has no illegal characters.
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
						$posted['content'] = ($type == 'glue') ? $this->fileFormat($posted['content']) . ((!empty($posted['code'])) ? '=' . implode('=', array_map(array('TIE', 'fileFormat'), $posted['code'])) : '') : preg_replace('/(\\r\\n)|\\r|\\n/', $char, $posted['content']);
						file_put_contents($filepath, $posted['content']);
						if (isset($_POST['glue']) && $type == 'content')
						{
							$filepath = $this->owner->templates . '/glue/' . $title . '.txt';
							$error2 = $this->checkWritable($title, 'glue', 'txt');
							if (!$error2)
							{
								//This is writable, so write to the glue formatted file.
								file_put_contents($filepath, $title . '=' . $title);
							}
						}
						$redirectmessage = $this->language['addedsuccessfully'];
					}
				}
			}
			elseif ((isset($_POST['edit']) || isset($_POST['editandcontinue'])) && ($status == 0))
			{
				$title = $this->fileFormat($posted['title']);
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
					//The original file should exist.
					$this->owner->getTemplate('badrequest');
				}
				if (!$error)
				{
					$error = $this->checkWritable($title, $type, $filetype);
					if (!$error)
					{
						if (is_writable($filepath2))
						{
							//Shall we continue editing?
							if (isset($_POST['editandcontinue']))
							{
								//Add this to the redirect querystring after all has been updated.
								$redirect .= '&cmd=edit&file=' . $posted['title'];
							}
							$posted['content'] = ($type == 'glue') ? $this->fileFormat($posted['content']) . ((!empty($posted['code'])) ? '=' . implode('=', array_map(array('TIE', 'fileFormat'), $posted['code'])) : '') : preg_replace('/(\\r\\n)|\\r|\\n/', $char, $posted['content']);
							file_put_contents($filepath2, $posted['content']);
							if ($title != $posted['oldtitle'])
							{
								//Delete the old file if it was a rename.
								unlink($filepath);
							}
							if (isset($_POST['glue']) && $type == 'content')
							{
								$templates = scandir($this->owner->templates . '/glue');
								if (is_array($templates))
								{
									foreach ($templates as $value)
									{
										if ($value != '.' && $value != '..' && $value != (basename($value, '.txt')) && !(is_array($value)))
										{
											//We can't be having any invalid files presented in the array.
											$array = explode('=', file_get_contents($this->owner->templates . '/glue/' . $value), 2);
											if (isset($array[0]) && ($array[0] == $posted['oldtitle']))
											{
												$array[0] = $posted['title'];
												file_put_contents($this->owner->templates . '/glue/' . $value, implode('=', $array));
											}
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
				if (is_array($posted['title']))
				{
					foreach ($posted['title'] as $value)
					{
						$filepath = $this->owner->templates . '/' . $type . '/' . $value . '.' . $filetype;
						if (file_exists($filepath))
						{
							if (!is_writable($filepath))
							{
								//Looks like the file is not writable.
								$error = $this->language['filenotchmod'];
							}
						}
						else
						{
							//That file does not exist.
							$this->owner->getTemplate('badrequest');
						}
					}
				}
				else
				{
					$this->owner->getTemplate('badrequest');
				}

				if (!$error)
				{
					foreach ($posted['title'] as $value)
					{
						//Iterate through the provided entries marked for deletion.
						$filepath = $this->owner->templates . '/' . $type . '/' . $value . '.' . $filetype;
						unlink($filepath);
						if (isset($_POST['glue']) && $type == 'content')
						{
							$templates = scandir($this->owner->templates . '/glue');
							foreach ($templates as $value2)
							{
								if ($value2 != '.' && $value2 != '..' && $value2 != (basename($value, '.txt')))
								{
									//We can't be having any invalid files in the array.
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
			elseif (isset($_POST['boxes']) && ($_POST['boxes'] >= 0) && isset($_POST['boxes_submit']) && in_array($_GET['cmd'], array('add', 'edit')) && $type == 'glue')
			{
				$error = $this->language['displayed'];
			}
			if (!isset($redirectmessage))
			{
				if (!strcmp($_GET['cmd'], 'add'))
				{
					$return = $this->owner->getTemplate('admin_form');
					$return = $this->replace($this->parseConditional('if code', ($type == 'code'), $return, 'else code'), $return); //Are we going to use the code box?
					$return = $this->replace($this->parseConditional('if editing', (!strcmp($_GET['cmd'], 'edit')), $return), $return); //Determine if we are editing in order for allow display of the 'Save and Continue' button.
					$array = array_merge
					(
						$this->parseConditional('if content', ($type == 'content'), $return), //Whether or not to show the content box.
						$this->parseConditional('if glue', ($type == 'glue'), $return), //Are we doing anything with the glue?
						$this->parseConditional('if error', ($error), $return) //Are there any errors at all?
					);
					$return = $this->replace($array, $return);
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
									//Separator for glue files are the equal signs, so we split them.
									$array = explode('=', file_get_contents($filepath));
									unset($array[0]);
									$posted['code']  = $array;
								}
								else
								{
									//Of course because this isn't a glue file, just write the content.
									$posted['content'] = file_get_contents($filepath);
								}
							}
						}
					}
					$posted['code'] = (!isset($posted['code'])) ? array('') : $posted['code'];
					$code = array();
					$boxes = (isset($_POST['boxes']) && (intval($_POST['boxes']) >= 0)) ? intval($_POST['boxes']) : count($posted['code']);
					$number = 1;
					foreach ($posted['code'] as $value)
					{
						if ($number > $boxes)
						{
							break;
						}
						$code[] = array
						(
							array
							(
								array('<code>', htmlentities($value)),
								array('<number>', $number)
							)
						);
						$number++;
					}
					for ($number; $number <= $boxes; $number++)
					{
						$code[] = array
						(
							array
							(
								array('<code>', ''),
								array('<number>', $number)
							)
						);
					}
					$array = array_merge
					(
						array
						(
							array('<boxes>', $boxes),
							array('<content>', htmlentities($posted['content'])),
							array('<error>', $error),
							array('<name>', 'add'),
							array('<title>', htmlentities($posted['title'])),
							array('<value>', $this->language['add'])
						),
						$this->parseLoop('loop code', $code, $return)
					);
					$return = $this->replace($array, $return);
					$section = $this->language['add'];
				}
				elseif (!strcmp($_GET['cmd'], 'edit'))
				{
					if (!isset($_GET['file']))
					{
						//You need to specify a file.
						$this->owner->getTemplate('badrequest');
					}
					$filepath = $this->owner->templates . '/' . $type . '/' . $_GET['file'] . '.' . $filetype;
					if (!file_exists($filepath))
					{
						//Nonexistant file specified.
						$this->owner->getTemplate('badrequest');
					}
					$return = $this->owner->getTemplate('admin_form'); //We'll work with one template confirming to the different conditions of code.
					$return = $this->replace($this->parseConditional('if code', ($type == 'code'), $return, 'else code'), $return); //Are we going to use the code box?
					$return = $this->replace($this->parseConditional('if editing', (!strcmp($_GET['cmd'], 'edit')), $return), $return); //Determine if we are editing in order for allow display of the 'Save and Continue' button.
					$array = array_merge
					(
						$this->parseConditional('if content', ($type == 'content'), $return), //Whether or not to show the content box.
						$this->parseConditional('if glue', ($type == 'glue'), $return), //Are we doing anything with the glue?
						$this->parseConditional('if error', ($error), $return) //Are there any errors at all?
					);
					$return = $this->replace($array, $return);
					if (!$error)
					{
						$posted['title'] = $_GET['file'];
						if ($type == 'glue')
						{
							$array = explode('=', file_get_contents($filepath));
							$posted['content'] = (isset($array[0])) ? $array[0] : '';
							unset($array[0]);
							$posted['code']  = $array;
						}
						else
						{
							$posted['content'] = file_get_contents($filepath);
						}
						$posted['code'] = (!isset($posted['code'])) ? array('') : $posted['code'];
					}
					$code = array();
					$boxes = (isset($_POST['boxes']) && (intval($_POST['boxes']) >= 0)) ? intval($_POST['boxes']) : count($posted['code']);
					$number = 1;
					foreach ($posted['code'] as $value)
					{
						if ($number > $boxes)
						{
							break;
						}
						$code[] = array
						(
							array
							(
								array('<code>', htmlentities($value)),
								array('<number>', $number)
							)
						);
						$number++;
					}
					for ($number; $number <= $boxes; $number++)
					{
						$code[] = array
						(
							array
							(
								array('<code>', ''),
								array('<number>', $number)
							)
						);
					}
					$array = array_merge
					(
						array
						(
							array('<boxes>', $boxes),
							array('<content>', htmlentities($posted['content'])),
							array('<error>', $error),
							array('<oldtitle>', $_GET['file']),
							array('<name>', 'edit'),
							array('<title>', htmlentities($posted['title'])),
							array('<value>', $this->language['edit'])
						),
						$this->parseLoop('loop code', $code, $return)
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
						//Turn it into a one-dimensional array with that single ID as an element to prevent any errors.
						$id = array($id);
					}
					$input = array();
					foreach ($id as $value)
					{
						$filepath = $this->owner->templates . '/' . $type . '/' . $value . '.' . $filetype;
						if (file_exists($filepath))
						{
							//Considering that the template exists, add it to the input array for deletion mark.
							$input[] = array
							(
								array
								(
									array('<title>', htmlspecialchars($value))
								)
							);
						}
					}
					if (empty($input))
					{
						//No template(s) found.
						$this->owner->getTemplate('badrequest');
					}
					//Are you sure you want to delete the template(s)?
					$return = $this->owner->getTemplate('admin_delete');
					$delimeter = $this->getSection('section delimeter', $return);
					$delimeter = ($delimeter) ? $delimeter[0] : '';
					$message = $this->replace($this->parseLoop('loop names', $input, $this->language['deleteconfirm'], $delimeter), $this->language['deleteconfirm']);
					$array = array_merge
					(
						$this->parseConditional('section delimeter', false, $return),
						$this->parseConditional('if content', ($type == 'content'), $return), //Whether or not to show the content box.
						$this->parseConditional('if code', ($type == 'code'), $return, 'else code'), //Are we going to use the code box?
						$this->parseConditional('if glue', ($type == 'glue'), $return), //Are we doing anything with the glue?
						$this->parseConditional('if error', ($error), $return) //Are there any errors at all?
					);
					$return = $this->replace($array, $return);
					$array = array
					(
						array('<error>', $error),
						array('<name>', 'delete'),
						array('<message>', $message)
					);
					$array = array_merge
					(
						$array,
						$this->parseLoop('loop input', $input, $return)
					);
					$return = $this->replace($array, $return);
					$section = $this->language['delete'];
				}
			}
		}
		elseif (isset($_GET['cmd']) && (!strcmp($_GET['cmd'], 'view')) && $type == 'code')
		{
			if (!isset($_GET['file']))
			{
				//You must specify a file.
				$this->owner->getTemplate('badrequest');
			}
			$filepath = $this->owner->templates . '/' . $type . '/' . $_GET['file'] . '.' . $filetype;
			if (!file_exists($filepath))
			{
				//The file does not exist.
				$this->owner->getTemplate('badrequest');
			}
			$return = $this->owner->getTemplate('admin_form');
			$return = $this->replace($this->parseConditional('if code', ($type == 'code'), $return, 'else code'), $return); //Are we going to use the code box?
			$return = $this->replace($this->parseConditional('if editing', (!strcmp($_GET['cmd'], 'edit')), $return), $return); //Determine if we are editing in order for allow display of the 'Save and Continue' button.
			$array = array_merge
			(
				$this->parseConditional('if content', ($type == 'content'), $return), //Whether or not to show the content box.
				$this->parseConditional('if glue', ($type == 'glue'), $return), //Are we doing anything with the glue?
				$this->parseConditional('if error', ($error), $return) //Are there any errors at all?
			);
			$return = $this->replace($array, $return);
			$array = array
			(
				array('<code>', htmlentities(file_get_contents($filepath))),
				array('<title>', htmlentities($_GET['file']))
			);
			$return = $this->replace($array, $return);
			$section = $this->language['view'];
		}
		else
		{
			$return = $this->owner->getTemplate('admin_list');
			$return = $this->replace($this->parseConditional('if code', ($type == 'code'), $return, 'else code'), $return); //Are we going to use the code box?
			$array = array_merge
			(
				$this->parseConditional('if content', ($type == 'content'), $return), //Whether or not to show the content box.
				$this->parseConditional('if checked', ($this->navigation->settings['select']), $return), //Is this submission's checkbox ticked?
				$this->parseConditional('if glue', ($type == 'glue'), $return), //Are we doing anything with the glue?
				$this->parseConditional('if error', ($error), $return) //Are there any errors at all?
			);
			$return = $this->replace($array, $return);
			$templates = scandir($this->owner->templates . '/' . $type);
			if (is_array($templates))
			{
				foreach ($templates as $key => $value)
				{
					$preg = array('NULL');
					if ($this->navigation->settings['search'])
					{
						//File searching regular expression.
						preg_match_all('/(.*?)' . $this->navigation->settings['search'] . '(.*?)/', basename($value, '.' . $filetype), $preg, PREG_SET_ORDER);
					}
					if ($value == '.' || $value == '..' || $value == (basename($value, '.' . $filetype)) || !$preg)
					{
						//Keep these out of the array, as they are links to directories.
						unset($templates[$key]);
					}
				}
			}
			$count = count($templates);
			if ($this->navigation->settings['start'] && ($this->navigation->settings['start'] > $count-1))
			{
				$this->owner->getTemplate('badrequest');
			}
			$link = $this->navigation->pagination($count, $this->config['navigation']['pages']);
			$section = $this->language['page'] . ' ' . preg_replace('/\<[a](.*)\>(.*)\\<\/[a]\>/', '$2', $link['current']);
			natcasesort($templates); //Case insensitive sorting.
			//Sorting order for natcasesort is Ascending, so to simulate, we reverse the array. Otherwise, leave the variable as is.
			$templates = ($this->navigation->settings['orderby_type'] == 'desc') ? array_reverse($templates) : $templates;
			$iterations = 0;
			$entries = array();
			if (!empty($templates))
			{
				$highlightstart = $this->getSection('section highlightstart', $return);
				$highlightstart = ($highlightstart) ? $highlightstart[0] : '';
				$highlightend = $this->getSection('section highlightend', $return);
				$highlightend = ($highlightend) ? $highlightend[0] : '';
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
							array
							(
								array('<displaytitle>', $displaytitle),
								array('<limit>', $this->navigation->settings['limit']),
								array('<orderby>', $this->navigation->settings['orderby_type']),
								array('<path>', htmlentities($path)),
								array('<search>', $this->navigation->settings['search']),
								array('<start>', $this->navigation->settings['start']),
								array('<title>', urlencode($title))
							)
						);
					}
					$iterations++;
					if ($iterations == $this->navigation->settings['start'] + $this->navigation->settings['limit'])
					{
						//This loop has gone as far as the starting and ending point, so end it.
						break;
					}
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
					array('<orderby>', $this->navigation->settings['orderby_type']),
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
			{
				if ($value == '.' || $value == '..' )
				{
					//Remove directories from listing.
					unset($templates[$key]);
				}
			}
			if (count($templates) <= $this->navigation->settings['start'])
			{
				$start = $this->navigation->reduce(count($templates), true);
				$redirect = $path . 'start=' . $start . '&limit=' . $this->navigation->settings['limit'] . '&orderby=' . $this->navigation->settings['orderby_type'] . '&search=' . $this->navigation->settings['search'];
			}
			$this->navigation->redirect($this->config['navigation']['redirect'], $redirect, $redirectmessage);
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
	private function checkWritable($template, $directory, $filetype)
	{
		$return = false;
		$template = strval($template);
		$directory = strval($directory);
		$filetype = strval($filetype);
		if (!file_exists($this->owner->templates . '/' . $directory . '/' . $template . '.' . $filetype))
		{
			//The file doesn't exist, so now we check that the directory itself is writable.
			if (is_writable($this->owner->templates . '/' . $directory))
			{
				//It is, so we will create the file and CHMOD it so it is writable by our script.
				touch($this->owner->templates . '/' . $directory . '/' . $template . '.' . $filetype);
				chmod($this->owner->templates . '/' . $directory . '/' . $template . '.' . $filetype, 0666);
			}
			else
			{
				//It is not, so return an error.
				$return = $this->language['directorynotchmod'];
			}
		}
		return $return;
	}

	/**
	Removes illegal characters from filenames.
	**@param string Filename to format
	**@returns string Formatted Filename
	**/
	private function fileFormat($return)
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
		//Replace the illegal characters (if any) with the replacements defined in an array.
		$return = $this->replace($array, strval($return));
		//Remove Linebreaks(\n) and Carriage Returns (\r)
		$return = preg_replace('/(\\r\\n)|\\r|\\n/', '', $return);
		return $return;
	}

	/**
	Retrieve a template from a remote URL.
	**@param string Template name
	**@param string Remote URL of giveTemplates() script
	**@returns string The Template
	**/
	public function getExternalTemplate($template, $url)
	{
		$url = strval($url);
		if (strstr($url, '?'))
		{
			//Convert the first querystring character to an ampersand, to avoid URL breaking.
			$char = '&';
		}
		else
		{
			$char = '?';
		}
		return file_get_contents($url . $char . 'template=' . $template);
	}

	/**
	Retrieve a Section
	**@param string Section Name
	**@param string Output Variable
	**@returns string Section
	**/
	public function getSection($string, $output)
	{
		$output = strval($output);
		$string = strval($string);
		$string = $this->pregFormat($string);
		$open = $this->pregFormat($this->config['parse']['sections']['open']);
		$close = $this->pregFormat($this->config['parse']['sections']['close']);
		$end = $this->pregFormat($this->config['parse']['sections']['end']);
		$return = array();
		$parse = array();
		preg_match_all('/' . $open . $string . $close . '((?:(?!' . $open . $string . $close . ').)*?)' . $open . $end . $string . $close . '/msx', $output, $parse, PREG_SET_ORDER);
		foreach ($parse as $value)
		{
			$return[] = $value[1];
		}
		return $return;
	}

	/**
	Set-up templates for getExternalTemplate() requests
	**@param array Template List
	**@param string Failure Message
	**@param boolean Whether templates array is a blacklist or not
	**/
	public function giveTemplates($templates, $message = '', $blacklist = false)
	{
		$message = strval($message);
		if (is_array($templates))
		{
			if (isset($_GET['template']))
			{
				$success = false;
				if ($blacklist)
				{
					//Blacklist, checks if the requested template is not one specified in the first parameter.
					if (!in_array($_GET['template'], $templates))
					{
						$success = true;
					}
				}
				else
				{
					//Whitelist, checks if the requested template is one specified in the first parameter.
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
		}
		else
		{
			trigger_error($this->language['invalidtypearray'], E_USER_WARNING);
		}
		exit($message);
	}

	/**
	Parse
	**@param string Output Variable
	**@returns array Parsed String
	**/
	public function parse($eval, $open, $close, $output, $escape = false)
	{
		$return = array();
		$output = strval($output);
		$open = $this->pregFormat($open);
		$close = $this->pregFormat($close);
		//Match [expression_here] as phrases
		preg_match_all('/' . $open . '((?:(?!' . $open . ').)*?)' . $close . '/msx', $output, $return, PREG_SET_ORDER);
		$temp = $return;
		if ($escape !== false)
		{
			$escape = strval($escape);
			$section = $this->getSection($escape, $output);
			$open = $this->config['parse']['sections']['open'];
			$close = $this->config['parse']['sections']['close'];
			$end = $this->config['parse']['sections']['end'];
			foreach ($section as $value)
			{
				$value = $open . $escape . $close . $value . $open . $end . $escape . $close;
				$return[] = array($value, $value);
			}
			$positions = $this->replacePositions($return, $output);
			$return = $positions[1];
		}
		//Loop through the $parse array and run respective actions for them.
		if (is_array($return))
		{
			foreach ($return as $key => $value)
			{
				if (in_array($value, $temp))
				{
					$case = $value[1];
					$return[$key][1] = eval('return' . $eval . ';');
				}
				else
				{
					unset($return[$key]);
				}
			}
		}
		return $return;
	}

	/**
	Parses conditional statements in templates
	**@param string Conditional Name
	**@param boolean Condition to evaluate
	**@param string Output Variable
	**@returns array Parsed Conditional(s)
	**/
	public function parseConditional($if, $bool, $output, $else = false)
	{
		$output = strval($output);
		$if = strval($if);
		$if = $this->pregFormat($if);
		$open = $this->pregFormat($this->config['parse']['sections']['open']);
		$close = $this->pregFormat($this->config['parse']['sections']['close']);
		$end = $this->pregFormat($this->config['parse']['sections']['end']);
		$return = array();
		$parse = array();
		//Match the IF conditionals.
		preg_match_all('/' . $open . $if . $close . '((?:(?!' . $open . $if . $close . ').)*?)' . $open . $end . $if . $close . '/msx', $output, $parse, PREG_SET_ORDER);
		foreach ($parse as $value)
		{
			//Now we evaluate the conditional for if, making sure the expression is true.
			$replacement = ($bool) ? $value[1] : '';
			$return[] = array($value[0], $replacement);
		}
		if ($else !== false)
		{
			$else = strval($else);
			$else = $this->pregFormat($else);
			//Match the ELSE conditionals.
			preg_match_all('/' . $open . $else . $close . '((?:(?!' . $open . $else . $close . ').)*?)' . $open . $end . $else . $close . '/msx', $output, $parse, PREG_SET_ORDER);
			foreach ($parse as $value)
			{
				//Now we evaluate the conditional for else, making sure the expression is false.
				$replacement = ($bool) ? '' : $value[1];
				$return[] = array($value[0], $replacement);
			}
		}
		return $return;
	}

	/**
	Parses conditional statements in templates
	**@param string Conditional Name
	**@param boolean Condition to evaluate
	**@param string Output Variable
	**@param string
	**@returns array Parsed Loop(s)
	**/
	public function parseLoop($string, $replace, $output, $implode = '')
	{
		$output = strval($output);
		$implode = strval($implode);
		$string = strval($string);
		$string = $this->pregFormat($string);
		$open = $this->pregFormat($this->config['parse']['sections']['open']);
		$close = $this->pregFormat($this->config['parse']['sections']['close']);
		$end = $this->pregFormat($this->config['parse']['sections']['end']);
		$parse = array();
		$return = array();
		//Match the loops.
		preg_match_all('/' . $open . $string . $close . '((?:(?!' . $open . $string . $close . ').)*?)' . $open . $end . $string . $close . '/msx', $output, $parse, PREG_SET_ORDER);
		foreach ($parse as $value)
		{
			$replacements = array();
			if (is_array($replace))
			{
				foreach ($replace as $value2)
				{
					$string = $value[1];
					if (is_array($value2))
					{
						foreach ($value2 as $value3)
						{
							$string = $this->replace($value3, $string);
						}
					}
					else
					{
						trigger_error($this->language['invalidtypearray'], E_USER_WARNING);
					}
					$replacements[] = $string;
				}
			}
			else
			{
				trigger_error($this->language['invalidtypearray'], E_USER_WARNING);
			}
			//Separate the loops by use of a certain key, in the case that it is needed.
			$return[] = array($value[0], implode($implode, $replacements));
		}
		return $return;
	}

	/**
	Format the text for usage in a preg_match.
	**@param string String
	**@returns string Formatted String
	**/
	public function pregFormat($return)
	{
		$return = strval($return);
		$return = preg_quote($return, '/');
		//Escape whitespace with the whitespace character(\s)
		$return = str_replace(' ', '\s', $return);
		$return = str_replace('	', '\s', $return);
		//Remove Linebreaks(\n) and Carriage Returns (\r), replacing them with whitespace(\s)
		$return = preg_replace('/(\\r\\n)|\\r|\\n/', '\s', $return);
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
			$add = 0;
			$positions = $this->replacePositions($array, $return);
			$pos = $positions[0];
			$array = $positions[1];
			foreach ($pos as $key => $value)
			{
				$length = strlen(strval($array[$value][0]));
				$return = substr_replace($return, strval($array[$value][1]), $key+$add, $length);
				$add += strlen(strval($array[$value][1])) - strlen(strval($array[$value][0]));
			}
		}
		else
		{
			trigger_error($this->language['invalidtypearray'], E_USER_WARNING);
		}
		return $return;
	}

	/**
	Finds the positions of valid arrays.
	**@param array Placeholders and Replacements
	**@param string String
	**@returns array First Key is the positions, second key is replacement array
	**/
	public function replacePositions($array, $output)
	{
		if (is_array($array))
		{
			$output = strval($output);
			$pos = array();
			$taken = array();
			usort($array, array('TIE', 'replaceSort'));
			foreach ($array as $key => $value)
			{
				$unset = true;
				if (is_array($value) && (array_key_exists(0, $value) && array_key_exists(1, $value)))
				{
					$position = -1;
					while (($position = stripos($output, strval($value[0]), $position+1)) !== false)
					{
						$pass = true;
						for ($x = 0; $x < strlen(strval($value[0])); $x++)
						{
							if (in_array($position + $x, $taken))
							{
								$pass = false;
								break;
							}
						}
						if ($pass)
						{
							$pos[$position] = $key;
							for ($x = 0; $x < strlen(strval($value[0])); $x++)
							{
								$taken[] = $position + $x;
							}
							$unset = false;
						}
					}
				}
				else
				{
					trigger_error($this->language['invalidtypearray'], E_USER_WARNING);
				}
				if ($unset)
				{
					unset($array[$key]);
				}
			}
		}
		else
		{
			trigger_error($this->language['invalidtypearray'], E_USER_WARNING);
		}
		ksort($pos);
		return array($pos, $array);
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
			{
				$return = (strlen(strval($a[0])) < strlen(strval($b[0])));
			}
		}
		else
		{
			trigger_error($this->language['invalidtypearray'], E_USER_WARNING);
		}
		return $return;
	}
}
$suit->getTemplate('config');
$suit->tie = new TIE($suit, $suit->vars['config']);
$suit->getTemplate('navigation'); //Load the methods for the admin class.
if (isset($suit->tie->config['cookie']['domain']) && isset($suit->tie->config['cookie']['length']) && isset($suit->tie->config['cookie']['path']) && isset($suit->tie->config['cookie']['prefix']))
{
	//Grab the template holding an array of available languages, and store it in $returns.
	$suit->getTemplate('languages');
	$languages = $suit->vars['languages'];
	$language = -1; //By default, we'll return a negative value until we verify the language exists.
	if (isset($_COOKIE[$suit->tie->config['cookie']['prefix'] . 'language']))
	{
		//If the user already has a language in cookies, then we can just set the language from there.
		$language = $_COOKIE[$suit->tie->config['cookie']['prefix'] . 'language'];
		if (!(isset($languages[$language]) || $language == -1))
		{
			//Language doesn't exist; remove the cookie and set the language to a negative integer.
			$language = -1;
			setcookie($suit->tie->config['cookie']['prefix'] . 'language', '', time() - $suit->tie->config['cookie']['length'], $suit->tie->config['cookie']['path'], $suit->tie->config['cookie']['domain']);
		}
	}
	if ($language != -1)
	{
		//Grab the language file since it exists.
		$suit->getTemplate($languages[$language][1]);
	}
	else
	{
		if (is_array($languages))
		{
			foreach ($languages as $value)
			{
				if ($value[2])
				{
					//Use the first language, for our alternative.
					$suit->getTemplate($value[1]);
				}
			}
		}
	}
	$suit->tie->languageid = $language;
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
				//Undo magic_quotes_sybase effects.
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