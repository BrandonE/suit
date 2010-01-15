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

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$suit->loop['section'] = array
(
    array
    (
        'title' => $suit->language['dashboard']
    )
);
$suit->path = $suit->tie->path(array('check', 'cmd', 'directory', 'directorytitle', 'list', 'order', 'search', 'section', 'start', 'title'));
$suit->template = $suit->parse($suit->nodes, $suit->template);
$suit->debugging = $suit->debug;
include 'code/tie/debug.inc.php';
if ($suit->tie->config['flag']['debug'])
{
    $debug = $suit->parse($suit->nodes, file_get_contents('templates/tie/debug.tpl'));
}
else
{
    $debug = '';
}
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
exit($suit->parse($nodes, $suit->template));
?>