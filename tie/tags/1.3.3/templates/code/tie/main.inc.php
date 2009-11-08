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

	public $language = array();

	public $owner;

	public $settings = array();

	public $version = '1.3.3';

	/**
	http://www.suitframework.com/docs/TIE+Construct#howitworks
	**/
	public function __construct(&$owner, $config, $array = NULL, $limit = NULL)
	{
		$this->owner = &$owner;
		$this->config = $config;
		$array = (isset($array)) ?
			$array :
			$this->config['navigation']['array'];
		$limit = intval((isset($limit)) ?
			$limit :
			$this->config['navigation']['limit']);
		$this->settings['start'] = (isset($array['start'])) ?
			intval($array['start']) :
			0;
		$this->settings['limit'] = (isset($array['limit'])) ?
			intval($array['limit']) :
			$limit;
		$this->settings['search'] = (isset($array['search'])) ?
			$array['search'] :
			'';
		$this->settings['order'] = (isset($array['order']) && $array['order'] == 'desc') ?
			'desc' :
			'asc';
		$this->settings['order_reverse'] = (isset($array['order']) && $array['order'] == 'asc') ?
			'asc' :
			'desc';
		$this->settings['check'] = (isset($array['check']) && $array['check'] == 'true');
		if (isset($this->config['cookie']['domain']) && isset($this->config['cookie']['length']) && isset($this->config['cookie']['path']) && isset($this->config['cookie']['prefix']))
		{
			$this->owner->getTemplate('languages/main');
			$this->language = -1;
			if (isset($_COOKIE[$this->config['cookie']['prefix'] . 'language']))
			{
				$this->language = $_COOKIE[$this->config['cookie']['prefix'] . 'language'];
				if (!(isset($this->owner->vars['languages'][$this->language]) || $this->language == -1))
				{
					$this->language = -1;
					setcookie($this->config['cookie']['prefix'] . 'language', '', time() - $this->config['cookie']['length'], $this->config['cookie']['path'], $this->config['cookie']['domain']);
				}
			}
			if ($this->language != -1)
				$this->owner->getTemplate($this->owner->vars['languages'][$this->language][1]);
			else
				if (is_array($this->owner->vars['languages']))
					foreach ($this->owner->vars['languages'] as $value)
						if ($value[2])
							$this->owner->getTemplate($value[1]);
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
	public function adminArea($type, $badrequest = NULL, $delete = NULL, $form = NULL, $list = NULL, $xml = NULL)
	{
		$type = strval($type);
		$delete = strval((isset($delete)) ?
			$delete :
			$this->config['templates']['delete']);
		$form = strval((isset($form)) ?
			$form :
			$this->config['templates']['form']);
		$list = strval((isset($list)) ?
			$list :
			$this->config['templates']['list']);
		$xml = strval((isset($xml)) ?
			$xml :
			$this->config['templates']['xml']);
		$badrequest = strval((isset($badrequest)) ?
			$badrequest :
			$this->config['templates']['badrequest']);
		$error = false;
		$nodes = $this->owner->config['parse']['nodes'];
		$nodes[] = $this->owner->parseConditional('if code', ($type == 'code'), 'else code');
		$nodes[] = $this->owner->parseConditional('if content', ($type == 'content'));
		$nodes[] = $this->owner->parseConditional('if glue', ($type == 'glue'));
		$nodes[] = $this->owner->parseConditional('if box', (!in_array($_GET['cmd'], array('copy', 'create', 'rename'))));
		$path = $this->path(array('boxes', 'cmd', 'directory', 'directorytitle', 'limit', 'order', 'search', 'check', 'start', 'title'));
		$redirect = $this->path(array('boxes', 'cmd', 'directory', 'directorytitle', 'check', 'title'));
		$redirect = $redirect[0];
		if (!$this->logistics())
			$this->owner->getTemplate($badrequest);
		$directory = $this->directorydata($_GET['directory']);
		if (!is_dir($this->owner->config['templates'][$type] . $directory['string']))
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
					$upload = file_get_contents($_FILES['file']['tmp_name']);
				else
					$error = $this->owner->vars['language']['filenotvalid'];
			if ($error === false)
			{
				if
				(
					(
						(
							isset($_POST['add']) ||
							isset($_POST['edit']) ||
							isset($_POST['editandcontinue'])
						) &&
						isset($posted['title']) &&
						isset($posted['content'])
					) ||
					(
						(
							isset($_POST['copy']) ||
							isset($_POST['create']) ||
							isset($_POST['rename'])
						) &&
						isset($posted['title'])
					) ||
					(
						isset($_POST['import']) &&
						isset($_FILES['file'])
					) ||
					(
						(
							(
								isset($_POST['move']) &&
								isset($_POST['moveto'])
							) ||
							(
								isset($_POST['replace']) &&
								isset($_POST['find']) &&
								isset($_POST['replacewith'])
							)
						) &&
						(
							isset($_POST['entry']) ||
							isset($_POST['directoryentry'])
						)
					) ||
					isset($_POST['delete'])
				)
				{
					$files = array();
					$directories = array();
					if (isset($_POST['import']))
					{
						$files = $this->owner->getSection('file', $upload, '<', '>', '/', '\\');
						foreach ($files as $key => $value)
						{
							$temp = array($value, $value, $value);
							$files[$key] = array
							(
								'code' => $this->owner->getSection('code', $value, '<', '>', '/', '\\'),
								'content' => $this->owner->getSection('content', $temp[0], '<', '>', '/', '\\'),
								'directory' => array_merge
								(
									$directory['array'],
									$this->owner->getSection('array', $temp[1], '<', '>', '/', '\\')
								),
								'title' => $this->owner->getSection('title', $temp[2], '<', '>', '/', '\\')
							);
							if (isset($files[$key]['title'][0]) && isset($files[$key]['content'][0]))
							{
								$files[$key]['title'] = $this->owner->replace($illegal, $files[$key]['title'][0]);
								$files[$key]['oldtitle'] = $files[$key]['title'];
								$files[$key]['content'] = $files[$key]['content'][0];
								$this->owner->getSection('code', $files[$key]['title'], '<', '>', '/', '\\');
								$this->owner->getSection('content', $files[$key]['title'], '<', '>', '/', '\\');
								$this->owner->getSection('array', $files[$key]['title'], '<', '>', '/', '\\');
								$this->owner->getSection('directory', $files[$key]['title'], '<', '>', '/', '\\');
								$this->owner->getSection('code', $files[$key]['content'], '<', '>', '/', '\\');
								$this->owner->getSection('array', $files[$key]['content'], '<', '>', '/', '\\');
								$this->owner->getSection('title', $files[$key]['content'], '<', '>', '/', '\\');
								$this->owner->getSection('directory', $files[$key]['content'], '<', '>', '/', '\\');
								foreach ($files[$key]['code'] as $key2 => $value2)
								{
									$this->owner->getSection('content', $files[$key]['code'][$key2], '<', '>', '/', '\\');
									$this->owner->getSection('array', $files[$key]['code'][$key2], '<', '>', '/', '\\');
									$this->owner->getSection('title', $files[$key]['code'][$key2], '<', '>', '/', '\\');
									$this->owner->getSection('directory', $files[$key]['code'][$key2], '<', '>', '/', '\\');
								}
								foreach ($files[$key]['directory'] as $key2 => $value2)
								{
									$this->owner->getSection('code', $files[$key]['directory'][$key2], '<', '>', '/', '\\');
									$this->owner->getSection('content', $files[$key]['directory'][$key2], '<', '>', '/', '\\');
									$this->owner->getSection('title', $files[$key]['directory'][$key2], '<', '>', '/', '\\');
									$this->owner->getSection('directory', $files[$key]['directory'][$key2], '<', '>', '/', '\\');
								}
							}
							else
								$error = $this->owner->vars['language']['filenotvalid'];
							$thisdirectory = $this->directorydata($files[$key]['directory']);
							$filepath = $this->owner->config['templates'][$type] . $thisdirectory['string'] . '/' . $files[$key]['title'] . '.' . $filetype;
							if (is_file($filepath))
								$error = $this->owner->vars['language']['duplicatetitle'];
						}
					}
					elseif (isset($_POST['delete']) && is_array($_GET['title']))
						foreach ($_GET['title'] as $value)
							if (is_writable($this->owner->config['templates'][$type] . $directory['string']))
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
								$error = $this->owner->vars['language']['directorynotchmod'];
								break;
							}
					elseif (isset($_POST['move']) && is_array($_POST['entry']))
						foreach ($_POST['entry'] as $value)
							if (is_writable($this->owner->config['templates'][$type] . $directory['string']))
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
									$stripped = $this->owner->replace($illegal, $value);
									$files[] = array
									(
										'code' => array(),
										'content' => file_get_contents($this->owner->config['templates'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype),
										'directory' => array_merge($array, $moveto),
										'oldtitle' => $stripped,
										'title' => $stripped
									);
								}
							}
							else
							{
								$error = $this->owner->vars['language']['directorynotchmod'];
								break;
							}
					elseif (isset($_POST['replace']) && is_array($_POST['entry']))
						foreach ($_POST['entry'] as $value)
							if ($value != '')
							{
								$stripped = $this->owner->replace($illegal, $value);
								if ($type == 'glue')
								{
									$glue = $this->owner->explodeUnescape('=', '\\', file_get_contents($this->owner->config['templates'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype));
									foreach ($glue as $key2 => $value2)
										$glue[$key2] = str_replace($_POST['find'], $_POST['replacewith'], $value2);
									$contenttoken = implode('=', $glue);
								}
								else
									$contenttoken = str_replace($_POST['find'], $_POST['replacewith'], file_get_contents($this->owner->config['templates'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype));
								$files[] = array
								(
									'code' => array(),
									'content' => $contenttoken,
									'directory' => $directory['array'],
									'oldtitle' => $stripped,
									'title' => $stripped
								);
							}
					$strippedget = $this->owner->replace($illegal, $_GET['title']);
					$strippedposted = $this->owner->replace($illegal, $posted['title']);
					if (isset($_POST['copy']) || isset($_POST['create']) || isset($_POST['delete']) || isset($_POST['import']) || isset($_POST['move']) || isset($_POST['rename']) || isset($_POST['replace']))
					{
						if (isset($_POST['delete']))
							$title = $_GET['directorytitle'];
						elseif (isset($_POST['move']) || isset($_POST['replace']))
							$title = $_POST['directoryentry'];
						elseif (isset($_POST['import']))
						{
							$directories = $this->owner->getSection('directory', $upload, '<', '>', '/', '\\');
							foreach ($directories as $key => $value)
							{
								$temp = $value;
								$directories[$key] = array
								(
									'directory' => array_merge
									(
										$directory['array'],
										$this->owner->getSection('array', $value, '<', '>', '/', '\\')
									),
									'title' => $this->owner->getSection('title', $temp, '<', '>', '/', '\\')
								);
								if (isset($directories[$key]['title'][0]))
								{
									$directories[$key]['title'] = $this->owner->replace($illegal, $directories[$key]['title'][0]);
									$this->owner->getSection('code', $directories[$key]['title'], '<', '>', '/', '\\');
									$this->owner->getSection('content', $directories[$key]['title'], '<', '>', '/', '\\');
									$this->owner->getSection('array', $directories[$key]['title'], '<', '>', '/', '\\');
									$this->owner->getSection('directory', $directories[$key]['title'], '<', '>', '/', '\\');
									foreach ($directories[$key]['directory'] as $key2 => $value2)
									{
										$this->owner->getSection('code', $directories[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->owner->getSection('content', $directories[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->owner->getSection('title', $directories[$key]['directory'][$key2], '<', '>', '/', '\\');
										$this->owner->getSection('directory', $directories[$key]['directory'][$key2], '<', '>', '/', '\\');
									}
								}
								else
								{
									$error = $this->owner->vars['language']['filenotvalid'];
									break;
								}
							}
						}
						else
						{
							$directories[] = array
							(
								'directory' => $directory['array'],
								'oldtitle' => $strippedget,
								'title' => $strippedposted
							);
							$title = array($_GET['title']);
						}
						if
						(
							(
								isset($_POST['copy']) || isset($_POST['delete']) || isset($_POST['move']) || isset($_POST['rename']) || isset($_POST['replace'])
							) &&
							is_array($title)
						)
							foreach ($title as $value)
								if ($value != $_POST['moveto'] || !isset($_POST['move']))
								{
									if ($value != '')
									{
										$stripped = $this->owner->replace($illegal, $value);
										if (!is_dir($this->owner->config['templates'][$type] . $directory['string'] . '/' . $stripped))
											$this->owner->getTemplate($badrequest);
										$templates = array_diff($this->rscandir($this->owner->config['templates'][$type] . $directory['string'] . '/' . $stripped . '/'), array('.', '..'));
										$templates = array_merge
										(
											array($this->owner->config['templates'][$type] . $directory['string'] . '/' . $stripped),
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
												$showtitle = (!isset($_POST['move']) && !isset($_POST['replace'])) ?
													'/' . $strippedposted :
													'/' . $stripped;
												$moveto = (isset($_POST['move']) && ($_POST['moveto'] != '..')) ?
													'/' . $this->owner->replace($illegal, $_POST['moveto']) :
													'';
												$newdirectory = explode('/', $directory['string']);
												$newdirectory = array_values($newdirectory);
												unset($newdirectory[count($newdirectory) - 1]);
												$newdirectory = implode('/', $newdirectory);
												$string = (isset($_POST['move']) && ($_POST['moveto'] == '..')) ?
													$newdirectory :
													$directory['string'];
												$new = str_replace($this->owner->config['templates'][$type] . $directory['string'] . '/' . $stripped, $this->owner->config['templates'][$type] . $string . $moveto . $showtitle, $value2);
												if (substr($new, strlen($new) - 1) == '/')
													$new = substr($new, 0, -1);
												$new = explode($this->owner->config['templates'][$type] . '/', $new, 2);
												$new = explode('/', $new[1]);
												$new = array_values($new);
												unset($new[count($new) - 1]);
												if (is_file($value2) && !isset($_POST['delete']))
												{
													if (isset($_POST['replace']))
														if ($type == 'glue')
														{
															$glue = $this->owner->explodeUnescape('=', '\\', file_get_contents($value2));
															foreach ($glue as $key3 => $value3)
																$glue[$key3] = str_replace($_POST['find'], $_POST['replacewith'], $value3);
															$contenttoken = implode('=', $glue);
														}
														else
															$contenttoken = str_replace($_POST['find'], $_POST['replacewith'], file_get_contents($value2));
													else
														$contenttoken = file_get_contents($value2);
													$files[] = array
													(
														'code' => '',
														'content' => $contenttoken,
														'directory' => $new,
														'oldtitle' => basename($value2, '.' . $filetype),
														'title' => basename($value2, '.' . $filetype),
													);
												}
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
												$error = $this->owner->vars['language']['directorynotchmod'];
												break;
											}
										}
									}
								}
								else
									$error = $this->owner->vars['language']['cannotmovedirectorytoself'];
					}
					else
						$files[] = array
						(
							'code' => $posted['code'],
							'content' => $posted['content'],
							'directory' => $directory['array'],
							'oldtitle' => $strippedget,
							'title' => $strippedposted,
						);
					if ($error === false)
					{
						foreach ($directories as $value)
						{
							$thisdirectory = $this->directorydata($value['directory']);
							$filepath = $this->owner->config['templates'][$type] . $thisdirectory['string'] . '/' . $value['title'];
							if (!isset($_POST['rename']) && !isset($_POST['copy']) && !isset($_POST['replace']))
								$error = (!is_dir($filepath)) ?
									(
										($value['title'] == '') ?
											$this->owner->vars['language']['missingtitle'] :
											$error
									) :
									$this->owner->vars['language']['duplicatetitle'];
							else
								$error = (!is_dir($filepath) || $value['title'] == $value['oldtitle']) ?
									(
										($value['title'] == '') ?
											$this->owner->vars['language']['missingtitle'] :
											$error
									) :
									$this->owner->vars['language']['duplicatetitle'];
							if ($error !== false)
								break;
						}
						if ($error === false)
						{
							foreach ($directories as $value)
							{
								$thisdirectory = $this->directorydata($value['directory']);
								$error = false;
								if (!is_dir($this->owner->config['templates'][$type] . $thisdirectory['string'] . '/' . $value['title']))
									if (is_writable($this->owner->config['templates'][$type] . $thisdirectory['string']))
									{
										mkdir($this->owner->config['templates'][$type] . $thisdirectory['string'] . '/' . $value['title']);
										chmod($this->owner->config['templates'][$type] . $thisdirectory['string'] . '/' . $value['title'], 0777);
									}
									else
										$error = $this->owner->vars['language']['directorynotchmod'];
								if ($error !== false)
									break;
							}
							if ($error === false)
							{
								foreach ($files as $value)
								{
									$thisdirectory = $this->directorydata($value['directory']);
									$filepath = $this->owner->config['templates'][$type] . $thisdirectory['string'] . '/' . $value['oldtitle'] . '.' . $filetype;
									$filepath2 = $this->owner->config['templates'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype;
									if (!isset($_POST['delete']))
									{
										if (!isset($_POST['rename']))
											if (!isset($_POST['edit']) && !isset($_POST['editandcontinue']) && !isset($_POST['replace']))
												$error = (!is_file($filepath2)) ?
													(
														($value['title'] == '') ?
															$this->owner->vars['language']['missingtitle'] :
															$error
													) :
													$this->owner->vars['language']['duplicatetitle'];
											else
												if (is_file($filepath))
													$error = (!is_file($filepath2) || $value['title'] == $value['oldtitle']) ?
														(
															($value['title'] == '') ?
																$this->owner->vars['language']['missingtitle'] :
																$error
														) :
														$this->owner->vars['language']['duplicatetitle'];
												else
													$this->owner->getTemplate($badrequest);
										if ($error === false)
										{
											$error = false;
											if (!is_file($this->owner->config['templates'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype))
												if (is_writable($this->owner->config['templates'][$type] . $thisdirectory['string']))
												{
													@touch($this->owner->config['templates'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype) or $return = $this->owner->vars['language']['filecouldnotbecreated'];
													@chmod($this->owner->config['templates'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype, 0666);
												}
												else
													$return = $this->owner->vars['language']['directorynotchmod'];
											if ($error === false)
												if (is_writable($filepath2))
												{
													if ($type == 'glue' && !isset($_POST['rename']) && !isset($_POST['copy']) && !isset($_POST['move']) && !isset($_POST['replace']))
													{
														if (!empty($value['code']))
															foreach ($value['code'] as $key => $value2)
																$value['code'][$key] = $this->owner->escape(array('='), '\\', $value2);
														$value['content'] = $this->owner->escape(array('='), '\\', $value['content']) .
														(
															(!empty($value['code'])) ?
																'=' . implode('=', $value['code']) :
																''
														);
													}
													else
													{
														$array = array
														(
															array("\r", "\n"),
															array("\r\n", "\n")
														);
														$value['content'] = $this->owner->replace($array, $value['content']);
													}
													file_put_contents($filepath2, $value['content']);
													if ((isset($_POST['edit']) || isset($_POST['editandcontinue'])) && $value['title'] != $value['oldtitle'])
														unlink($filepath);
												}
												else
													$error = $this->owner->vars['language']['filenotchmod'];
											if ($error === false && isset($_POST['move']) && is_array($_POST['entry']) && in_array($value['title'], $_POST['entry']))
												unlink($this->owner->config['templates'][$type] . $directory['string'] . '/' . $value['title'] . '.' . $filetype);
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
									if
									(
										(
											isset($_POST['rename']) &&
											$posted['title'] != $_GET['title']
										) ||
										(
											isset($_POST['delete']) &&
											is_array($_GET['directorytitle'])
										) ||
										(
											isset($_POST['move']) &&
											is_array($_POST['directoryentry'])
										)
									)
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
												$templates = array_diff($this->rscandir($this->owner->config['templates'][$type] . $directory['string'] . '/' . $value . '/'), array('.', '..'));
												$templates = array_reverse($templates);
												$templates[] = $this->owner->config['templates'][$type] . $directory['string'] . '/' . $value;
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
										$this->owner->vars['language']['addedsuccessfully'] :
										(
											(isset($_POST['edit']) || isset($_POST['editandcontinue'])) ?
												$this->owner->vars['language']['editedsuccessfully'] :
												(
													(isset($_POST['delete'])) ?
														$this->owner->vars['language']['deletedsuccessfully'] :
														(
															(isset($_POST['create'])) ?
																$this->owner->vars['language']['createdsuccessfully'] :
																(
																	(isset($_POST['rename'])) ?
																		$this->owner->vars['language']['renamedsuccessfully'] :
																		(
																			(isset($_POST['remove'])) ?
																				$this->owner->vars['language']['removedsuccessfully'] :
																				(
																					(isset($_POST['copy'])) ?
																						$this->owner->vars['language']['copiedsuccessfully'] :
																						(
																							(isset($_POST['move'])) ?
																								$this->owner->vars['language']['movedsuccessfully'] :
																								(
																									(isset($_POST['replace'])) ?
																										$this->owner->vars['language']['replacedsuccessfully'] :
																										$this->owner->vars['language']['importedsuccessfully']
																								)
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
				elseif
				(
					(
						$_GET['cmd'] == 'export'
					) ||
					(
						isset($_POST['exportchecked']) &&
						(
							(
								isset($_POST['entry']) && is_array($_POST['entry'])
							) ||
							(
								isset($_POST['directoryentry']) && is_array($_POST['directoryentry'])
							)
						)
					)
				)
				{
					$files = ($_GET['cmd'] == 'export') ?
						$_GET['title'] :
						$_POST['entry'];
					$directories = ($_GET['cmd'] == 'export') ?
						$_GET['directorytitle'] :
						$_POST['directoryentry'];
					$nodes = array();
					$filesarray = array();
					$directoriesarray = array();
					$xml = $this->owner->getTemplate($xml);
					if (is_array($files))
						foreach ($files as $key => $value)
						{
							$files[$key] = $this->owner->config['templates'][$type] . $directory['string'] . '/' . $this->owner->replace($illegal, $value) . '.' . $filetype;
							if (!is_file($files[$key]))
								unset($files[$key]);
						}
					else
						$files = array();
					if (is_array($directories))
						foreach ($directories as $key => $value)
						{
							$directories[$key] = $this->owner->config['templates'][$type] . $directory['string'] . '/' . $this->owner->replace($illegal, $value);
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
					$symbols = array('<array>', '<code>', '<content>', '<directory>', '<file>', '<title>', '</array>', '</code>', '</content>', '</directory>', '</file>', '</title>');
					foreach ($directories as $value)
					{
						$dir = $value;
						if (substr($dir, strlen($dir) - 1) == '/')
							$dir = substr($dir, 0, -1);
						$dir = explode($this->owner->config['templates'][$type] . $directory['string'] . '/', $dir, 2);
						$dir = explode('/', $dir[1]);
						$dir = array_values($dir);
						unset($dir[count($dir) - 1]);
						$array = array();
						foreach ($dir as $value2)
							$array[] = array
							(
								array('[arraytoken]', $this->owner->escape($symbols, '\\', $value2)),
								array()
							);
						$value = basename($value);
						$directoriesarray[] = array
						(
							array('[titletoken]', $this->owner->escape($symbols, '\\', $value)),
							array
							(
								$this->owner->parseLoop('loop array', $array)
							)
						);
					}
					foreach ($files as $value)
					{
						$code = array();
						if ($type == 'glue')
						{
							$array = $this->owner->explodeUnescape('=', '\\', file_get_contents($value));
							$content = (isset($array[0])) ?
								$array[0] :
								'';
							unset($array[0]);
							foreach ($array as $value2)
								$code[] = array
								(
									array('[codetoken]', $this->owner->escape($symbols, '\\', $value2)),
									array()
								);
						}
						else
							$content = file_get_contents($value);
						$dir = explode($this->owner->config['templates'][$type] . $directory['string'] . '/', $value, 2);
						$dir = explode('/', $dir[1]);
						$dir = array_values($dir);
						unset($dir[count($dir) - 1]);
						$array = array();
						foreach ($dir as $value2)
							$array[] = array
							(
								array('[arraytoken]', $this->owner->escape($symbols, '\\', $value2)),
								array()
							);
						$title = basename($value, '.' . $filetype);
						$filesarray[] = array
						(
							array
							(
								array('[contenttoken]', $this->owner->escape($symbols, '\\', $content)),
								array('[titletoken]', $this->owner->escape($symbols, '\\', $title))
							),
							array
							(
								$this->owner->parseLoop('loop array', $array),
								$this->owner->parseLoop('loop code', $code)
							)
						);
					}
					$nodes[] = $this->owner->parseLoop('loop directories', $directoriesarray);
					$nodes[] = $this->owner->parseLoop('loop files', $filesarray);
					$xml = $this->owner->parse($nodes, $xml);
					header('Pragma: public');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Content-type: text/xml');
					header('Content-Disposition: attachment; filename=' . $type . '.xml');
					header('Content-Length: ' . strlen($xml));
					exit($xml);
				}
				elseif
				(
					isset($_POST['deletechecked']) &&
					(
						(
							isset($_POST['entry']) &&
							is_array($_POST['entry'])
						) ||
						(
							isset($_POST['directoryentry']) &&
							is_array($_POST['directoryentry'])
						)
					)
				)
				{
					$titles = (isset($_POST['entry'])) ?
						implode('&title[]=', $_POST['entry']) :
						'';
					$directorytitles = (isset($_POST['directoryentry'])) ?
						implode('&directorytitle[]=', $_POST['directoryentry']) :
						'';
					$this->redirect($redirect . $directory['url'] . '&cmd=delete&title[]=' . $titles . '&directorytitle[]=' . $directorytitles, '', 0);
				}
				elseif (isset($_POST['boxes']) && ($_POST['boxes'] >= 0) && isset($_POST['boxes_submit']) && in_array($_GET['cmd'], array('add', 'edit')) && $type == 'glue')
					$error = $this->owner->vars['language']['displayed'];
			}
		}
		$this->owner->vars['error'] = $error;
		$nodes[] = $this->owner->parseConditional('if error', ($error));
		if (isset($redirectmessage))
		{
			$templates = array_diff(scandir($this->owner->config['templates'][$type] . $directory['string']), array('.', '..'));
			if (!empty($directory['array']))
				$templates = array_merge
				(
					array('..'),
					$templates
				);
			if (count($templates) <= $this->settings['start'])
			{
				$start = $this->reduce(count($templates), true);
				if ($start < 0)
					$start = 0;
				$redirect = $this->path(array('boxes', 'cmd', 'directory', 'check', 'start', 'title'));
				$directory['url'] = substr($directory['url'], 1);
				$redirect = $redirect[0] . $redirect[2] . $directory['url'] . '&start=' . $start;
			}
			$this->redirect($redirect, $redirectmessage);
		}
		if
		(
			(
				in_array($_GET['cmd'], array('add', 'copy', 'create', 'delete', 'edit', 'remove', 'rename')) &&
				$type != 'code'
			) ||
			(
				$_GET['cmd'] == 'view' &&
				$type == 'code'
			)
		)
		{
			$return = $this->owner->getTemplate(($_GET['cmd'] == 'delete') ?
				$delete :
				$form);
			$sectiontitle = (!in_array($_GET['cmd'], array('add', 'create', 'delete'))) ?
				array(htmlspecialchars($_GET['title'])) :
				array();
			$section = array_merge
			(
				array($this->owner->vars['language'][$_GET['cmd']]),
				$sectiontitle
			);
			$stripped = $this->owner->replace($illegal, $_GET['title']);
			$filepath = $this->owner->config['templates'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype;
			$filepath2 = $this->owner->config['templates'][$type] . $directory['string'] . '/' . $stripped;
			if
			(
				(
					in_array($_GET['cmd'], array('edit', 'view')) &&
					!is_file($filepath)
				) ||
				(
					in_array($_GET['cmd'], array('rename', 'copy')) &&
					(
						in_array($_GET['title'], array('.', '..')) ||
						!is_dir($filepath2)
					)
				)
			)
				$this->owner->getTemplate($badrequest);
			$this->owner->vars['name'] = $_GET['cmd'];
			$this->owner->vars['value'] = $section[0];
			if ($_GET['cmd'] == 'delete')
			{
				$delimiter = $this->owner->getSection('section delimiter', $return);
				$delimiter = (!empty($delimiter)) ?
					$delimiter[0] :
					'';
				$titles = array();
				$directorytitles = array();
				if (is_array($_GET['title']))
					foreach ($_GET['title'] as $value)
					{
						$filepath = $this->owner->config['templates'][$type] . $directory['string'] . '/' . $this->owner->replace($illegal, $value) . '.' . $filetype;
						if (is_file($filepath) && !in_array($value, array('.', '..')))
							$titles[] = array
							(
								array('[title]', htmlspecialchars($value)),
								array()
							);
					}
				if (is_array($_GET['directorytitle']))
					foreach ($_GET['directorytitle'] as $value)
					{
						$filepath = $this->owner->config['templates'][$type] . $directory['string'] . '/' . $this->owner->replace($illegal, $value);
						if (is_dir($filepath) && !in_array($value, array('', '.', '..')))
							$directorytitles[] = array
							(
								array('[title]', htmlspecialchars($value)),
								array()
							);
					}
				if (empty($titles) && empty($directorytitles))
					$this->owner->getTemplate($badrequest);
				$message = $this->owner->vars['language']['deleteconfirm'];
				$message = $this->owner->parse
				(
					array_merge
					(
						$nodes,
						array
						(
							$this->owner->parseConditional('if titles', (!empty($titles))),
							$this->owner->parseConditional('if directorytitles', (!empty($directorytitles))),
							$this->owner->parseConditional('if plural', (count($titles) != 1)),
							$this->owner->parseConditional('if directoryplural', (count($directorytitles) != 1), 'else directoryplural'),
							$this->owner->parseLoop('loop titles', $titles, $delimiter),
							$this->owner->parseLoop('loop directorytitles', $directorytitles, $delimiter)
						)
					),
					$message
				);
				$this->owner->vars['message'] = $message;
				$nodes[] = $this->owner->parseConditional('section delimiter', false);
			}
			else
			{
				$nodes[] = $this->owner->parseConditional('if editing', ($_GET['cmd'] == 'edit'));
				$posted['title'] = (!isset($posted['title'])) ?
					$_GET['title'] :
					$posted['title'];
				if ($type == 'glue')
				{
					$glue = (isset($filepath) && is_file($filepath)) ?
						file_get_contents($filepath) :
						'=';
					$glue = $this->owner->explodeUnescape('=', '\\', $glue);
					$posted['content'] = (isset($glue[0]) && !$posted['content']) ?
						$glue[0] :
						$posted['content'];
					unset($glue[0]);
					$posted['code'] = (!$posted['code']) ?
						$glue :
						$posted['code'];
					$boxes = (isset($_POST['boxes']) && (intval($_POST['boxes']) >= 0)) ?
						intval($_POST['boxes']) :
						count($posted['code']);
					$number = 1;
					$code = array();
					foreach ($posted['code'] as $value)
					{
						if ($number > $boxes)
							break;
						$code[] = array
						(
							array
							(
								array('[code]', $value),
								array('[number]', $number)
							),
							array()
						);
						$number++;
					}
					for ($number; $number <= $boxes; $number++)
						$code[] = array
						(
							array
							(
								array('[code]', ''),
								array('[number]', $number)
							),
							array()
						);
					$nodes[] = $this->owner->parseLoop('loop code', $code);
					$this->owner->vars['boxes'] = $boxes;
				}
				else
				{
					if (!isset($posted['content']))
						$posted['content'] = '';
					$posted['content'] = (isset($filepath) && is_file($filepath) && !$posted['content']) ?
						file_get_contents($filepath) :
						$posted['content'];
				}
				$this->owner->vars['content'] = htmlspecialchars(strval($posted['content']));
				$this->owner->vars['title'] = htmlspecialchars(strval($posted['title']));
			}
			$return = $this->owner->parse($nodes, $return);
		}
		else
		{
			$return = $this->owner->getTemplate($list);
			$page = $this->owner->getSection('section page', $return);
			$page = (!empty($page)) ?
				$page[0] :
				'';
			$section = array($this->owner->vars['language']['page'] . $page . ($this->settings['start'] / $this->settings['limit'] + 1));
			$templates = array_diff(scandir($this->owner->config['templates'][$type] . $directory['string']), array('.', '..'));
			$files = array();
			$directories = array();
			if (is_array($templates))
				foreach ($templates as $key => $value)
				{
					$pos = true;
					if ($this->settings['search'] != '')
						$pos = $this->owner->strpos(basename($value, '.' . $filetype), $this->settings['search']);
					if (is_file($filepath = $this->owner->config['templates'][$type] . $directory['string'] . '/' . $value) && $value != basename($value, '.' . $filetype))
					{
						$file = false;
						if ($type == 'glue')
						{
							$glue = $this->owner->explodeUnescape('=', '\\', file_get_contents($filepath));
							foreach ($glue as $value2)
								if ($this->owner->strpos($value2, $this->settings['search']) !== false)
								{
									$file = true;
									break;
								}
						}
						else
							if ($this->owner->strpos(file_get_contents($filepath), $this->settings['search']))
								$file = true;
						if ($pos !== false || $file)
							$files[] = $value;
					}
					elseif (is_dir($this->owner->config['templates'][$type] . $directory['string'] . '/' . $value) && $pos !== false)
						$directories[] = $value;
				}
			natcasesort($files);
			natcasesort($directories);
			$templates = array_merge
			(
				$directories,
				$files
			);
			$templates = ($this->settings['order'] == 'desc') ?
				array_reverse($templates) :
				$templates;
			if (!empty($directory['array']))
				$templates = array_merge
				(
					array('..'),
					$templates
				);
			if ($this->settings['start'] > (($count = count($templates)) - 1) && $this->settings['start'])
				$this->owner->getTemplate($badrequest);
			$this->owner->vars['link'] = $this->pagination($count);
			$iterations = 0;
			$entries = array();
			if (!empty($templates))
			{
				$highlightstart = $this->owner->getSection('section highlightstart', $return);
				$highlightstart = (!empty($highlightstart)) ?
					$highlightstart[0] :
					'';
				$highlightend = $this->owner->getSection('section highlightend', $return);
				$highlightend = (!empty($highlightend)) ?
					$highlightend[0] :
					'';
				foreach ($templates as $value)
				{
					if ($iterations >= $this->settings['start'])
					{
						$title = (is_file($this->owner->config['templates'][$type] . $directory['string'] . '/' . $value)) ?
							basename($value, '.' . $filetype) :
							$value;
						$displaytitle = str_replace(htmlspecialchars($this->settings['search']), $highlightstart . $this->settings['search'] . $highlightend, htmlspecialchars($title));
						$array = $directory['replace'];
						if ($value == '..')
							unset($array[count($array) - 1]);
						$entries[] = array
						(
							array
							(
								array('[displaytitle]', $displaytitle),
								array('[title]', urlencode($title)),
							),
							array
							(
								$this->owner->parseConditional('if file', (is_file($this->owner->config['templates'][$type] . $directory['string'] . '/' . $value)), 'else file'),
								$this->owner->parseConditional('if show', ($value != '..'), 'else show'),
								$this->owner->parseConditional('if checked', ($this->settings['check'])),
								$this->owner->parseLoop('loop entriesdirectories', $array)
							)
						);
					}
					$iterations++;
					if ($iterations == $this->settings['start'] + $this->settings['limit'])
						break;
				}
			}
			$nodes[] = $this->owner->parseConditional('section highlightstart', false);
			$nodes[] = $this->owner->parseConditional('section highlightend', false);
			$nodes[] = $this->owner->parseConditional('section page', false);
			$nodes[] = $this->owner->parseConditional('if entries', (!empty($entries)), 'else entries');
			$nodes[] = $this->owner->parseLoop('loop directories', $directory['replace']);
			$nodes[] = $this->owner->parseLoop('loop entries', $entries);
			$this->owner->vars['count'] = $count;
			$this->owner->vars['listpath'] = $path[1] . $path[3];
			$this->owner->vars['display'] = ($this->settings['start'] / $this->settings['limit']) + 1;
			$this->owner->vars['limit'] = urlencode($this->settings['limit']);
			$this->owner->vars['order'] = urlencode($this->settings['order']);
			$this->owner->vars['search'] = urlencode($this->settings['search']);
			$this->owner->vars['start'] = urlencode($this->settings['start']);
			$return = $this->owner->parse($nodes, $return);
		}
		$array = array();
		foreach ($directory['array'] as $value)
			$array[] = htmlspecialchars($value);
		$section = array_merge
		(
			$array,
			$section
		);
		$return = array($return, $section);
		return $return;
	}

	public function checkWritable($template, $directory, $type, $filetype)
	{
		$return = false;
		$template = strval($template);
		$directory = strval($directory);
		$filetype = strval($filetype);
		if (!is_file($this->owner->config['templates'][$type] . $directory . '/' . $template . '.' . $filetype))
			if (is_writable($this->owner->config['templates'][$type] . $directory))
			{
				@touch($this->owner->config['templates'][$type] . $directory . '/' . $template . '.' . $filetype) or $return = $this->owner->vars['language']['filecouldnotbecreated'];
				@chmod($this->owner->config['templates'][$type] . $directory . '/' . $template . '.' . $filetype, 0666);
			}
			else
				$return = $this->owner->vars['language']['directorynotchmod'];
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
					array('[directory]', urlencode(strval($value))),
					array()
				);
				$return['string'] .= '/' . $value;
				$return['url'] .= '&directory[]=' . $value;
			}
		return $return;
	}

	/**
	http://www.suitframework.com/docs/logistics#howitworks
	**/
	public function logistics()
	{
		return ($this->settings['start'] >= 0 && $this->settings['limit'] > 0 && $this->settings['start'] % $this->settings['limit'] == 0);
	}

	private function pageLink($count, $check, $start, $display, $ahead, $pagelink)
	{
		$return = '';
		$count = intval($count);
		$check = intval($check);
		$start = intval($start);
		$display = strval($display);
		$pagelink = strval($pagelink);
		$path = $this->path(array('check', 'limit', 'order', 'search', 'start'));
		$success = false;
		if ($ahead)
		{
			if ($count - 1 >= $check)
				$success = true;
		}
		else
			if ($check >= 0)
				$success = true;
		if ($start == -1)
			$start = $check;
		if ($success)
		{
			$return = $this->owner->getTemplate($pagelink);
			$nodes = $this->owner->config['parse']['nodes'];
			$nodes[] = $this->owner->parseConditional('if checked', ($this->settings['check']), 'else checked');
			$nodes[] = $this->owner->parseConditional('if current', false, 'else current');
			$this->owner->vars['display'] = $display;
			$this->owner->vars['limit'] = urlencode($this->settings['limit']);
			$this->owner->vars['order'] = urlencode($this->settings['order']);
			$this->owner->vars['path'] = $path[1] . $path[3];
			$this->owner->vars['search'] = urlencode($this->settings['search']);
			$this->owner->vars['start'] = urlencode($start);
			$return = $this->owner->parse($nodes, $return);
		}
		return $return;
	}

	/**
	http://www.suitframework.com/docs/pagination#howitworks
	**/
	public function pagination($count, $pages = NULL, $pagelink = NULL)
	{
		$count = intval($count);
		$pages = intval((isset($pages)) ?
			$pages :
			$this->config['navigation']['pages']);
		$pagelink = strval((isset($pagelink)) ?
			$pagelink :
			$this->config['templates']['pagelink']);
		$exclude = array('check', 'limit', 'order', 'search', 'start');
		$path = $this->path($exclude);
		$return = array();
		$return['current'] = $this->owner->getTemplate($pagelink);
		$nodes = $this->owner->config['parse']['nodes'];
		$nodes[] = $this->owner->parseConditional('if checked', ($this->settings['check']), 'else checked');
		$nodes[] = $this->owner->parseConditional('if current', true, 'else current');
		$this->owner->vars['display'] = ($this->settings['start'] / $this->settings['limit']) + 1;
		$this->owner->vars['limit'] = urlencode($this->settings['limit']);
		$this->owner->vars['order'] = urlencode($this->settings['order']);
		$this->owner->vars['path'] = $path[1] . $path[3];
		$this->owner->vars['search'] = urlencode($this->settings['search']);
		$this->owner->vars['start'] = urlencode($this->settings['start']);
		$return['current'] = $this->owner->parse($nodes, $return['current']);
		$num = $this->reduce($count - 1);
		$array = array();
		$array[] = $this->pageLink($count, ($this->settings['start'] - ($this->settings['limit'] * ($pages + 1))), 0, $this->owner->vars['language']['first'], false, $pagelink);
		for ($x = $pages; $x != 0; $x--)
			$array[] = $this->pageLink($count, ($this->settings['start'] - ($this->settings['limit'] * $x)), -1, (($this->settings['start'] / $this->settings['limit']) - ($x - 1)), false, $pagelink);
		$return['previous'] = implode(' ', $array);
		$array = array();
		for ($x = 1; $x <= $pages; $x++)
			$array[] = $this->pageLink($count, ($this->settings['start'] + ($this->settings['limit'] * $x)), -1, (($this->settings['start'] / $this->settings['limit']) + ($x + 1)), true, $pagelink);
		$array[] = $this->pageLink($count, ($this->settings['start'] + ($this->settings['limit'] * ($pages + 1))), strval($num), $this->owner->vars['language']['last'], true, $pagelink);
		$return['next'] = implode(' ', $array);
		return $return;
	}

	/**
	http://www.suitframework.com/docs/path#howitworks
	**/
	public function path($exclude = array())
	{
		$regular = $_SERVER['SCRIPT_NAME'];
		$url = $_SERVER['SCRIPT_NAME'];
		$querychar = '?';
		$urlquerychar = '?';
		foreach ($_GET as $key => $value)
			if (is_array($exclude))
			{
				if (!in_array($key, $exclude))
				{
					if (is_array($value))
					{
						foreach ($value as $value2)
						{
							$regular .= $querychar . $key . '[]=' . $value2;
							$url .= $urlquerychar . urlencode($key) . '[]=' . urlencode($value2);
							if ($querychar == '?')
							{
								$querychar = '&';
								$urlquerychar = '&amp;';
							}
						}
					}
					else
					{
						$regular .= $querychar . $key . '=' . $value;
						$url .= $urlquerychar . urlencode($key) . '=' . urlencode($value);
						if ($querychar == '?')
						{
							$querychar = '&';
							$urlquerychar = '&amp;';
						}
					}
				}
			}
			else
				$this->owner->error('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5', NULL, 'Warning');
		return array($regular, $url, $querychar, $urlquerychar);
	}

	/**
	http://www.suitframework.com/docs/redirect#howitworks
	**/
	public function redirect($url, $message = '', $refresh = NULL, $redirect = NULL)
	{
		$url = strval($url);
		$message = strval($message);
		$refresh = intval((isset($refresh)) ?
			$refresh :
			$this->config['navigation']['refresh']);
		$redirect = strval((isset($redirect)) ?
			$redirect :
			$this->config['templates']['redirect']);
		$content = $this->owner->getTemplate($redirect);
		if ($refresh)
		{
			$nodes = $this->owner->config['parse']['nodes'];
			$nodes[] = $this->owner->parseConditional('if s', ($refresh != 1));
			$this->owner->vars['seconds'] = $this->owner->vars['language']['seconds'];
			$this->owner->vars['refresh'] = $refresh;
			$this->owner->vars['seconds'] = $this->owner->parse($nodes, $this->owner->vars['seconds']);
			$this->owner->vars['message'] = $message;
			$this->owner->vars['name'] = $this->owner->vars['language']['redirecting'];
			$this->owner->vars['url'] = htmlspecialchars($url);
			$content = $this->owner->parse($nodes, $content);
		}
		else
			$content = '';
		header('refresh: ' . $refresh . '; url=' . $url);
		exit($content);
	}

	/**
	http://www.suitframework.com/docs/reduce#howitworks
	**/
	public function reduce($return, $once = false)
	{
		$return = intval($return);
		if ($return % $this->settings['limit'] || $once)
			do
				$return--;
			while ($return % $this->settings['limit']);
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
}
$suit->getTemplate('tie/config');
$suit->tie = new TIE($suit, $suit->vars['config']);
?>