<?php
/**
**@This file is part of TIE.
**@TIE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@TIE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with TIE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class TIE
{
	public $config = array();

	public $languageid = array();

	public $language = array();

	public $owner;

	public $version = '1.2.9';

	/**
	http://www.suitframework.com/docs/TIE+Construct#howitworks
	**/
	public function __construct(&$owner, $config)
	{
		$this->owner = &$owner;
		$this->config = $config;
		if (isset($this->config['cookie']['domain']) && isset($this->config['cookie']['length']) && isset($this->config['cookie']['path']) && isset($this->config['cookie']['prefix']))
		{
			$this->owner->getTemplate('languages/main');
			$this->languageid = -1;
			if (isset($_COOKIE[$this->config['cookie']['prefix'] . 'language']))
			{
				$this->languageid = $_COOKIE[$this->config['cookie']['prefix'] . 'language'];
				if (!(isset($this->owner->vars['languages'][$this->languageid]) || $this->languageid == -1))
				{
					$this->languageid = -1;
					setcookie($this->config['cookie']['prefix'] . 'language', '', time() - $this->config['cookie']['length'], $this->config['cookie']['path'], $this->config['cookie']['domain']);
				}
			}
			if ($this->languageid != -1)
				$this->owner->getTemplate($this->owner->vars['languages'][$this->languageid][1]);
			else
				if (is_array($this->owner->vars['languages']))
					foreach ($this->owner->vars['languages'] as $value)
						if ($value[2])
							$this->owner->getTemplate($value[1]);
			$this->language = $this->owner->vars['language'];
		}
		if (get_magic_quotes_gpc())
		{
			$in = array(&$_GET, &$_POST, &$_COOKIE);
			while (list($k, $v) = each($in))
				foreach ($v as $key => $value)
				{
					if (!is_array($value))
					{
						$in[$k][$key] = (ini_get('magic_quotes_sybase')) ?
							$in[$k][$key] = str_replace('\'\'', '\'', $value) :
							$in[$k][$key] = stripslashes($value);
						continue;
					}
					$in[] =& $in[$k][$key];
				}
			unset($in);
		}
	}

	/**
	http://www.suitframework.com/docs/adminArea#howitworks
	**/
	public function adminArea($type, $tie_delete = false, $tie_form = false, $tie_list = false, $tie_xml = false, $badrequest = false)
	{
		$type = strval($type);
		$tie_delete = strval(($tie_delete !== false) ?
			$tie_delete :
			$this->config['templates']['tie_delete']);
		$tie_form = strval(($tie_form !== false) ?
			$tie_form :
			$this->config['templates']['tie_form']);
		$tie_list = strval(($tie_list !== false) ?
			$tie_list :
			$this->config['templates']['tie_list']);
		$tie_xml = strval(($tie_xml !== false) ?
			$tie_xml :
			$this->config['templates']['tie_xml']);
		$badrequest = strval(($badrequest !== false) ?
			$badrequest :
			$this->config['templates']['badrequest']);
		$error = false;
		$path = $this->navigation->path($_SERVER['SCRIPT_NAME'], array('boxes', 'cmd', 'directory', 'directorytitle', 'limit', 'order', 'search', 'check', 'start', 'title'));
		$redirect = $this->navigation->path($_SERVER['SCRIPT_NAME'], array('boxes', 'cmd', 'directory', 'directorytitle', 'check', 'title'));
		$redirect = substr($redirect, 0, -1);
		$this->navigation->logistics();
		$char = (stristr(PHP_OS, 'WIN')) ?
			"\r\n" :
			(
				(stristr(PHP_OS, 'MAC')) ?
					"\r" :
					"\n"
			);
		$directory = $this->directorydata($_GET['directory']);
		if (!is_dir($this->owner->templates . '/' . $type . $directory['string']))
			$this->owner->getTemplate($badrequest);
		$filetype = ($type != 'code') ?
			(
				($type == 'glue') ?
					'txt' :
					'tpl'
			) :
			'inc.php';
		switch ($type)
		{
			case 'content':
				$filetype = 'tpl';
				break;
			case 'code':
				$filetype = 'inc.php';
				break;
			case 'glue':
				$filetype = 'txt';
				break;
		}
		$illegal = array
		(
			array('/', ''),
			array('\\', '')
		);
		$linebreak = array
		(
			array("\n", $char),
			array("\r", $char),
			array("\r\n", $char)
		);
		$post = ($type == 'glue') ?
			array('code', 'content', 'title') :
			array('content', 'title');
		if ($type != 'code')
		{
			$posted = array();
			foreach ($post as $value)
				$posted[$value] = (isset($_POST[$value])) ?
					$_POST[$value] :
					NULL;
			if (isset($_POST['import']))
				if ($_FILES['file']['type'] == 'text/xml')
					$xml = file_get_contents($_FILES['file']['tmp_name']);
				else
					$error = $this->language['filenotvalid'];
			if ($error === false)
			{
				if (((isset($_POST['add']) || (isset($_POST['edit']) || isset($_POST['editandcontinue']))) && isset($posted['title']) && isset($posted['content'])) || ((isset($_POST['copy']) || isset($_POST['create']) || isset($_POST['rename'])) && (isset($posted['title']))) || (isset($_POST['import']) && isset($_FILES['file'])) || (isset($_POST['move']) && isset($_POST['moveto']) && (isset($_POST['entry']) || isset($_POST['directoryentry']))) || isset($_POST['delete']))
				{
					$files = array();
					$directories = array();
					if (isset($_POST['delete']) || isset($_POST['import']) || isset($_POST['move']))
					{
						if (isset($_POST['import']))
							$files = $this->getSection('file', $xml, '<', '>', '/', '\\');
						elseif (isset($_POST['delete']) && is_array($_GET['title']))
							foreach ($_GET['title'] as $value)
								if (is_writable($this->owner->templates . '/' . $type . $directory['string']))
								{
									if ($value != '')
										$files[] = array
										(
											'code' => array(),
											'content' => array(''),
											'directory' => $directory['array'],
											'oldtitle' => '',
											'title' => $value
										);
								}
								else
								{
									$error = $this->language['directorynotchmod'];
									break;
								}
						elseif (isset($_POST['move']) && is_array($_POST['entry']))
							foreach ($_POST['entry'] as $value)
								if (is_writable($this->owner->templates . '/' . $type . $directory['string']))
								{
									if ($value != '')
									{
										$array = $directory['array'];
										if ($_POST['moveto'] == '..')
										{
											$array = array_values($array);
											unset($array[count($array) - 1]);
											$moveto = array();
										}
										else
											$moveto = array($_POST['moveto']);
										$files[] = array
										(
											'code' => array(),
											'content' => file_get_contents($this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($illegal, $value) . '.' . $filetype),
											'directory' => array_merge($array, $moveto),
											'oldtitle' => $this->replace($illegal, $value),
											'title' => $this->replace($illegal, $value)
										);
									}
								}
								else
								{
									$error = $this->language['directorynotchmod'];
									break;
								}
						foreach ($files as $key => $value)
						{
							if (isset($_POST['import']))
							{
								$files[$key] = array
								(
									'code' => $this->getSection('code', $value, '<', '>', '/', '\\'),
									'content' => $this->getSection('content', $value, '<', '>', '/', '\\'),
									'directory' => array_merge
									(
										$directory['array'],
										$this->getSection('array', $value, '<', '>', '/', '\\')
									),
									'title' => $this->getSection('title', $value, '<', '>', '/', '\\')
								);
								if (isset($files[$key]['title'][0]) && isset($files[$key]['content'][0]))
								{
									$files[$key]['title'] = $this->replace($illegal, $files[$key]['title'][0]);
									$files[$key]['oldtitle'] = $files[$key]['title'];
									$files[$key]['content'] = $files[$key]['content'][0];
									$this->getSection('code', $files[$key]['title'], '<', '>', '/', '\\');
									$this->getSection('content', $files[$key]['title'], '<', '>', '/', '\\');
									$this->getSection('array', $files[$key]['title'], '<', '>', '/', '\\');
									$this->getSection('title', $files[$key]['title'], '<', '>', '/', '\\');
									$this->getSection('directory', $files[$key]['title'], '<', '>', '/', '\\');
									$this->getSection('code', $files[$key]['content'], '<', '>', '/', '\\');
									$this->getSection('content', $files[$key]['content'], '<', '>', '/', '\\');
									$this->getSection('array', $files[$key]['content'], '<', '>', '/', '\\');
									$this->getSection('title', $files[$key]['content'], '<', '>', '/', '\\');
									$this->getSection('directory', $files[$key]['content'], '<', '>', '/', '\\');
									foreach ($files[$key]['code'] as $key2 => $value2)
									{
										$this->getSection('code', $files[$key]['code'][$key2], '<', '>', '/', '\\');
										$this->getSection('content', $files[$key]['code'][$key2], '<', '>', '/', '\\');
										$this->getSection('array', $files[$key]['code'][$key2], '<', '>', '/', '\\');
										$this->getSection('title', $files[$key]['code'][$key2], '<', '>', '/', '\\');
										$this->getSection('directory', $files[$key]['code'][$key2], '<', '>', '/', '\\');
									}
									foreach ($files[$key]['directory'] as $key2 => $value2)
									{
										$this->getSection('code', $files[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->getSection('content', $files[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->getSection('array', $files[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->getSection('title', $files[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->getSection('directory', $files[$key]['directory'][$key2], '<', '>', '/', '\\');
									}
								}
								else
									$error = $this->language['filenotvalid'];
								$thisdirectory = $this->directorydata($files[$key]['directory']);
								$filepath = $this->owner->templates . '/' . $type . $thisdirectory['string'] . '/' . $files[$key]['title'] . '.' . $filetype;
								if (is_file($filepath))
									$error = $this->language['duplicatetitle'];
							}
						}
					}
					if (isset($_POST['copy']) || isset($_POST['create']) || isset($_POST['delete']) || isset($_POST['import']) || isset($_POST['move']) || isset($_POST['rename']))
					{
						if (isset($_POST['delete']))
							$title = $_GET['directorytitle'];
						elseif (isset($_POST['move']))
							$title = $_POST['directoryentry'];
						elseif (isset($_POST['import']))
						{
							$directories = $this->getSection('directory', $xml, '<', '>', '/', '\\');
							foreach ($directories as $key => $value)
							{
								$directories[$key] = array
								(
									'directory' => array_merge
									(
										$directory['array'],
										$this->getSection('array', $value, '<', '>', '/', '\\')
									),
									'title' => $this->getSection('title', $value, '<', '>', '/', '\\')
								);
								if (isset($directories[$key]['title'][0]))
								{
									$directories[$key]['title'] = $this->replace($illegal, $directories[$key]['title'][0]);
									$this->getSection('code', $directories[$key]['title'], '<', '>', '/', '\\');
									$this->getSection('content', $directories[$key]['title'], '<', '>', '/', '\\');
									$this->getSection('array', $directories[$key]['title'], '<', '>', '/', '\\');
									$this->getSection('title', $directories[$key]['title'], '<', '>', '/', '\\');
									$this->getSection('directory', $directories[$key]['title'], '<', '>', '/', '\\');
									foreach ($directories[$key]['directory'] as $key2 => $value2)
									{
										$this->getSection('code', $directories[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->getSection('content', $directories[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->getSection('array', $directories[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->getSection('title', $directories[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->getSection('directory', $directories[$key]['directory'][$key2], '<', '>', '/', '\\');
									}
								}
								else
								{
									$error = $this->language['filenotvalid'];
									break;
								}
							}
						}
						else
						{
							$directories[] = array
							(
								'directory' => $directory['array'],
								'oldtitle' => $this->replace($illegal, $_GET['title']),
								'title' => $this->replace($illegal, $posted['title'])
							);
							$title = array($_GET['title']);
						}
						if ((isset($_POST['copy']) || isset($_POST['delete']) || isset($_POST['move']) || isset($_POST['rename'])) && is_array($title))
							foreach ($title as $value)
								if ($value != $_POST['moveto'] || !isset($_POST['move']))
								{
									if ($value != '')
									{
										if (!is_dir($this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($illegal, $value)))
											$this->owner->getTemplate($badrequest);
										$templates = array_diff($this->rscandir($this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($illegal, $value) . '/'), array('.', '..'));
										$templates = array_merge
										(
											array($this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($illegal, $value)),
											$templates
										);
										foreach ($templates as $value2)
										{
											$check = $value2;
											if (substr($check, strlen($check) - 1) == '/')
												$check = substr($check, 0, -1);
											$check = explode('/', $check);
											$check = array_values($check);
											unset($check[count($check) - 1]);
											$check = implode('/', $check);
											if (is_writable($check))
											{
												$showtitle = (!isset($_POST['move'])) ?
													'/' . $this->replace($illegal, $posted['title']) :
													'/' . $this->replace($illegal, $value);
												$moveto = (isset($_POST['move']) && ($_POST['moveto'] != '..')) ?
													'/' . $this->replace($illegal, $_POST['moveto']) :
													'';
												$newdirectory = explode('/', $directory['string']);
												$newdirectory = array_values($newdirectory);
												unset($newdirectory[count($newdirectory) - 1]);
												$newdirectory = implode('/', $newdirectory);
												$string = (isset($_POST['move']) && ($_POST['moveto'] == '..')) ?
													$newdirectory :
													$directory['string'];
												$new = str_replace($this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($illegal, $value), $this->owner->templates . '/' . $type . $string . $moveto . $showtitle, $value2);
												if (substr($new, strlen($new) - 1) == '/')
													$new = substr($new, 0, -1);
												$new = explode($this->owner->templates . '/' . $type . '/', $new, 2);
												$new = explode('/', $new[1]);
												$new = array_values($new);
												unset($new[count($new) - 1]);
												if (is_file($value2) && !isset($_POST['delete']))
													$files[] = array
													(
														'code' => '',
														'content' => file_get_contents($value2),
														'directory' => $new,
														'oldtitle' => '',
														'title' => basename($value2, '.' . $filetype),
													);
												elseif (!isset($_POST['delete']))
													$directories[] = array
													(
														'directory' => $new,
														'oldtitle' => basename($value2),
														'title' => basename($value2)
													);
											}
											else
											{
												$error = $this->language['directorynotchmod'];
												break;
											}
										}
									}
								}
								else
									$error = $this->language['cannotmovedirectorytoself'];
					}
					else
						$files[] = array
						(
							'code' => $posted['code'],
							'content' => $posted['content'],
							'directory' => $directory['array'],
							'oldtitle' => $this->replace($illegal, $_GET['title']),
							'title' => $this->replace($illegal, $posted['title']),
						);
					if ($error === false)
					{
						foreach ($directories as $value)
						{
							$thisdirectory = $this->directorydata($value['directory']);
							$filepath = $this->owner->templates . '/' . $type . $thisdirectory['string'] . '/' . $value['oldtitle'];
							$filepath2 = $this->owner->templates . '/' . $type . $thisdirectory['string'] . '/' . $value['title'];
							if (!isset($_POST['rename']) && !isset($_POST['copy']))
								$error = (!is_dir($filepath2)) ?
									(
										($value['title'] == '') ?
											$this->language['missingtitle'] :
											$error
									) :
									$this->language['duplicatetitle'];
							else
								$error = (!is_dir($filepath2) || $value['title'] == $value['oldtitle']) ?
									(
										($value['title'] == '') ?
											$this->language['missingtitle'] :
											$error
									) :
									$this->language['duplicatetitle'];
							if ($error !== false)
								break;
						}
						if ($error === false)
						{
							foreach ($directories as $value)
							{
								$error = $this->checkWritableFolder($type . $thisdirectory['string'] . '/' . $value['title'], $type . $thisdirectory['string']);
								if ($error !== false)
									break;
							}
							if ($error === false)
							{
								foreach ($files as $value)
								{
									$thisdirectory = $this->directorydata($value['directory']);
									$filepath = $this->owner->templates . '/' . $type . $thisdirectory['string'] . '/' . $value['oldtitle'] . '.' . $filetype;
									$filepath2 = $this->owner->templates . '/' . $type . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype;
									if (!isset($_POST['delete']))
									{
										if (!isset($_POST['edit']) && !isset($_POST['editandcontinue']))
											$error = (!is_file($filepath2)) ?
												(
													($value['title'] == '') ?
														$this->language['missingtitle'] :
														$error
												) :
												$this->language['duplicatetitle'];
										else
											if (is_file($filepath))
												$error = (!is_file($filepath2) || $value['title'] == $value['oldtitle']) ?
													(
														($value['title'] == '') ?
															$this->language['missingtitle'] :
															$error
													) :
													$this->language['duplicatetitle'];
											else
												$this->owner->getTemplate($badrequest);
										if ($error === false)
										{
											$error = $this->checkWritable($value['title'], $type . $thisdirectory['string'], $filetype);
											if ($error === false)
												if (is_writable($filepath2))
												{
													if ($type == 'glue' && !isset($_POST['rename']) && !isset($_POST['copy']) && !isset($_POST['move']))
													{
														if (!empty($value['code']))
															foreach ($value['code'] as $key => $value2)
																$value['code'][$key] = str_replace('=', '\=', $value2) . str_repeat('\\', $this->parseEscape($value2));
														$value['content'] = str_replace('=', '\=', $value['content']) . str_repeat('\\', $this->parseEscape($value['content'])) .
														(
															(!empty($value['code'])) ?
																'=' . implode('=', $value['code']) :
																''
														);
													}
													else
														$this->replace($linebreak, $value['content']);
													file_put_contents($filepath2, $value['content']);
													if ((isset($_POST['edit']) || isset($_POST['editandcontinue'])) && $value['title'] != $value['oldtitle'])
														unlink($filepath);
												}
												else
													$error = $this->language['filenotchmod'];
											if ($error === false && isset($_POST['move']) && is_array($_POST['entry']) && (in_array($value['title'], $_POST['entry'])))
												unlink($this->owner->templates . '/' . $type . $directory['string'] . '/' . $value['title'] . '.' . $filetype);
										}
									}
									else
										if (is_file($filepath2))
											unlink($filepath2);
										else
											$this->owner->getTemplate($badrequest);
								}
								if ($error === false)
								{
									if ((isset($_POST['rename']) && $posted['title'] != $_GET['title']) || (isset($_POST['delete']) && is_array($_GET['directorytitle'])) || (isset($_POST['move']) && is_array($_POST['directoryentry'])))
									{
										$title = (isset($_POST['delete'])) ?
											$_GET['directorytitle'] :
											(
												(isset($_POST['rename'])) ?
													array($_GET['title']) :
													$_POST['directoryentry']
											);
										foreach ($title as $value)
										{
											if (!in_array($value, array('', '.', '..')) && !(isset($_POST['move']) && $value == $_POST['moveto']))
											{
												$templates = array_diff($this->rscandir($this->owner->templates . '/' . $type . $directory['string'] . '/' . $value . '/'), array('.', '..'));
												$templates = array_reverse($templates);
												$templates[] = $this->owner->templates . '/' . $type . $directory['string'] . '/' . $value;
												foreach ($templates as $value2)
														if (is_file($value2))
															unlink($value2);
														else
															rmdir($value2);
											}
										}
									}
									$redirect .= (isset($_POST['editandcontinue'])) ?
										$directory['url'] . '&cmd=edit&title=' . $value['title'] :
										$directory['url'];
									$redirectmessage = (isset($_POST['add'])) ?
										$this->language['addedsuccessfully'] :
										(
											(isset($_POST['edit']) || isset($_POST['editandcontinue'])) ?
												$this->language['editedsuccessfully'] :
												(
													(isset($_POST['delete'])) ?
														$this->language['deletedsuccessfully'] :
														(
															(isset($_POST['create'])) ?
																$this->language['createdsuccessfully'] :
																(
																	(isset($_POST['rename'])) ?
																		$this->language['renamedsuccessfully'] :
																		(
																			(isset($_POST['remove'])) ?
																				$this->language['removedsuccessfully'] :
																				(
																					(isset($_POST['copy'])) ?
																						$this->language['copiedsuccessfully'] :
																						(
																							(isset($_POST['move'])) ?
																								$this->language['movedsuccessfully'] :
																								$this->language['importedsuccessfully']
																						)
																				)
																		)
																)
														)
												)
										);
								}
							}
						}
					}
				}
				elseif ((isset($_GET['cmd']) && ($_GET['cmd'] == 'export')) || isset($_POST['exportchecked']))
				{
					$files = (isset($_GET['cmd']) && $_GET['cmd'] == 'export') ?
						$_GET['title'] :
						$_POST['entry'];
					$directories = (isset($_GET['cmd']) && $_GET['cmd'] == 'export') ?
						$_GET['directorytitle'] :
						$_POST['directoryentry'];
					$filesarray = array();
					$directoriesarray = array();
					$xml = $this->owner->getTemplate($tie_xml);
					$escape = array
					(
						array('<array>', '\<array>'),
						array('<code>', '\<code>'),
						array('<content>', '\<content>'),
						array('<directory>', '\<directory>'),
						array('<file>', '\<file>'),
						array('<title>', '\<title>')
					);
					$fileescape = array_merge
					(
						$escape,
						array
						(
							array('</file>', '\</file>')
						)
					);
					$filetitleescape = array_merge
					(
						$fileescape,
						array
						(
							array('</array>', '\</array>')
						)
					);
					$filearrayescape = array_merge
					(
						$fileescape,
						array
						(
							array('</code>', '\</code>')
						)
					);
					$filecontentescape = array_merge
					(
						$fileescape,
						array
						(
							array('</content>', '\</content>')
						)
					);
					$filecodeescape = array_merge
					(
						$fileescape,
						array
						(
							array('</code>', '\</code>')
						)
					);
					$directoryescape = array_merge
					(
						$escape,
						array
						(
							array('</directory>', '\</directory>')
						)
					);
					$directorytitleescape = array_merge
					(
						$directoryescape,
						array
						(
							array('</array>', '\</array>')
						)
					);
					$directoryarrayescape = array_merge
					(
						$directoryescape,
						array
						(
							array('</code>', '\</code>')
						)
					);
					if (is_array($files))
						foreach ($files as $key => $value)
						{
							$files[$key] = $this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($illegal, $value) . '.' . $filetype;
							if (!is_file($files[$key]))
								unset($files[$key]);
						}
					else
						$files = array();
					if (is_array($directories))
						foreach ($directories as $key => $value)
						{
							$directories[$key] = $this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($illegal, $value);
							if (is_dir($directories[$key]))
							{
								$templates = array_diff($this->rscandir($directories[$key] . '/'), array('.', '..'));
								foreach ($templates as $value2)
									if (is_file($value2))
										$files[] = $value2;
									else
										$directories[] = $value2;
							}
							else
								unset($directories[$key]);
						}
					else
						$directories = array();
					if (empty($files) && empty($directories))
						$this->owner->getTemplate($badrequest);
					foreach ($directories as $value)
					{
						$dir = $value;
						if (substr($dir, strlen($dir) - 1) == '/')
							$dir = substr($dir, 0, -1);
						$dir = explode($this->owner->templates . '/' . $type . $directory['string'] . '/', $dir, 2);
						$dir = explode('/', $dir[1]);
						$dir = array_values($dir);
						unset($dir[count($dir) - 1]);
						$array = array();
						foreach ($dir as $value2)
							$array[] = array
							(
								array('<arraytoken>', $this->replace($directoryarrayescape, $value2) . str_repeat('\\', $this->parseEscape($value2)))
							);
						$value = basename($value);
						$directoriesarray[] = array_merge
						(
							array
							(
								array('<titletoken>', $this->replace($directorytitleescape, $value) . str_repeat('\\', $this->parseEscape($value)))
							),
							$this->parseLoop('loop array', $array, $xml)
						);
					}
					foreach ($files as $value)
					{
						if ($type == 'glue')
						{
							$array = explode('=', file_get_contents($value));
							$array = $this->owner->glueUnescape($array);
							$content = (isset($array[0])) ?
								$array[0] :
								'';
							unset($array[0]);
							$code = array();
							foreach ($array as $value2)
								$code[] = array
								(
									array('<codetoken>', $this->replace($filecodeescape, $value2) . str_repeat('\\', $this->parseEscape($value2)))
								);
						}
						else
						{
							$content = file_get_contents($value);
							$code = array();
						}
						$dir = explode($this->owner->templates . '/' . $type . $directory['string'] . '/', $value, 2);
						$dir = explode('/', $dir[1]);
						$dir = array_values($dir);
						unset($dir[count($dir) - 1]);
						$array = array();
						foreach ($dir as $value2)
							$array[] = array
							(
								array('<arraytoken>', $this->replace($filearrayescape, $value2) . str_repeat('\\', $this->parseEscape($value2)))
							);
						$title = basename($value, '.' . $filetype);
						$filesarray[] = array_merge
						(
							array
							(
								array('<contenttoken>', $this->replace($filecontentescape, $content) . str_repeat('\\', $this->parseEscape($content))),
								array('<titletoken>', $this->replace($filetitleescape, $title) . str_repeat('\\', $this->parseEscape($title)))
							),
							$this->parseLoop('loop array', $array, $xml),
							$this->parseLoop('loop code', $code, $xml)
						);
					}
					$array = array_merge
					(
						$this->parseLoop('loop directories', $directoriesarray, $xml),
						$this->parseLoop('loop files', $filesarray, $xml)
					);
					$xml = $this->replace($array, $xml);
					header('Pragma: public');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Content-type: text/xml');
					header('Content-Disposition: attachment; filename=' . $type . '.xml');
					header('Content-Length: ' . strlen($xml));
					exit($xml);
				}
				elseif (isset($_POST['deletechecked']) && ((isset($_POST['entry']) && is_array($_POST['entry'])) || (isset($_POST['directoryentry']) && is_array($_POST['directoryentry']))))
				{
					$titles = (isset($_POST['entry'])) ?
						implode('&title[]=', $_POST['entry']) :
						'';
					$directorytitles = (isset($_POST['directoryentry'])) ?
						implode('&directorytitle[]=', $_POST['directoryentry']) :
						'';
					$this->navigation->redirect($redirect . $directory['url'] . '&cmd=delete&title[]=' . $titles . '&directorytitle[]=' . $directorytitles, '', 0);
				}
				elseif (isset($_POST['boxes']) && ($_POST['boxes'] >= 0) && isset($_POST['boxes_submit']) && in_array($_GET['cmd'], array('add', 'edit')) && $type == 'glue')
					$error = $this->language['displayed'];
			}
		}
		if (isset($redirectmessage))
		{
			$templates = array_diff(scandir($this->owner->templates . '/' . $type . $directory['string']), array('.', '..'));
			if (!empty($directory['array']))
				$templates = array_merge
				(
					array('..'),
					$templates
				);
			if (count($templates) <= $this->navigation->settings['start'])
			{
				$start = $this->navigation->reduce(count($templates), true);
				if ($start < 0)
					$start = 0;
				$redirect = $this->navigation->path($_SERVER['SCRIPT_NAME'], array('boxes', 'cmd', 'directory', 'check', 'start', 'title'));
				$directory['url'] = substr($directory['url'], 1);
				$redirect .= $directory['url'] . '&start=' . $start;
			}
			$this->navigation->redirect($redirect, $redirectmessage);
		}
		if (isset($_GET['cmd']) && ((in_array($_GET['cmd'], array('add', 'copy', 'create', 'delete', 'edit', 'remove', 'rename')) && $type != 'code') || ($_GET['cmd'] == 'view' && $type == 'code')))
		{
			$return = $this->owner->getTemplate(($_GET['cmd'] == 'delete') ?
				$tie_delete :
				$tie_form);
			$title = (isset($_GET['title'])) ?
				$_GET['title'] :
				'';
			$directorytitle = (isset($_GET['directorytitle'])) ?
				$_GET['directorytitle'] :
				'';
			$sectiontitle = (!in_array($_GET['cmd'], array('add', 'create', 'delete'))) ?
				array($_GET['title']) :
				array();
			$section = array_merge
			(
				array($this->language[$_GET['cmd']]),
				$sectiontitle
			);
			$filepath = $this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($illegal, $title) . '.' . $filetype;
			$filepath2 = $this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($illegal, $title);
			if ((in_array($_GET['cmd'], array('edit', 'view')) && !is_file($filepath)) || (in_array($_GET['cmd'], array('rename', 'copy')) && (in_array($title, array('.', '..')) || !is_dir($filepath2))))
				$this->owner->getTemplate($badrequest);
			$array = array
			(
				array_merge
				(
					$this->parseConditional('section delimeter', false, $return),
					$this->parseConditional('if content', ($type == 'content' && !in_array($_GET['cmd'], array('copy', 'create', 'rename'))), $return),
					$this->parseConditional('if code', ($type == 'code'), $return, 'else code'),
					$this->parseConditional('if glue', ($type == 'glue' && !in_array($_GET['cmd'], array('copy', 'create', 'rename'))), $return),
					$this->parseConditional('if error', ($error), $return)
				),
				array_merge
				(
					$this->parseConditional('if glue', ($type == 'glue' && !in_array($_GET['cmd'], array('copy', 'create', 'rename'))), $return),
					$this->parseConditional('if content', ($type == 'content' && !in_array($_GET['cmd'], array('copy', 'create', 'rename'))), $return),
					$this->parseConditional('if error', ($error), $return),
					$this->parseConditional('if editing', ($_GET['cmd'] == 'edit'), $return)
				),
			);
			$nonparse = array
			(
				array('<name>', $_GET['cmd']),
				array('<value>', $section[0])
			);
			if ($_GET['cmd'] == 'delete')
			{
				$delimeter = $this->getSection('section delimeter', $return);
				$delimeter = (!empty($delimeter)) ?
					$delimeter[0] :
					'';
				$titles = array();
				$directorytitles = array();
				if (is_array($title))
					foreach ($title as $value)
					{
						$filepath = $this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($array, $value) . '.' . $filetype;
						if (is_file($filepath) && !in_array($value, array('.', '..')))
							$titles[] = array
							(
								array('<title>', htmlspecialchars($value))
							);
					}
				if (is_array($directorytitle))
					foreach ($directorytitle as $value)
					{
						$filepath = $this->owner->templates . '/' . $type . $directory['string'] . '/' . $this->replace($array, $value);
						if (is_dir($filepath) && !in_array($value, array('', '.', '..')))
							$directorytitles[] = array
							(
								array('<title>', htmlspecialchars($value))
							);
					}
				if (empty($titles) && empty($directorytitles))
					$this->owner->getTemplate($badrequest);
				$message = $this->language['deleteconfirm'];
				$messagearray = array
				(
					array_merge
					(
						$this->parseConditional('if titles', (!empty($titles)), $message),
						$this->parseConditional('if directorytitles', (!empty($directorytitles)), $message),
						$this->parseConditional('if both', (!empty($titles) && !empty($directorytitles)), $message)
					),
					array_merge
					(
						$this->parseConditional('if plural', (count($titles) != 1), $message),
						$this->parseConditional('if directoryplural', (count($directorytitles) != 1), $message, 'else directoryplural')
					),
					array_merge
					(
						$this->parseLoop('loop titles', $titles, $message, $delimeter),
						$this->parseLoop('loop directorytitles', $directorytitles, $message, $delimeter)
					)
				);
				$message = $this->replace($messagearray, $message);
				$array = array_merge
				(
					$array,
					array
					(
						array_merge
						(
							$nonparse,
							array
							(
								array('<error>', $error),
								array('<message>', $message)
							)
						)
					)
				);
			}
			else
			{
				$posted['title'] = (!isset($posted['title'])) ?
					(
						(isset($title)) ?
							$title :
							''
					) :
					$posted['title'];
				if ($type == 'glue')
				{
					$glue = (isset($filepath) && is_file($filepath)) ?
						explode('=', file_get_contents($filepath)) :
						array('', '');
					$glue = $this->owner->glueUnescape($glue);
					$posted['content'] = (isset($glue[0]) && !$posted['content']) ?
						$glue[0] :
						$posted['content'];
					unset($glue[0]);
					$posted['code'] = (!$posted['code']) ?
						$glue :
						$posted['code'];
				}
				else
				{
					if (!isset($posted['content']))
						$posted['content'] = '';
					$posted['content'] = (isset($filepath) && is_file($filepath) && !$posted['content']) ?
						file_get_contents($filepath) :
						$posted['content'];
				}
				$posted['code'] = (!isset($posted['code'])) ?
					array('') :
					$posted['code'];
				$code = array();
				if ($type == 'glue')
				{
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
					$array,
					array
					(
						array_merge
						(
							array_merge
							(
								$nonparse,
								array
								(
									array('<boxes>', $boxes),
									array('<content>', htmlentities(strval($posted['content']))),
									array('<error>', $error),
									array('<oldtitle>', htmlentities(strval($title))),
									array('<title>', htmlentities(strval($posted['title'])))
								)
							),
							$this->parseLoop('loop code', $code, $return)
						)
					)
				);
			}
			$return = $this->replace($array, $return);
		}
		else
		{
			$return = $this->owner->getTemplate($tie_list);
			$page = $this->getSection('section page', $return);
			$page = (!empty($page)) ?
				$page[0] :
				'';
			$section = array($this->language['page'] . $page . ($this->navigation->settings['start'] / $this->navigation->settings['limit'] + 1));
			$templates = array_diff(scandir($this->owner->templates . '/' . $type . $directory['string']), array('.', '..'));
			$files = array();
			$directories = array();
			if (is_array($templates))
				foreach ($templates as $key => $value)
				{
					$pos = true;
					if ($this->navigation->settings['search'] != '')
						$pos = strpos(basename($value, '.' . $filetype), $this->navigation->settings['search']);
					if ($pos !== false)
						if (is_file($this->owner->templates . '/' . $type . $directory['string'] . '/' . $value) && $value != (basename($value, '.' . $filetype)))
							$files[] = $value;
						elseif (is_dir($this->owner->templates . '/' . $type . $directory['string'] . '/' . $value))
							$directories[] = $value;
				}
			natcasesort($files);
			natcasesort($directories);
			$templates = array_merge
			(
				$directories,
				$files
			);
			$templates = ($this->navigation->settings['order'] == 'desc') ?
				array_reverse($templates) :
				$templates;
			if (!empty($directory['array']))
				$templates = array_merge
				(
					array('..'),
					$templates
				);
			if ($this->navigation->settings['start'] > (($count = count($templates)) - 1) && $this->navigation->settings['start'])
				$this->owner->getTemplate($badrequest);
			$link = $this->navigation->pagination($count);
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
				foreach ($templates as $value)
				{
					if ($iterations >= $this->navigation->settings['start'])
					{
						$title = (is_file($this->owner->templates . '/' . $type . $directory['string'] . '/' . $value)) ?
							basename($value, '.' . $filetype) :
							$value;
						$displaytitle = str_replace(htmlspecialchars($this->navigation->settings['search']), $highlightstart . $this->navigation->settings['search'] . $highlightend, htmlspecialchars($title));
						$array = $directory['replace'];
						if ($value == '..')
							unset($array[count($array) - 1]);
						$entries[] = array
						(
							$this->parseConditional('if entries_code', ($type == 'code'), $return, 'else entries_code'),
							$this->parseConditional('if file', (is_file($this->owner->templates . '/' . $type . $directory['string'] . '/' . $value)), $return, 'else file'),
							$this->parseConditional('if show', ($value != '..'), $return, 'else show'),
							$this->parseConditional('if entries_checked', ($this->navigation->settings['check']), $return),
							array_merge
							(
								array
								(
									array('<displaytitle>', $displaytitle),
									array('<limit>', $this->navigation->settings['limit']),
									array('<order>', $this->navigation->settings['order']),
									array('<path>', htmlentities($path)),
									array('<search>', $this->navigation->settings['search']),
									array('<start>', $this->navigation->settings['start']),
									array('<title>', urlencode($title)),
								),
								$this->parseLoop('loop directories', $array, $return)
							)
						);
					}
					$iterations++;
					if ($iterations == $this->navigation->settings['start'] + $this->navigation->settings['limit'])
						break;
				}
			}
			$array = array
			(
				$this->parseConditional('if code', ($type == 'code'), $return, 'else code'),
				array_merge
				(
					$this->parseConditional('if content', ($type == 'content'), $return),
					$this->parseConditional('if glue', ($type == 'glue' && !isset($_GET['create'])), $return),
					$this->parseConditional('if error', ($error), $return),
					$this->parseConditional('section highlightstart', false, $return),
					$this->parseConditional('section highlightend', false, $return),
					$this->parseConditional('section page', false, $return)
				),
				array_merge
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
					$this->parseLoop('loop directories', $directory['replace'], $return),
					$this->parseLoop('loop entries', $entries, $return)
				)
			);
			$return = $this->replace($array, $return);
		}
		$section = array_merge
		(
			$directory['array'],
			$section
		);
		$return = array($return, $section);
		return $return;
	}

	public function checkWritable($template, $directory, $filetype)
	{
		$return = false;
		$template = strval($template);
		$directory = strval($directory);
		$filetype = strval($filetype);
		if (!is_file($this->owner->templates . '/' . $directory . '/' . $template . '.' . $filetype))
			if (is_writable($this->owner->templates . '/' . $directory))
			{
				@touch($this->owner->templates . '/' . $directory . '/' . $template . '.' . $filetype) or $return = $this->language['filecouldnotbecreated'];
				@chmod($this->owner->templates . '/' . $directory . '/' . $template . '.' . $filetype, 0666);
			}
			else
				$return = $this->language['directorynotchmod'];
		return $return;
	}

	public function checkWritableFolder($folder, $directory)
	{
		$return = false;
		$folder = strval($folder);
		$directory = strval($directory);
		if (!is_dir($this->owner->templates . '/' . $folder))
			if (is_writable($this->owner->templates . '/' . $directory))
			{
				mkdir($this->owner->templates . '/' . $folder);
				chmod($this->owner->templates . '/' . $folder, 0777);
			}
			else
				$return = $this->language['directorynotchmod'];
		return $return;
	}

	public function directorydata($array)
	{
		$return = array
		(
			'array' => $array
		);
		$return['array'] = (isset($return['array']) && is_array($return['array'])) ?
			$return['array'] :
			array();
		$return['string'] = '';
		$return['replace'] = array();
		$return['url'] = '';
		foreach ($return['array'] as $key => $value)
			if ($value == '.' || $value == '..')
				unset($return['array'][$key]);
			else
			{
				$return['replace'][] = array
				(
					array('<directory>', urlencode(strval($value)))
				);
				$return['string'] .= '/' . $value;
				$return['url'] .= '&directory[]=' . $value;
			}
		return $return;
	}

	/**
	http://www.suitframework.com/docs/getSection#howitworks
	**/
	public function getSection($string, &$content, $open = false, $close = false, $end = false, $escape = false)
	{
		$return = array();
		$string = strval($string);
		$content = strval($content);
		$open = strval(($open !== false) ?
			$open :
			$this->config['parse']['sections']['open']);
		$close = strval(($close !== false) ?
			$close :
			$this->config['parse']['sections']['close']);
		$end = strval(($end !== false) ?
			$end :
			$this->config['parse']['sections']['end']);
		$escape = strval(($escape !== false) ?
			$escape :
			$this->config['parse']['escape']);
		$parse = $this->parseMatch($open . $string . $close, $open . $end . $string . $close, $content, $escape);
		foreach ($parse as $value)
			$return[] = $value[1];
		return $return;
	}

	/**
	http://www.suitframework.com/docs/parse#howitworks
	**/
	public function parse($eval, $open, $close, &$content, $escape = false)
	{
		$return = array();
		$eval = strval($eval);
		$open = strval($open);
		$close = strval($close);
		$content = strval($content);
		$escape = strval(($escape !== false) ?
			$escape :
			$this->config['parse']['escape']);
		$return = $this->parseMatch($open, $close, $content, $escape);
		foreach ($return as $key => $value)
		{
			$case = $value[1];
			$return[$key][1] = eval('return' . $eval . ';');
		}
		return $return;
	}

	/**
	http://www.suitframework.com/docs/parseConditional#howitworks
	**/
	public function parseConditional($if, $bool, &$content, $else = false, $open = false, $close = false, $end = false, $escape = false)
	{
		$return = array();
		$if = strval($if);
		$bool = strval($bool);
		$content = strval($content);
		$open = strval(($open !== false) ?
			$open :
			$this->config['parse']['sections']['open']);
		$close = strval(($close !== false) ?
			$close :
			$this->config['parse']['sections']['close']);
		$end = strval(($end !== false) ?
			$end :
			$this->config['parse']['sections']['end']);
		$escape = strval(($escape !== false) ?
			$escape :
			$this->config['parse']['escape']);
		$parse = $this->parseMatch($open . $if . $close, $open . $end . $if . $close, $content, $escape);
		foreach ($parse as $value)
		{
			$replacement = ($bool) ?
				$value[1] :
				'';
			$return[] = array($value[0], $replacement);
		}
		if ($else !== false)
		{
			$parse = $this->parseMatch($open . $else . $close, $open . $end . $else . $close, $content, $escape);
			foreach ($parse as $value)
			{
				$replacement = ($bool) ?
					'' :
					$value[1];
				$return[] = array($value[0], $replacement);
			}
		}
		return $return;
	}

	public function parseEscape($content)
	{
		$count = 0;
		while (isset($content[strlen($content) - ($count + 1)]) && ($content[strlen($content) - ($count + 1)] == '\\'))
			$count++;
		return $count;
	}

	/**
	http://www.suitframework.com/docs/parseLoop#howitworks
	**/
	public function parseLoop($string, $replace, &$content, $implode = '', $open = false, $close = false, $end = false, $escape = false)
	{
		$return = array();
		$string = strval($string);
		$content = strval($content);
		$implode = strval($implode);
		$open = strval(($open !== false) ?
			$open :
			$this->config['parse']['sections']['open']);
		$close = strval(($close !== false) ?
			$close :
			$this->config['parse']['sections']['close']);
		$end = strval(($end !== false) ?
			$end :
			$this->config['parse']['sections']['end']);
		$escape = strval(($escape !== false) ?
			$escape :
			$this->config['parse']['escape']);
		if (is_array($replace))
		{
			$replace = array_values($replace);
			if ((array_key_exists(0, $replace) && is_array($replace[0]) && ((array_key_exists(0, $replace[0]) && (is_array($replace[0][0]))) || empty($replace[0]))) || empty($replace))
			{
				$parse = $this->parseMatch($open . $string . $close, $open . $end . $string . $close, $content, $escape);
				foreach ($parse as $value)
				{
					$replacements = array();
					if (is_array($replace))
						foreach ($replace as $value2)
							$replacements[] = $this->replace($value2, $value[1]);
					else
						$this->warning($this->language['invalidtypearray']);
					$return[] = array($value[0], implode($implode, $replacements));
				}
			}
			else
				$this->warning($this->language['invalidtypearray']);
		}
		else
			$this->warning($this->language['invalidtypearray']);
		return $return;
	}

	/**
	http://www.suitframework.com/docs/parseMatch#howitworks
	**/
	public function parseMatch($open, $close, &$content, $escape = false)
	{
		$parse = array();
		$donotparse = array(array());
		$open = strval($open);
		$close = strval($close);
		$content = strval($content);
		$escape = strval(($escape !== false) ?
			$escape :
			$this->config['parse']['escape']);
		$pos = -1;
		while (($pos = $this->strpos($content, $open, $pos + 1)) !== false)
		{
			$call = array();
			$call = $this->parseUnescape($pos, $content, $escape);
			$pos -= strlen($escape) * ($call[1] / 2);
			$content = substr_replace($content, '', $pos, strlen($escape) * ($call[1] / 2));
			if (!$call[0])
			{
				$openpos = $pos;
				$closepos = $pos;
				$call[0] = true;
				while ($call[0] && ($closepos = $this->strpos($content, $close, $closepos + 1)) !== false)
					$call = $this->parseUnescape($closepos, $content, $escape);
				$call[0] = true;
				while ($call[0] && ($openpos = $this->strpos($content, $open, $openpos + 1)) !== false && ($openpos < $closepos))
					$call = $this->parseUnescape($openpos, $content, $escape);
				if ($closepos !== false && ($openpos === false || ($openpos > $closepos)))
				{
					$openpos = $pos;
					$closepos = $pos;
					$call[0] = true;
					while ($call[0] && ($closepos = $this->strpos($content, $close, $closepos + 1)) !== false)
					{
						$call = $this->parseUnescape($closepos, $content, $escape);
						$closepos -= strlen($escape) * ($call[1] / 2);
						$content = substr_replace($content, '', $closepos, strlen($escape) * ($call[1] / 2));
					}
					$call[0] = true;
					while ($call[0] && ($openpos = $this->strpos($content, $open, $openpos + 1)) !== false && ($openpos < $closepos))
					{
						$call = $this->parseUnescape($openpos, $content, $escape);
						$openpos -= strlen($escape) * ($call[1] / 2);
						$closepos -= strlen($escape) * ($call[1] / 2);
						$content = substr_replace($content, '', $openpos, strlen($escape) * ($call[1] / 2));
					}
					$match = substr($content, $pos + strlen($open), $closepos - ($pos + strlen($open)));
					$parse[] = array($open . $match . $close, $match);
					$pos = $closepos;
				}
			}
		}
		return $parse;
	}

	private function parseUnescape($pos, $content, $escape)
	{
		$replace = array();
		$pos = intval($pos);
		$content = strval($content);
		$escape = strval($escape);
		$count = 0;
		if ($escape)
		{
			while (abs($start = $pos - ($count + strlen($escape))) == $start && substr($content, $start, strlen($escape)) == $escape)
				$count += strlen($escape);
			$count = $count / strlen($escape);
		}
		$condition = $count % 2;
		if ($condition)
			$count++;
		return array($condition, $count);
	}

	/**
	http://www.suitframework.com/docs/replace#howitworks
	**/
	public function replace($array, $return)
	{
		$return = strval($return);
		if (is_array($array))
		{
			$array = array_values($array);
			if ((array_key_exists(0, $array) && (is_array($array[0]))) || empty($array))
			{
				if (array_key_exists(0, $array) && (is_array($array[0]) && (array_key_exists(0, $array[0]) && (!is_array($array[0][0])))))
					$array = array($array);
				foreach ($array as $value)
				{
					$add = 0;
					$pos = array();
					$repeated = array();
					$taken = array();
					usort($value, array('TIE', 'replaceSort'));
					foreach ($value as $key => $value2)
					{
						if (is_array($value2) && (array_key_exists(0, $value2) && array_key_exists(1, $value2)))
						{
							if (!in_array($value2[0], $repeated))
							{
								$unset = true;
								$position = -1;
								while (($position = $this->strpos($return, $value2[0], $position + 1)) !== false)
								{
									$pass = true;
									foreach ($taken as $value3)
									{
										if (($position > $value3[0] && $position < $value3[1]) || ($position + strlen(strval($value2[0])) > $value3[0] && $position + strlen(strval($value2[0])) < $value3[1]))
										{
											$pass = false;
											break;
										}
									}
									if ($pass)
									{
										$pos[$position] = $key;
										$taken[] = array($position, $position + strlen(strval($value2[0])));
										$unset = false;
									}
								}
								if ($unset)
									unset($value2[$key]);
								$repeated[] = $value2[0];
							}
						}
						else
							$this->warning($this->language['invalidtypearray']);
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
		}
		else
			$this->warning($this->language['invalidtypearray']);
		return $return;
	}

	private function replaceSort($a, $b)
	{
		$return = false;
		if (is_array($a) && is_array($b))
		{
			if (array_key_exists(0, $a) && array_key_exists(0, $b))
				$return = (strlen(strval($a[0])) < strlen(strval($b[0])));
		}
		else
			$this->warning($this->language['invalidtypearray'], 3);
		return $return;
	}

	public function rscandir($base = '', $data = array())
	{
		$array = array_diff(scandir($base), array('.', '..'));
		foreach($array as $value)
			if (is_dir($base . $value))
			{
				$data[] = $base . $value . '/';
				$data = $this->rscandir($base . $value . '/', $data);
			}
			elseif (is_file($base . $value))
				$data[] = $base . $value;
		return $data;
	}

	private function strpos($haystack, $needle, $offset = 0)
	{
		$haystack = strval($haystack);
		$needle = strval($needle);
		$offset = intval($offset);
		return ($this->config['flag']['insensitive']) ?
			stripos($haystack, $needle, $offset) :
			strpos($haystack, $needle, $offset);
	}

	private function warning($warning, $key = 1)
	{
		$warning = strval($warning);
		if (ini_get('error_reporting'))
		{
			$backtrace = debug_backtrace();
			$array = array
			(
				array('<warning>', $warning),
				array('<file>', $backtrace[$key]['file']),
				array('<line>', $backtrace[$key]['line'])
			);
			echo $this->replace($array, $this->language['tiewarning']);
			error_log($this->replace($array, $this->language['tiewarningplain']));
		}
	}
}
$suit->getTemplate('tie/config');
$suit->tie = new TIE($suit, $suit->vars['config']);
?>