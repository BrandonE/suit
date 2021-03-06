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
$suit->vars['config'] = array
(
	'cookie' => array
	(
		'domain' => '',
		'length' => 2678400,
		'path' => '',
		'prefix' => ''
	),
	'db' => array
	(
		'host' => 'localhost',
		'name' => '',
		'pass' => '',
		'prefix' => 'community_',
		'salt' => 'a839f',
		'type' => 'mysqli',
		'user' => ''
	),
	'flag' => array
	(
		'debug' => false
	),
	'navigation' => array
	(
		'array' => $_GET,
		'limit' => 10,
		'pages' => 2,
		'refresh' => 2
	),
	'templates' => array
	(
		'badrequest' => 'tie/badrequest',
		'delete' => 'tie/delete',
		'form' => 'tie/form',
		'list' => 'tie/list',
		'pagelink' => 'tie/pagelink',
		'redirect' => 'tie/redirect',
		'xml' => 'tie/xml'
	)
);
?>