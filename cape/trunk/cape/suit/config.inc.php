<?php
/**
**@This file is part of SUIT.
**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$config = array
(
	'flag' => array
	(
		'insensitive' => true
	),
	'parse' => array
	(
		'close' => ']',
		'end' => '/',
		'escape' => '\\',
		'nodes' => array
		(
			array('{', '}', 'return $suit->getTemplate($case);'),
			array('(', ')', '$array = $suit->explodeUnescape(\'=>\', $suit->config[\'parse\'][\'escape\'], $case);
			$arrays = \'\';
			foreach ($array as $value)
				$arrays .= \'[\\\'\' . addslashes($value) . \'\\\']\';
			return eval(\'return $suit->vars\' . $arrays . \';\');'),
			array('/*', '*/', '')
		),
		'open' => '['
	),
	'templates' => array
	(
		'code' => 'suit/code',
		'content' => 'suit/content',
		'glue' => 'suit/glue'
	),
	'variables' => array
	(
		'content' => 'content',
		'suit' => 'suit'
	)
);
?>