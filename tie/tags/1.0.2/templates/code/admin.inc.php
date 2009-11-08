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

Copyright (C) 2008-2009 The TIE Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$suit->getTemplate('tie');
$isadmin = true;
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{	
	$suit->tie->vars[$var_name] = &$$var_name;
}
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parsePhrases($output);
$output = $suit->tie->parseTemplates($output);
$pages = array('add', 'edit', 'delete'); //Valid pages that can be requested.
$exclude = array('cmd', 'limit', 'orderby', 'search', 'select', 'start', 'template');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$suit->tie->navigation->logistics();
if (isset($_GET['cmd']))
{
	$name = $suit->tie->language['templates'];
	$error = '';
	if ($_GET['cmd'] == 'export' || (!strcmp($_GET['cmd'], 'templates') && isset($_POST['exportselected']) && is_array($_POST['entry'])))
	{
		$id = (isset($_GET['cmd']) && !strcmp($_GET['cmd'], 'export')) ? $_GET['template'] : array_map('intval', $_POST['entry']);
		//Check if the provided value is an array.
		if (!is_array($id))
		{
			//Turn it into a one-dimensional array with that single ID as an element.
			$id = array($id);
		}
		$templates = '';
		foreach ($id as $value)
		{
			$filepath = $suit->checkFile($value);
			if ($filepath)
			{
				$content = file_get_contents($filepath['content']);
				$code = file_get_contents($filepath['code']);
				$array = array
				(
					array('<contenttoken>', htmlentities($content)),
					array('<titletoken>', htmlentities($value))
				);
				$templates .= $suit->tie->replace($suit->getTemplate('xml_entry'), $array);
			}
		}
		if (!$templates)
		{
			//Those templates do not exist.
			$suit->getTemplate('badrequest');
		}
		$xml = str_replace('<list>', $templates, $suit->getTemplate('xml')); //Have the replacement done, for output afterwards.
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename=templates.xml');
		header('Content-Length: ' . strlen($xml));
		exit($xml); //Output the XML; it will prompt for a download, because of the headers supplied.
	}
	if (!empty($_POST))
	{
		$redirect = $path . 'cmd=templates&start=' . $suit->tie->navigation->settings['start'] . '&limit=' . $suit->tie->navigation->settings['limit'] . '&orderby=' . $suit->tie->navigation->settings['orderby_type'] . '&search=' . $suit->tie->navigation->settings['search'];
	}
}
if (isset($_GET['cmd']) && (!strcmp($_GET['cmd'], 'templates')))
{
	if (isset($_POST['deleteselected']) && isset($_POST['entry']) && is_array($_POST['entry']))
	{
		$get = implode('&template[]=', $_POST['entry']); //Implode the array into comma separated values, for explosion later in the $_GET
		$suit->tie->navigation->redirect('', 0, $path . 'start=' . $suit->tie->navigation->settings['start'] . '&limit=' . $suit->tie->navigation->settings['limit'] . '&orderby=' . $suit->tie->navigation->settings['orderby_type'] . '&search=' . $suit->tie->navigation->settings['search'] . '&cmd=delete&template[]=' . $get);
	}
	elseif (isset($_POST['import']) && isset($_FILES['file']))
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
					if (isset($row->title) && isset($row->content))
					{
						$filepath = $suit->checkFile($row->title);
						if (!$filepath['content'] || ($_POST['overwrite'] && is_writable($filepath['content'])))
						{
							$error = $suit->tie->checkWritable($row->title);
							if (!$error)
							{
								$filepath = $suit->checkFile($row->title);
								//OS specific linebreak conversions to avoid linebreaking issues.
								$char = $suit->tie->breakConvert($row->content, PHP_OS);
								$content = preg_replace('/(\\r\\n)|\\r|\\n/', $char, $row->content);
								file_put_contents($filepath['content'], $content); //Write the contents to the file.
							}
						}
						else
						{
							$error = $suit->tie->language['filenotchmod'];
						}
					}
				}
			}
		}
		else
		{
			$error = $suit->tie->language['simplexmlfail'];
		}
		if (!$error)
		{
			//The import is now complete.
			$suit->tie->navigation->redirect($suit->tie->language['importedsuccessfully'], $suit->tie->config['redirect']['interval'], $redirect);
		}
		else
		{
			$error = '<center><p>' . $error . '</p></center>';
		}
	}
	$list = $suit->getTemplate('admin_templates');
	$entry = $suit->getTemplate('admin_templates_entry');
	//Create an empty variable for when we have to concatenate results to this.
	$entries = '';
	$templates = scandir($suit->templates . '/content');
	foreach ($templates as $key => $value)
	{
		$preg = array('NULL');
		if ($suit->tie->navigation->settings['search'])
		{
			preg_match_all('/(.*?)' . $suit->tie->navigation->settings['search'] . '(.*?)/', basename($value, '.tpl'), $preg, PREG_SET_ORDER);
		}
		if ($value == '.' || $value == '..' || !$preg)
		{
			unset($templates[$key]);
		}
	}
	$count = count($templates);
	if ($suit->tie->navigation->settings['start'] && ($suit->tie->navigation->settings['start'] > $count-1))
	{
		$suit->getTemplate('badrequest');
	}
	if ($suit->tie->navigation->settings['orderby_type'] == 'asc')
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
			if ($iterations >= $suit->tie->navigation->settings['start'])
			{
				$title = htmlspecialchars(basename($value, '.tpl'));
				$checked = ($suit->tie->navigation->settings['select']) ? ' checked' : '';
				$array = array
				(
					array('<checked>', $checked),
					array('<limit>', $suit->tie->navigation->settings['limit']),
					array('<orderby>', $suit->tie->navigation->settings['orderby_type']),
					array('<path>', htmlentities($path)),
					array('<search>', $suit->tie->navigation->settings['search']),
					array('<start>', $suit->tie->navigation->settings['start']),
					array('<title>', $title)
				);
				$entries .= $suit->tie->replace($entry, $array);
			}
			$iterations++;
			if ($iterations == $suit->tie->navigation->settings['start'] + $suit->tie->navigation->settings['limit'])
			{
				break;
			}
		}
	}
	$link = $suit->tie->navigation->pagination($count);
	//Finalize replacements for the list.
	$array = array
	(
		array('<1>', $link[2]),
		array('<2>', $link[3]),
		array('<3>', $link[4]),
		array('<4>', $link[5]),
		array('<5>', $link[6]),
		array('<entries>', $entries),
		array('<cmd>', $_GET['cmd']),
		array('<count>', $count),
		array('<error>', $error),
		array('<First>', $link[1]),
		array('<Last>', $link[7]),
		array('<limit>', $suit->tie->navigation->settings['limit']),
		array('<orderby>', $suit->tie->navigation->settings['orderby_type']),
		array('<path>', htmlentities($path)),
		array('<search>', $suit->tie->navigation->settings['search']),
		array('<start>', $suit->tie->navigation->settings['start'])
	);
	$list = $suit->tie->replace($list, $array);
	$section = $suit->tie->language['page'] . ' ' . preg_replace('/\<[a](.*)\>(.*)\\<\/[a]\>/', '$2', $link[4]);
}
elseif (isset($_GET['cmd']) && (in_array($_GET['cmd'], $pages)))
{
	$post = array
	(
		'content',
		'oldtitle',
		'title'
	);
	$posted = array();
	$status = 0;
	foreach ($post as $value)
	{
		if (isset($_POST[$value]))
		{
			$posted[$value] = $_POST[$value];
		}
		else
		{
			$status = (!$status && ($value == 'id')) ? 1 : 2;
		}
	}
	if (isset($_POST['add']) && $status != 2)
	{
		$filepath = $suit->checkFile($posted['title']);
		if (!$filepath['content'])
		{
			if (!strcmp($posted['title'], ''))
			{
				//No title specified.
				$error = $suit->tie->language['missingtitle'];
			}
		}
		else
		{
			//Duplicate title
			$error = $suit->tie->language['duplicatetitle'];
		}
		if (!$error)
		{
			$error = $suit->tie->checkWritable($posted['title']);
			if (!$error)
			{
				$filepath = $suit->checkFile($posted['title']);
				$char = $suit->tie->breakConvert($posted['content'], PHP_OS);
				$posted['content'] = preg_replace('/(\\r\\n)|\\r|\\n/', $char, $posted['content']);
				file_put_contents($filepath['content'], $posted['content']);
				$suit->tie->navigation->redirect($suit->tie->language['addedsuccessfully'], $suit->tie->config['redirect']['interval'], $redirect);
			}
		}
	}
	elseif (isset($_POST['edit']) && !strcmp($status, 0))
	{
		$filepath = $suit->checkFile($posted['oldtitle']);
		if ($filepath['content'])
		{
			$filepath2 = $suit->checkFile($posted['title']);
			if (!$filepath2['content'] || $posted['title'] == $posted['oldtitle'])
			{
				if (!strcmp($posted['title'], ''))
				{
					$error = $suit->tie->language['missingtitle'];
				}
			}
			else
			{
				$error = $suit->tie->language['duplicatetitle'];
			}
		}
		else
		{
			$suit->getTemplate('badrequest');
		}
		if (!$error)
		{
			$error = $suit->tie->checkWritable($posted['title']);
			if (!$error)
			{
				$filepath2 = $suit->checkFile($posted['title']);
				if (is_writable($filepath2['content']))
				{
					$char = $suit->tie->breakConvert($posted['content'], PHP_OS);
					$posted['content'] = preg_replace('/(\\r\\n)|\\r|\\n/', $char, $posted['content']);
					file_put_contents($filepath2['content'], $posted['content']);
					if ($posted['title'] != $posted['oldtitle'])
					{
						unlink($filepath['content']);
					}
					$suit->tie->navigation->redirect($suit->tie->language['editedsuccessfully'], $suit->tie->config['redirect']['interval'], $redirect);
				}
				else
				{
					$error = $suit->tie->language['filenotchmod'];
				}
			}
		}
	}
	elseif (isset($_POST['delete']) && isset($posted['title']))
	{
		foreach ($posted['title'] as $value)
		{
			$filepath = $suit->checkFile($value);
			if ($filepath['content'])
			{
				if (!is_writable($filepath['content']))
				{
					$error = $suit->tie->language['filenotchmod'];
				}
			}
			else
			{
				$suit->getTemplate('badrequest');
			}
		}
		if (!$error)
		{
			foreach ($posted['title'] as $value)
			{
				$filepath = $suit->checkFile($value);
				unlink($filepath['content']);
			}
			$templates = scandir($suit->templates . '/content');
			if (count($templates) % $suit->tie->navigation->settings['limit'] == 0)
			{
				$redirect = $suit->getTemplate('path_url') . '/index.php?page=admin_templates&start=' . intval($suit->tie->navigation->settings['start'] - $suit->tie->navigation->settings['limit']) . '&limit=' . $suit->tie->navigation->settings['limit'] . '&orderby=' . $suit->tie->navigation->settings['orderby_type'] . '&search=' . $suit->tie->navigation->settings['search'];
			}
			$suit->tie->navigation->redirect($suit->tie->language['deletedsuccessfully'], $suit->tie->config['redirect']['interval'], $redirect);
		}
	}
	elseif (isset($_POST['escape']) && !strcmp($status, 0))
	{
		$array = array
		(
			array('{', '{openingbrace}'),
			array('}', '{closingbrace}'),
			array('[', '{openingbracket}'),
			array(']', '{closingbracket}'),
			array('(', '{openingparenthesis}'),
			array(')', '{closingparenthesis}')
		);
		$posted['content'] = $suit->tie->replace($posted['content'], $array);
		$error = $suit->tie->language['escaped'];
	}
	if ($error)
	{
		$error = '<p>' . $error . '</p>';
	}
	$output = $suit->tie->parseTemplates($output);	
	if (!strcmp($_GET['cmd'], 'add'))
	{
		$list = $suit->getTemplate('admin_templates_form');
		if (!(isset($error) && ($error)))
		{
			$error = '';
			$posted['title'] = '';
			$posted['content'] = '';
			$posted['code'] = '';
			//Template Cloning
			if (isset($_GET['template']) && ($_GET['template']))
			{
				$filepath = $suit->checkFile($_GET['template']);
				if ($filepath)
				{
					$posted['title'] = $_GET['template'];
					$posted['content'] = file_get_contents($filepath['content']);
				}
			}
		}
		$array = array
		(
			array('<code>', htmlentities($suit->tie->firstLine($posted['code']))),
			array('<content>', htmlentities($suit->tie->firstLine($posted['content']))),
			array('<error>', $error),
			array('<name>', 'add'),
			array('<title>', htmlentities($posted['title'])),
			array('<value>', $suit->tie->language['add'])
		);
		$list = $suit->tie->replace($list, $array);
		$section = $suit->tie->language['add'];
	}
	elseif (!strcmp($_GET['cmd'], 'edit'))
	{
		if (!isset($_GET['template']))
		{
			$suit->getTemplate('badrequest');
		}
		$filepath = $suit->checkFile($_GET['template']);
		if (!$filepath)
		{
			$suit->getTemplate('badrequest');
		}
		$list = $suit->getTemplate('admin_templates_form');
		if (!(isset($error) && ($error)))
		{
			$error = '';
			$posted['content'] = file_get_contents($filepath['content']);
			$posted['code'] = file_get_contents($filepath['code']);
		}
		$array = array
		(
			array('<error>', $error),
			array('<code>', htmlentities($suit->tie->firstLine($posted['code']))),
			array('<content>', htmlentities($suit->tie->firstLine($posted['content']))),
			array('<oldtitle>', $_GET['template']),
			array('<name>', 'edit'),
			array('<title>', htmlentities($_GET['template'])),
			array('<value>', $suit->tie->language['edit'])
		);
		$list = $suit->tie->replace($list, $array);
		$section = $suit->tie->language['edit'];
	}
	elseif (!strcmp($_GET['cmd'], 'delete'))
	{
		if (!isset($_GET['template']))
		{
			//You have to specify an ID.
			$suit->getTemplate('badrequest');
		}
		$id = $_GET['template'];
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
			if ($suit->checkFile($value))
			{
				$rows[] = $value;
				$ids .= str_replace('<template>', $value, $suit->getTemplate('admin_templates_delete_input'));
			}
		}
		if (empty($rows))
		{
			//No template(s) found.
			$suit->getTemplate('badrequest');
		}
		$error = str_replace('<name>', implode(', ', $rows), $error . '<p>' . $suit->tie->language['deleteconfirm'] . '</p>');
		$array = array
		(
			array('<error>', $error),
			array('<id>', $ids),
			array('<name>', 'delete')
		);
		$list = $suit->tie->replace($suit->getTemplate('admin_templates_delete'), $array);
		$section = $suit->tie->language['delete'];
	}
}
else
{
	$list = $suit->getTemplate('admin_dashboard');
	$latesttieversion = file_get_contents('http://www.suitframework.com/index.php?page=version');
	$array = array
	(
		array('<currenttieversion>', (!strcmp($suit->tie->version, $latesttieversion)) ? $suit->tie->version : '<strong style="color: red;">' . $suit->tie->version . '</span>'),
		array('<file_uploads>', (ini_get('file_uploads')) ? $suit->tie->language['on'] : $suit->tie->language['off']),
		array('<latesttieversion>', $latesttieversion),
		array('<magic_quotes_gpc>', (ini_get('magic_quotes_gpc')) ? '<strong style="color: red;">' . $suit->tie->language['on'] . '</span>' : $suit->tie->language['off']),
		array('<magic_quotes_sybase>', (ini_get('magic_quotes_sybase')) ? '<strong style="color: red;">' . $suit->tie->language['on'] . '</span>' : $suit->tie->language['off']),
		array('<magic_quotes_runtime>', (ini_get('magic_quotes_runtime')) ? '<strong style="color: red;">' . $suit->tie->language['on'] . '</span>' : $suit->tie->language['off']),
		array('<post_max_size>', ini_get('post_max_size')),
		array('<phpversion>', PHP_VERSION),
		array('<register_globals>', (ini_get('register_globals')) ? '<strong style="color: red;">' . $suit->tie->language['on'] . '</span>' : $suit->tie->language['off']),
		array('<servertype>', PHP_OS),
		array('<simplexml_installed>', (class_exists('SimpleXMLElement')) ? $suit->tie->language['on'] : '<strong style="color: red;">' . $suit->tie->language['off'] . '</span>'),
		array('<upload_max_filesize>', ini_get('upload_max_filesize'))
	);
	$list = $suit->tie->replace($list, $array);
	$name = $suit->tie->language['dashboard'];
	$section = $suit->tie->language['main'];
}
$array = array
(
	array('<admin>', $list),
	array('<name>', $name . ' - ' . $section),
	array('<section>', $section)
);
$output = $suit->tie->replace($output, $array);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{	
	$suit->tie->vars[$var_name] = &$$var_name;
}
?>