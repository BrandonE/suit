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

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$suit->vars['language'] = array
(
    'copyright' => 'Copyright &copy; 2008-2010 <a href="http://www.suitframework.com/docs/credits" target="_blank">The SUIT Group</a>. All Rights Reserved.',
    'default' => 'Default',
    'example' => 'Example',
    'item' => 'Item',
    'parsed' => 'Parsed',
    'poweredby' => 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>',
    'slogan' => 'Scripting Using Integrated Templates',
    'suit' => 'SUIT',
    'template' => 'Template',
    'title' => 'SUIT Framework',
    'update' => 'Update'
);
switch (strtolower($_GET['language']))
{
    case 'english':
        $suit->vars['languagename'] = 'english';
        break;
    default:
        $suit->vars['languagename'] = 'default';
        break;
}
function node($params)
{
    if ($params['case'] == $params['var']['exception'])
    {
        $params['case'] = strtolower($params['case']);
    }
    else
    {
        $params['case'] = strtoupper($params['case']);
    }
    return $params;
}
function php($params)
{
    $params['case'] = highlight_string($params['open']['open'] . $params['case'] . $params['open']['node']['close'], true);
    return $params;
}
$nodes = new Nodes();
$suit->vars['nodes']['[node]'] = array
(
    'close' => '[/node]',
    'function' => array
    (
        array
        (
            'function' => 'node'
        )
    ),
    'var' => array
    (
        'exception' => 'Test'
    )
);
$suit->vars['nodes']['[node'] = array
(
    'close' => ']',
    'function' => array
    (
        array
        (
            'function' => 'attribute',
            'class' => $nodes
        )
    ),
    'attribute' => '[node]',
    'skip' => true,
    'var' => array
    (
        'equal' => '=',
        'quote' => '"'
    )
);
$suit->vars['nodes']['<?php'] = array
(
    'close' => '?>',
    'function' => array
    (
        array
        (
            'function' => 'php'
        )
    ),
    'skip' => true
);
if (array_key_exists('submit', $_POST))
{
    $suit->vars['template'] = $_POST['template'];
}
else
{
    $suit->vars['template'] = file_get_contents($suit->vars['files']['templates'] . '/example.tpl');
}
$suit->vars['templateentities'] = htmlentities($suit->vars['template']);
?>