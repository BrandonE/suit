<?php
/**
**@This file is part of BBNode.
**@BBNode is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@BBNode is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with BBNode.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
require '../suit/suit.class.php';
require '../suit/nodes.class.php';
$suit = new SUIT();
require 'config.php';
require $suit->vars['files']['code'] . '/print.inc.php';
echo $suit->gettemplate(
    file_get_contents($suit->vars['files']['templates'] . '/index.tpl'),
    array
    (
        $suit->vars['files']['code'] . '/index.inc.php',
        $suit->vars['files']['code'] . '/parse.inc.php'
    )
);
unset($suit);
?>