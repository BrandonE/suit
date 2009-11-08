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
function nodedebug($params)
{
	return $params['var'];
}

function nodecomments()
{
	return '';
}

function nodetemplates($params)
{
	return $params['suit']->gettemplate($params['case']);
}

function nodevars($params)
{
	//Split up the file, paying attention to escape strings
	$split = $params['suit']->explodeunescape('=>', $params['case']);
	$var = $params['suit']->vars;
	foreach ($split as $value)
	{
		$var = $var[$value];
	}
	return $var;
}

require 'suit/suit.class.php';
$config = array
(
	'files' => array
	(
		'code' => 'suit/templates/code',
		'content' => 'suit/templates/content',
		'glue' => 'suit/templates/glue'
	),
	'flag' => array
	(
		'insensitive' => true,
	),
	'parse' => array
	(
		'escape' => '\\',
		'loop' => array
		(
			'open' => '[|',
			'close' => '|]'
		),
		'nodes' => array
		(
			'[!' => array
			(
				'close' => '!]',
				'function' => 'nodetemplates'
			),
			'[:' => array
			(
				'close' => ':]',
				'function' => 'nodevars'
			),
			'[*' => array
			(
				'close' => '*]',
				'function' => 'nodecomments',
                'params' => false,
				'skip' => true
			)
		),
		'section' => array
		(
			'open' => '[',
			'close' => ']',
			'end' => '/',
			'trim' => "\r.\n.\t ."
		),
		'separatator' => '=>'
	)
);
$suit = new SUIT($config);
$content = $suit->gettemplate('tie/index');
$suit->vars['debug'] = $suit->debug;
$debug = $suit->gettemplate('tie/debug');
$nodes = array
(
	'<debug' => array
	(
		'close' => ' />',
		'function' => 'nodedebug',
		'skip' => true,
		'var' => $debug
	)
);
echo $suit->parse($nodes, $content);
unset($suit);
?>