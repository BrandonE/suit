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

require 'suit/suit.class.php';
$config = array
(
	'files' => array
	(
		'code' => 'suit/code',
		'templates' => 'suit/templates'
	),
    'filetypes' => array
    (
        'code' => 'inc.php',
        'templates' => 'tpl'
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
		'section' => array
		(
			'open' => '[',
			'close' => ']',
			'end' => '/',
			'trim' => "\r.\n.\t ."
		),
		'separator' => '=>'
	)
);
$suit = new SUIT($config);
$suit->config['parse']['nodes'] = array
(
    '[!' => array
    (
        'close' => '!]',
        'class' => $suit->nodes,
        'function' => 'templates',
        'var' => array
        (
            'escape' => $suit->config['parse']['escape'],
            'separator' => $suit->config['parse']['separator']
        )
    ),
    '[:' => array
    (
        'close' => ':]',
        'class' => $suit->nodes,
        'function' => 'variables',
        'var' => array
        (
            'escape' => $suit->config['parse']['escape'],
            'separator' => $suit->config['parse']['separator']
        )
    ),
    '[*' => array
    (
        'close' => '*]',
        'class' => $suit->nodes,
        'function' => 'comments',
        'params' => false,
        'skip' => true
    )
);
require $suit->config['files']['code'] . '/tie/main.inc.php';
require $suit->config['files']['code'] . '/tie/print.inc.php';
$content = $suit->gettemplate(
    file_get_contents($suit->config['files']['templates'] . '/tie/index.tpl'),
    array
    (
        $suit->config['files']['code'] . '/tie/index.inc.php'
    )
);
$suit->vars['debug'] = $suit->debug;
$debug = $suit->gettemplate(
    file_get_contents($suit->config['files']['templates'] . '/tie/debug.tpl'),
    array($suit->config['files']['code'] . '/tie/debug.inc.php')
);
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