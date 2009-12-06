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
    $params['case'] = $params['var'];
	return $params;
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
        'attribute' => array
        (
            'equal' => '=',
            'separator' => ' ',
            'quote' => '"'
        ),
		'escape' => '\\',
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
        'function' => array
        (
            array
            (
                'function' => 'templates',
                'class' => $suit->nodes
            )
        ),
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
        'function' => array
        (
            array
            (
                'function' => 'variables',
                'class' => $suit->nodes
            )
        ),
        'var' => array
        (
            'escape' => $suit->config['parse']['escape'],
            'separator' => $suit->config['parse']['separator']
        )
    ),
    '[*' => array
    (
        'close' => '*]',
        'function' => array
        (
            array
            (
                'function' => 'comments',
                'class' => $suit->nodes
            )
        ),
        'skip' => true
    ),
    '[*' => array
    (
        'close' => '*]',
        'function' => array
        (
            array
            (
                'function' => 'comments',
                'class' => $suit->nodes
            )
        ),
        'skip' => true
    ),
    '[escape]' => array
    (
        'close' => '[/escape]',
        'function' => array
        (
            array
            (
                'function' => 'escape',
                'class' => $suit->nodes
            )
        ),
        'skip' => true,
        'skipescape' => true,
        'var' => $suit->config['parse']['section']['trim']
    ),
    $suit->config['parse']['section']['open'] . 'if' . $suit->config['parse']['section']['close'] => array
    (
        'close' => $suit->config['parse']['section']['open'] . $suit->config['parse']['section']['end'] . 'if' . $suit->config['parse']['section']['close'],
        'function' => array
        (
            array
            (
                'function' => 'condition',
                'class' => $suit->nodes
            )
        ),
        'skip' => true,
        'strip' => true,
        'var' => array
        (
            'condition' => false,
            'else' => false,
            'trim' => $suit->config['parse']['section']['trim']
        )
    ),
    $suit->config['parse']['section']['open'] . 'if ' => array
    (
        'close' => $suit->config['parse']['attribute']['quote'] . $suit->config['parse']['section']['close'],
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $suit->nodes
            ),
            array
            (
                'function' => 'conditionskip',
                'class' => $suit->nodes
            )
        ),
        'attribute' => $suit->config['parse']['section']['open'] . 'if' . $suit->config['parse']['section']['close'],
        'skip' => true,
        'skipignore' => true,
        'var' => array
        (
            'escape' => $suit->config['parse']['escape'],
            'equal' => $suit->config['parse']['attribute']['equal'],
            'separator' => $suit->config['parse']['attribute']['separator'],
            'quote' => $suit->config['parse']['attribute']['quote']
        )
    ),
    $suit->config['parse']['section']['open'] . 'loop' . $suit->config['parse']['section']['close'] => array
    (
        'close' => $suit->config['parse']['section']['open'] . $suit->config['parse']['section']['end'] . 'loop' . $suit->config['parse']['section']['close'],
        'function' => array
        (
            array
            (
                'function' => 'loop',
                'class' => $suit->nodes
            )
        ),
        'skip' => true,
        'var' => array
        (
            'vars' => serialize(array()),
            'delimiter' => '',
            'trim' => $suit->config['parse']['section']['trim'],
            'node' => array
            (
                'open' => '[|',
                'close' => '|]',
                'separator' => $suit->config['parse']['separator']
            )
        )
    ),
    $suit->config['parse']['section']['open'] . 'loop ' => array
    (
        'close' => $suit->config['parse']['attribute']['quote'] . $suit->config['parse']['section']['close'],
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $suit->nodes
            )
        ),
        'attribute' => $suit->config['parse']['section']['open'] . 'loop' . $suit->config['parse']['section']['close'],
        'skip' => true,
        'skipignore' => true,
        'var' => array
        (
            'blacklist' => true,
            'escape' => $suit->config['parse']['escape'],
            'equal' => $suit->config['parse']['attribute']['equal'],
            'list' => array('node'),
            'separator' => $suit->config['parse']['attribute']['separator'],
            'quote' => $suit->config['parse']['attribute']['quote']
        )
    ),
    '[return ' => array
    (
        'close' => '/]',
        'function' => array
        (
            array
            (
                'function' => 'returning',
                'class' => $suit->nodes
            )
        ),
        'skip' => true
    )
);
$suit->vars['condition'] = array();
$suit->vars['loop'] = array();
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
        'function' => array
        (
            array
            (
                'function' => 'nodedebug'
            )
        ),
		'skip' => true,
		'var' => $debug
	)
);
echo $suit->parse($nodes, $content);
unset($suit);
?>