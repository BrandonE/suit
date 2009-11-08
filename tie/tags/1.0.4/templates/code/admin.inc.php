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
foreach ($vars as $value)
{	
	$suit->tie->vars[$value] = &$$value;
}
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parsePhrases($output);
$output = $suit->tie->parseTemplates($output);
$exclude = array('cmd', 'file', 'limit', 'orderby', 'search', 'select', 'start');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$suit->tie->navigation->logistics();
$error = '';
if (!empty($_POST))
{
	$redirect = $path . 'start=' . $suit->tie->navigation->settings['start'] . '&limit=' . $suit->tie->navigation->settings['limit'] . '&orderby=' . $suit->tie->navigation->settings['orderby_type'] . '&search=' . $suit->tie->navigation->settings['search'];
}
if (isset($_GET['section']) && (!strcmp($_GET['section'], 'content')))
{
	$name = $suit->tie->language['content'];
	if (isset($_POST['import']) && isset($_FILES['file']))
	{
		$error = $suit->tie->xmlImporter('content', $redirect);
	}
	elseif ((isset($_GET['cmd']) && ($_GET['cmd'] == 'export')) || (isset($_POST['exportselected']) && is_array($_POST['entry'])))
	{
		$suit->tie->xmlExporter('content');
	}
	elseif (isset($_POST['deleteselected']) && isset($_POST['entry']) && is_array($_POST['entry']))
	{
		$get = implode('&file[]=', $_POST['entry']); //Implode the array into comma separated values, for explosion later in the $_GET
		$suit->tie->navigation->redirect('', 0, $path . 'start=' . $suit->tie->navigation->settings['start'] . '&limit=' . $suit->tie->navigation->settings['limit'] . '&orderby=' . $suit->tie->navigation->settings['orderby_type'] . '&search=' . $suit->tie->navigation->settings['search'] . '&cmd=delete&file[]=' . $get);
	}
	$pages = array('add', 'edit', 'delete', 'export'); //Valid pages that can be requested.
	if (isset($_GET['cmd']) && (in_array($_GET['cmd'], $pages)))
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
			$error = $suit->tie->addFile('content', $posted, $redirect);
		}
		elseif (isset($_POST['edit']) && !strcmp($status, 0))
		{
			$error = $suit->tie->editFile('content', $posted, $redirect);
		}
		elseif (isset($_POST['delete']) && isset($posted['title']))
		{
			$error = $suit->tie->deleteFile('content', $posted, $redirect);
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
			$list = $suit->getTemplate('admin_form_content');
			if (!$error)
			{
				$error = '';
				$posted['title'] = '';
				$posted['content'] = '';
				$posted['code'] = '';
				//Template Cloning
				if (isset($_GET['file']) && ($_GET['file']))
				{
					$posted['title'] = $_GET['file'];
					$filepath = $suit->templates . '/content/' . $_GET['file'] . '.tpl';
					if (file_exists($filepath))
					{
						$posted['content'] = file_get_contents($filepath);
					}
				}
			}
			$array = array
			(
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
			if (!isset($_GET['file']))
			{
				$suit->getTemplate('badrequest');
			}
			$filepath = $suit->templates . '/content/' . $_GET['file'] . '.tpl';
			if (!file_exists($filepath))
			{
				$suit->getTemplate('badrequest');
			}
			$list = $suit->getTemplate('admin_form_content');
			$posted['content'] = '';
			if (!$error)
			{
				$posted['content'] = file_get_contents($filepath);
			}
			$array = array
			(
				array('<content>', htmlentities($suit->tie->firstLine($posted['content']))),
				array('<error>', $error),
				array('<oldtitle>', $_GET['file']),
				array('<name>', 'edit'),
				array('<title>', htmlentities($_GET['file'])),
				array('<value>', $suit->tie->language['edit'])
			);
			$list = $suit->tie->replace($list, $array);
			$section = $suit->tie->language['edit'];
		}
		elseif (!strcmp($_GET['cmd'], 'delete'))
		{
			if (!isset($_GET['file']))
			{
				//You have to specify an ID.
				$suit->getTemplate('badrequest');
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
				$filepath = $suit->templates . '/content/' . $value . '.tpl';
				if (file_exists($filepath))
				{
					$rows[] = $value;
					$ids .= str_replace('<title>', $value, $suit->getTemplate('admin_delete_input'));
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
			$list = $suit->tie->replace($suit->getTemplate('admin_delete'), $array);
			$section = $suit->tie->language['delete'];
		}
	}
	else
	{
		$list = $suit->getTemplate('admin_list');
		$entry = $suit->getTemplate('admin_list_entry');
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
			if ($value == '.' || $value == '..' || $value == (basename($value, '.tpl')) || !$preg)
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
}
elseif (isset($_GET['section']) && (!strcmp($_GET['section'], 'code')))
{
	$name = $suit->tie->language['code'];
	if (isset($_GET['cmd']) && (!strcmp($_GET['cmd'], 'view')))
	{
		if (!isset($_GET['file']))
		{
			$suit->getTemplate('badrequest');
		}
		$filepath = $suit->templates . '/code/' . $_GET['file'] . '.inc.php';
		if (!file_exists($filepath))
		{
			$suit->getTemplate('badrequest');
		}
		$list = $suit->getTemplate('admin_form_code');
		$array = array
		(
			array('<code>', htmlentities($suit->tie->firstLine(file_get_contents($filepath)))),
			array('<title>', htmlentities($_GET['file']))
		);
		$list = $suit->tie->replace($list, $array);
		$section = $suit->tie->language['edit'];
	}
	else
	{
		$list = $suit->getTemplate('admin_list_code');
		$entry = $suit->getTemplate('admin_list_entry_code');
		//Create an empty variable for when we have to concatenate results to this.
		$entries = '';
		$templates = scandir($suit->templates . '/code');
		foreach ($templates as $key => $value)
		{
			$preg = array('NULL');
			if ($suit->tie->navigation->settings['search'])
			{
				preg_match_all('/(.*?)' . $suit->tie->navigation->settings['search'] . '(.*?)/', basename($value, '.inc.php'), $preg, PREG_SET_ORDER);
			}
			if ($value == '.' || $value == '..' || $value == (basename($value, '.inc.php')) || !$preg)
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
					$title = htmlspecialchars(basename($value, '.inc.php'));
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
}
elseif (isset($_GET['section']) && (!strcmp($_GET['section'], 'glue')))
{
	$name = $suit->tie->language['glue'];
	if (isset($_POST['import']) && isset($_FILES['file']))
	{
		$error = $suit->tie->xmlImporter('glue', $redirect);
	}
	elseif ((isset($_GET['cmd']) && ($_GET['cmd'] == 'export')) || (isset($_POST['exportselected']) && is_array($_POST['entry'])))
	{
		$suit->tie->xmlExporter('glue');
	}
	elseif (isset($_POST['deleteselected']) && isset($_POST['entry']) && is_array($_POST['entry']))
	{
		$get = implode('&file[]=', $_POST['entry']); //Implode the array into comma separated values, for explosion later in the $_GET
		$suit->tie->navigation->redirect('', 0, $path . 'start=' . $suit->tie->navigation->settings['start'] . '&limit=' . $suit->tie->navigation->settings['limit'] . '&orderby=' . $suit->tie->navigation->settings['orderby_type'] . '&search=' . $suit->tie->navigation->settings['search'] . '&cmd=delete&file[]=' . $get);
	}
	$pages = array('add', 'edit', 'delete'); //Valid pages that can be requested.
	if (isset($_GET['cmd']) && (in_array($_GET['cmd'], $pages)))
	{
		$post = array
		(
			'code',
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
			$error = $suit->tie->addFile('glue', $posted, $redirect);
		}
		elseif (isset($_POST['edit']) && !strcmp($status, 0))
		{
			$error = $suit->tie->editFile('glue', $posted, $redirect);
		}
		elseif (isset($_POST['delete']) && isset($posted['title']))
		{
			$error = $suit->tie->deleteFile('glue', $posted, $redirect);
		}
		if ($error)
		{
			$error = '<p>' . $error . '</p>';
		}
		$output = $suit->tie->parseTemplates($output);	
		if (!strcmp($_GET['cmd'], 'add'))
		{
			$list = $suit->getTemplate('admin_form_glue');
			$posted['title'] = '';
			$posted['content'] = '';
			$posted['code'] = '';
			//Template Cloning
			if (isset($_GET['file']) && ($_GET['file']))
			{
				$posted['title'] = $_GET['file'];
				$filepath = $suit->templates . '/glue/' . $_GET['file'] . '.txt';
				if (file_exists($filepath))
				{
					$array = explode("\n", file_get_contents($filepath), 2);
					if (isset($array[0]))
					{
						$posted['content'] = $array[0];
					}
					if (isset($array[1]))
					{
						$posted['code'] = $array[1];
					}
				}
			}
			$array = array
			(
				array('<code>', htmlentities($posted['code'])),
				array('<content>', htmlentities($posted['content'])),
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
			if (!isset($_GET['file']))
			{
				$suit->getTemplate('badrequest');
			}
			$filepath = $suit->templates . '/glue/' . $_GET['file'] . '.txt';
			if (!file_exists($filepath))
			{
				$suit->getTemplate('badrequest');
			}
			$list = $suit->getTemplate('admin_form_glue');
			$posted['content'] = '';
			$posted['code'] = '';
			$array = explode("\n", file_get_contents($filepath), 2);
			if (isset($array[0]))
			{
				$posted['content'] = $array[0];
			}
			if (isset($array[1]))
			{
				$posted['code'] = $array[1];
			}
			$array = array
			(
				array('<code>', htmlentities($posted['code'])),
				array('<content>', htmlentities($posted['content'])),
				array('<error>', $error),
				array('<oldtitle>', $_GET['file']),
				array('<name>', 'edit'),
				array('<title>', htmlentities($_GET['file'])),
				array('<value>', $suit->tie->language['edit'])
			);
			$list = $suit->tie->replace($list, $array);
			$section = $suit->tie->language['edit'];
		}
		elseif (!strcmp($_GET['cmd'], 'delete'))
		{
			if (!isset($_GET['file']))
			{
				//You have to specify an ID.
				$suit->getTemplate('badrequest');
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
				$filepath = $suit->templates . '/glue/' . $value . '.txt';
				if (file_exists($filepath))
				{
					$rows[] = $value;
					$ids .= str_replace('<title>', $value, $suit->getTemplate('admin_delete_input'));
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
			$list = $suit->tie->replace($suit->getTemplate('admin_delete'), $array);
			$section = $suit->tie->language['delete'];
		}
	}
	else
	{
		$list = $suit->getTemplate('admin_list');
		$entry = $suit->getTemplate('admin_list_entry');
		//Create an empty variable for when we have to concatenate results to this.
		$entries = '';
		$templates = scandir($suit->templates . '/glue');
		foreach ($templates as $key => $value)
		{
			$preg = array('NULL');
			if ($suit->tie->navigation->settings['search'])
			{
				preg_match_all('/(.*?)' . $suit->tie->navigation->settings['search'] . '(.*?)/', basename($value, '.txt'), $preg, PREG_SET_ORDER);
			}
			if ($value == '.' || $value == '..' || $value == (basename($value, '.txt')) || !$preg)
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
					$title = htmlspecialchars(basename($value, '.txt'));
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
foreach ($vars as $value)
{	
	$suit->tie->vars[$value] = &$$value;
}
?>