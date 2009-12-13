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
require 'suit/suit.class.php';
$suit = new SUIT($config);
$suit->vars['files'] = array
(
    'code' => 'suit/code',
    'templates' => 'suit/templates'
);
$suit->vars['filetypes'] = array
(
    'code' => 'inc.php',
    'templates' => 'tpl'
);
$suit->config['parse']['nodes'] = array
(
    '[assign]' => array
    (
        'close' => '[/assign]',
        'function' => array
        (
            array
            (
                'function' => 'assign',
                'class' => $suit->nodes
            )
        ),
        'var' => array
        (
            'var' => ''
        )
    ),
    '[assign ' => array
    (
        'close' => '"]',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $suit->nodes
            )
        ),
        'attribute' => '[assign]',
        'skip' => true,
        'skipescape' => true,
        'skipignore' => true,
        'var' => array
        (
            'equal' => '=',
            'quote' => '"'
        )
    ),
    '[comment]' => array
    (
        'close' => '[/comment]',
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
        'var' => "\r.\n.\t ."
    ),
    /*
    '[eval]' => array
    (
        'close' => '[/eval]',
        'function' => array
        (
            array
            (
                'function' => 'evaluation',
                'class' => $suit->nodes
            )
        )
    ),
    */
    '[if]' => array
    (
        'close' => '[/if]',
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
            'trim' => "\r.\n.\t ."
        )
    ),
    '[if ' => array
    (
        'close' => '"]',
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
        'attribute' => '[if]',
        'skip' => true,
        'skipescape' => true,
        'skipignore' => true,
        'var' => array
        (
            'equal' => '=',
            'quote' => '"'
        )
    ),
    '[loop]' => array
    (
        'close' => '[/loop]',
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
            'trim' => "\r.\n.\t .",
            'node' => array
            (
                'open' => '[loopvar]',
                'close' => '[/loopvar]',
                'separator' => '=>'
            )
        )
    ),
    '[loop ' => array
    (
        'close' => '"]',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $suit->nodes
            )
        ),
        'attribute' => '[loop]',
        'skip' => true,
        'skipescape' => true,
        'skipignore' => true,
        'var' => array
        (
            'blacklist' => true,
            'equal' => '=',
            'list' => array('node'),
            'quote' => '"'
        )
    ),
    '[replace]' => array
    (
        'close' => '[/replace]',
        'function' => array
        (
            array
            (
                'function' => 'replace',
                'class' => $suit->nodes
            )
        ),
        'var' => array
        (
            'replace' => '',
            'search' => ''
        )
    ),
    '[replace ' => array
    (
        'close' => '"]',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $suit->nodes
            )
        ),
        'attribute' => '[replace]',
        'skip' => true,
        'skipescape' => true,
        'skipignore' => true,
        'var' => array
        (
            'equal' => '=',
            'quote' => '"'
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
    ),
    '[template]' => array
    (
        'close' => '[/template]',
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
            'files' => $suit->vars['files'],
            'filetypes' => $suit->vars['filetypes'],
            'separator' => '=>'
        )
    ),
    '[template ' => array
    (
        'close' => '"]',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $suit->nodes
            )
        ),
        'attribute' => '[template]',
        'skip' => true,
        'skipescape' => true,
        'skipignore' => true,
        'var' => array
        (
            'equal' => '=',
            'list' => array('label'),
            'quote' => '"'
        )
    ),
    '[var]' => array
    (
        'close' => '[/var]',
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
            'separator' => '=>'
        )
    )
);
$suit->vars['condition'] = array();
$suit->vars['loop'] = array();
require $suit->vars['files']['code'] . '/tie/main.inc.php';
require $suit->vars['files']['code'] . '/tie/print.inc.php';
$content = $suit->gettemplate(
    file_get_contents($suit->vars['files']['templates'] . '/tie/index.tpl'),
    array
    (
        $suit->vars['files']['code'] . '/tie/index.inc.php'
    )
);
$suit->vars['debug'] = $suit->debug;
$debug = $suit->gettemplate(
    file_get_contents($suit->vars['files']['templates'] . '/tie/debug.tpl'),
    array($suit->vars['files']['code'] . '/tie/debug.inc.php')
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