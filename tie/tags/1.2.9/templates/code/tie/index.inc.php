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
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], array('cmd', 'file', 'limit', 'order', 'search', 'select', 'start'));
$separator = $suit->tie->getSection('section separator', $content);
$separator = (!empty($separator)) ?
	$separator[0] :
	'';
if (isset($_GET['section']) && (in_array($_GET['section'], array('code', 'content', 'glue'))))
{
	$content = $suit->tie->replace($suit->tie->parseConditional('if dashboard', false, $content, 'else dashboard'), $content);
	$return = $suit->tie->adminArea($_GET['section']);
	$list = $return[0];
	$section = array_merge
	(
		array($suit->tie->language[$_GET['section']]),
		$return[1]
	);
	$section = implode($separator, $section);
	$array = array
	(
		array
		(
			array('<admin>', $list),
			array('<name>', $suit->tie->language['admin'] . $separator . $section),
			array('<section>', $section)
		)
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
	$section = $suit->tie->language['dashboard'];
	if (isset($_GET['version']) && ($_GET['version'] == 'true'))
	{
		$content = $suit->getTemplate('tie/version');
		$fetchversion = @file_get_contents('http://suitframework.sourceforge.net/version.txt');
		$version = ($fetchversion) ?
			$fetchversion :
			$suit->tie->language['na'];
		$array = array
		(
			$suit->tie->parseConditional('if currenttieversion', (strcmp($suit->tie->version, $version)), $content),
			array_merge
			(
				array
				(
					array('<version>', $version)
				),
				$suit->tie->parseConditional('section separator', false, $content)
			)
		);
		$content = $suit->tie->replace($array, $content);
		exit($content);
	}
	$array = array
	(
		$suit->tie->parseConditional('if dashboard', true, $content, 'else dashboard'),
		array_merge
		(
			$suit->tie->parseConditional('if fileuploads', (ini_get('file_uploads')), $content, 'else fileuploads'),
			$suit->tie->parseConditional('if magicquotesgpc', (ini_get('magic_quotes_gpc')), $content, 'else magicquotesgpc'),
			$suit->tie->parseConditional('if magicquotesruntime', (ini_get('magic_quotes_runtime')), $content, 'else magicquotesruntime'),
			$suit->tie->parseConditional('if magicquotessybase', (ini_get('magic_quotes_sybase')), $content, 'else magicquotessybase'),
			$suit->tie->parseConditional('if registerglobals', (ini_get('register_globals')), $content, 'else registerglobals')
		),
		array
		(
			array('<currenttieversion>', $suit->tie->version),
			array('<name>', $suit->tie->language['admin'] . $separator . $section),
			array('<path>', htmlentities($path)),
			array('<post_max_size>', ini_get('post_max_size')),
			array('<phpversion>', PHP_VERSION),
			array('<section>', $section),
			array('<servertype>', PHP_OS),
			array('<upload_max_filesize>', ini_get('upload_max_filesize'))
		)
	);
}
$array[] = $suit->tie->parseConditional('section separator', false, $content);
$content = $suit->tie->replace($array, $content);
?>