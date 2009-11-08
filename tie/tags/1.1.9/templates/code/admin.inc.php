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
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], array('boxes', 'cmd', 'file', 'limit', 'order', 'search', 'select', 'start'));
$separator = $suit->tie->getSection('section separator', $content);
$separator = (!empty($separator)) ?
	$separator[0] :
	'';
$content = $suit->tie->replace($suit->tie->parseConditional('section separator', false, $content), $content);
if (isset($_GET['section']) && (in_array($_GET['section'], array('code', 'content', 'glue'))))
{
	$content = $suit->tie->replace($suit->tie->parseConditional('if dashboard', false, $content, 'else dashboard'), $content);
	//Load the content section of the admin area, and store it in a variable in case anything has to be returned.
	$return = $suit->tie->adminArea($_GET['section']);
	$list = $return[0];
	$section = $return[1];
	$name = $suit->tie->language[$_GET['section']] . $separator . $section;
	$array = array
	(
		array('<admin>', $list),
		array('<name>', $suit->tie->language['admin'] . $separator . $name),
		array('<section>', $section)
	);
	$content = $suit->tie->replace($array, $content);
}
elseif (isset($_GET['section']) && (!strcmp($_GET['section'], 'phpinfo')))
{
	phpinfo();
	exit;
}
else
{
	$content = $suit->tie->replace($suit->tie->parseConditional('if dashboard', true, $content, 'else dashboard'), $content);
	$fetchversion = @file_get_contents('http://suitframework.sourceforge.net/version.txt');
	$latesttieversion = ($fetchversion) ?
		$fetchversion :
		$suit->tie->language['na'];
	$array = array_merge
	(
		$suit->tie->parseConditional('if currenttieversion', (strcmp($suit->tie->version, $latesttieversion)), $content, 'else currenttieversion'),
		$suit->tie->parseConditional('if fileuploads', (ini_get('file_uploads')), $content, 'else fileuploads'),
		$suit->tie->parseConditional('if magicquotesgpc', (ini_get('magic_quotes_gpc')), $content, 'else magicquotesgpc'),
		$suit->tie->parseConditional('if magicquotesruntime', (ini_get('magic_quotes_runtime')), $content, 'else magicquotesruntime'),
		$suit->tie->parseConditional('if magicquotessybase', (ini_get('magic_quotes_sybase')), $content, 'else magicquotessybase'),
		$suit->tie->parseConditional('if registerglobals', (ini_get('register_globals')), $content, 'else registerglobals')
	);
	//Parse all of the conditionals that display 'On/Off' depending on the setting values.
	$content = $suit->tie->replace($array, $content);
	$section = $suit->tie->language['dashboard'];
	$name = $section;
	$array = array
	(
		array('<currenttieversion>', $suit->tie->version),
		array('<latesttieversion>', $latesttieversion),
		array('<name>', $suit->tie->language['admin'] . $separator . $name),
		array('<path>', htmlentities($path)),
		array('<post_max_size>', ini_get('post_max_size')),
		array('<phpversion>', PHP_VERSION),
		array('<section>', $section),
		array('<servertype>', PHP_OS),
		array('<upload_max_filesize>', ini_get('upload_max_filesize'))
	);
	$content = $suit->tie->replace($array, $content);
}
?>