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
$nodes = $suit->config['parse']['nodes'];
$path = $suit->tie->path(array('cmd', 'file', 'limit', 'order', 'search', 'select', 'start'));
$separator = $suit->getSection('section separator', $content);
$separator = (!empty($separator)) ?
	$separator[0] :
	'';
if (in_array($_GET['section'], array('code', 'content', 'glue')))
{
	$nodes[] = $suit->parseConditional('if dashboard', false, 'else dashboard');
	$return = $suit->tie->adminArea($_GET['section']);
	$suit->vars['tie'] = $return[0];
	$section = array_merge
	(
		array($suit->vars['language'][$_GET['section']]),
		$return[1]
	);
	$section = implode($separator, $section);
}
elseif ($_GET['section'] == 'phpinfo')
{
	if ($suit->tie->config['flag']['debug'])
		echo $suit->getTemplate('tie/debug');
	phpinfo();
	exit;
}
else
{
	$section = $suit->vars['language']['dashboard'];
	if ($_GET['tieversion'] == 'true')
	{
		$fetchversion = @file_get_contents('http://suitframework.sourceforge.net/tieversion.txt');
		$version = ($fetchversion) ?
			$fetchversion :
			$suit->vars['language']['na'];
		$nodes[] = $suit->parseConditional('if currentversion', ($suit->tie->version != $version));
		$nodes[] = $suit->parseConditional('if version', true, 'else version');
		$suit->vars['version'] = $version;
		$content = $suit->parse($nodes, $content);
		exit($content);
	}
	if ($_GET['suitversion'] == 'true')
	{
		$fetchversion = @file_get_contents('http://suitframework.sourceforge.net/suitversion.txt');
		$version = ($fetchversion) ?
			$fetchversion :
			$suit->vars['language']['na'];
		$nodes[] = $suit->parseConditional('if currentversion', ($suit->version != $version));
		$nodes[] = $suit->parseConditional('if version', true, 'else version');
		$suit->vars['version'] = $version;
		$content = $suit->parse($nodes, $content);
		exit($content);
	}
	$nodes[] = $suit->parseConditional('if dashboard', true, 'else dashboard');
	$nodes[] = $suit->parseConditional('if fileuploads', (ini_get('file_uploads')), 'else fileuploads');
	$nodes[] = $suit->parseConditional('if magicquotesgpc', (ini_get('magic_quotes_gpc')), 'else magicquotesgpc');
	$nodes[] = $suit->parseConditional('if magicquotesruntime', (ini_get('magic_quotes_runtime')), 'else magicquotesruntime');
	$nodes[] = $suit->parseConditional('if magicquotessybase', (ini_get('magic_quotes_sybase')), 'else magicquotessybase');
	$nodes[] = $suit->parseConditional('if registerglobals', (ini_get('register_globals')), 'else registerglobals');
	$suit->vars['currentsuitversion'] = $suit->version;
	$suit->vars['currenttieversion'] = $suit->tie->version;
	$suit->vars['path'] = $path[1] . $path[3];
	$suit->vars['phpversion'] = PHP_VERSION;
	$suit->vars['servertype'] = PHP_OS;
}
$suit->vars['name'] = $section;
$suit->vars['section'] = $section;
$nodes[] = $suit->parseConditional('section separator', false);
$nodes[] = $suit->parseConditional('if version', false, 'else version');
$content = $suit->parse($nodes, $content);
?>