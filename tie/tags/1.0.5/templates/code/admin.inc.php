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
$redirect = (!empty($_POST)) ? $path . 'start=' . $suit->tie->navigation->settings['start'] . '&limit=' . $suit->tie->navigation->settings['limit'] . '&orderby=' . $suit->tie->navigation->settings['orderby_type'] . '&search=' . $suit->tie->navigation->settings['search'] : '';
if (isset($_GET['section']) && (!strcmp($_GET['section'], 'content')))
{
	//Create an array with the fields that we want to collect from the form data.
	$post = array
	(
		'content',
		'oldtitle',
		'title'
	);
	$name = $suit->tie->language['content'];
	$return = $suit->tie->adminArea($path, $redirect, $post, 'content');
	$list = $return[0];
	$section = $return[1];
}
elseif (isset($_GET['section']) && (!strcmp($_GET['section'], 'code')))
{
	$name = $suit->tie->language['code'];
	$return = $suit->tie->adminArea($path, $redirect, '', 'code');
	$list = $return[0];
	$section = $return[1];
}
elseif (isset($_GET['section']) && (!strcmp($_GET['section'], 'glue')))
{
	//Create an array with the fields that we want to collect from the form data.
	$post = array
	(
		'code',
		'content',
		'oldtitle',
		'title'
	);
	$name = $suit->tie->language['glue'];
	$return = $suit->tie->adminArea($path, $redirect, $post, 'glue');
	$list = $return[0];
	$section = $return[1];
}
else
{
	$list = $suit->getTemplate('admin_dashboard');
	$latesttieversion = file_get_contents('http://suitframework.sourceforge.net/version.txt');
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