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
//Exclude these from the querystring.
$exclude = array('cmd', 'boxes', 'file', 'limit', 'orderby', 'search', 'select', 'start');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
if (isset($_GET['section']) && (in_array($_GET['section'], array('code', 'content', 'glue'))))
{
	$name = $suit->tie->language[$_GET['section']];
	//Load the content section of the admin area, and store it in a variable in case anything has to be returned.
	$return = $suit->tie->adminArea($_GET['section']);
	$list = $return[0];
	$section = $return[1];
}
elseif (isset($_GET['section']) && (!strcmp($_GET['section'], 'phpinfo')))
{
	phpinfo();
	exit;
}
else
{
	//No section, so we will do the dashboard.
	$list = $suit->getTemplate('admin_dashboard');
	$latesttieversion = file_get_contents('http://suitframework.sourceforge.net/version.txt');
	$array = array_merge
	(
		$suit->tie->parseConditional('if currenttieversion', (strcmp($suit->tie->version, $latesttieversion)), $list, 'else currenttieversion'),
		$suit->tie->parseConditional('if fileuploads', (ini_get('file_uploads')), $list, 'else fileuploads'),
		$suit->tie->parseConditional('if magicquotesgpc', (ini_get('magic_quotes_gpc')), $list, 'else magicquotesgpc'),
		$suit->tie->parseConditional('if magicquotesruntime', (ini_get('magic_quotes_runtime')), $list, 'else magicquotesruntime'),
		$suit->tie->parseConditional('if magicquotessybase', (ini_get('magic_quotes_sybase')), $list, 'else magicquotessybase'),
		$suit->tie->parseConditional('if registerglobals', (ini_get('register_globals')), $list, 'else registerglobals')
	);
	//Parse all of the conditionals that display 'On/Off' depending on the setting values.
	$list = $suit->tie->replace($array, $list);
	$array = array
	(
		array('<currenttieversion>', $suit->tie->version),
		array('<latesttieversion>', $latesttieversion),
		array('<path>', $path),
		array('<post_max_size>', ini_get('post_max_size')),
		array('<phpversion>', PHP_VERSION),
		array('<servertype>', PHP_OS),
		array('<upload_max_filesize>', ini_get('upload_max_filesize'))
	);
	$list = $suit->tie->replace($array, $list);
	$name = $suit->tie->language['dashboard'];
	$section = $suit->tie->language['main'];
}
$array = array
(
	array('<admin>', $list),
	array('<name>', $name . ' - ' . $section),
	array('<section>', $section)
);
$output = $suit->tie->replace($array, $output);
?>