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
$suit->vars['config'] = array
(
	'cookie' => array
	(
		'domain' => '',
		'length' => 3600,
		'path' => '',
		'prefix' => ''
	),
	'flag' => array
	(
		'insensitive' => true
	),
	'navigation' => array
	(
		'array' => $_GET,
		'pages' => 2,
		'refresh' => 2
	),
	'parse' => array
	(
		'escape' => '\\',
		'languages' => array
		(
			'open' => '[',
			'close' => ']'
		),
		'sections' => array
		(
			'open' => '<',
			'close' => '>',
			'end' => '/'
		),
		'templates' => array
		(
			'open' => '{',
			'close' => '}'
		),
		'variables' => array
		(
			'open' => '(',
			'close' => ')'
		)
	),
	'templates' => array
	(
		'admin_delete' => 'admin_delete',
		'admin_form' => 'admin_form',
		'admin_list' => 'admin_list',
		'admin_xml' => 'admin_xml',
		'badrequest' => 'badrequest',
		'navigation_pagelink' => 'navigation_pagelink',
		'navigation_redirect' => 'navigation_redirect'
	)
);
?>