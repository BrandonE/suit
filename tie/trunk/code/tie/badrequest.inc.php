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
$suit->vars['name'] = $suit->vars['language']['badrequest'];
$template = $suit->parse($suit->vars['nodes'], $template);
$suit->vars['debug'] = $suit->debug;
$debug = $suit->gettemplate(
    file_get_contents($suit->vars['files']['templates'] . '/tie/debug.tpl'),
    array
    (
        $suit->vars['files']['code'] . '/tie/debug.inc.php',
        $suit->vars['files']['code'] . '/tie/parse.inc.php'
    )
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
exit($suit->parse($nodes, $template));
?>