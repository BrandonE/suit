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
require 'suit/suit.class.php';
require 'suit/nodes.class.php';
$suit = new SUIT();
$nodes = new Nodes();
$suit->nodes = $nodes->nodes;
$suit->nodes['[template]']['var']['list'] = array();
foreach (scandir('templates') as $value)
{
    if (basename($value, '.tpl') != $value)
    {
        $suit->nodes['[template]']['var']['list'][] = 'templates/' . $value;
        $suit->nodes['[template]']['var']['list'][] = realpath('templates/' . $value);
    }
}
foreach (scandir('templates/tie') as $value)
{
    if (basename($value, '.tpl') != $value)
    {
        $suit->nodes['[template]']['var']['list'][] = 'templates/tie/' . $value;
        $suit->nodes['[template]']['var']['list'][] = realpath('templates/tie/' . $value);
    }
}
$suit->nodes['[code]']['var']['list'] = array();
foreach (scandir('code') as $value)
{
    if (basename($value, '.inc.php') != $value)
    {
        $suit->nodes['[code]']['var']['list'][] = 'code/' . $value;
        $suit->nodes['[code]']['var']['list'][] = realpath('code/' . $value);
    }
}
foreach (scandir('code/languages') as $value)
{
    if (basename($value, '.inc.php') != $value)
    {
        $suit->nodes['[code]']['var']['list'][] = 'code/languages/' . $value;
        $suit->nodes['[code]']['var']['list'][] = realpath('code/languages/' . $value);
    }
}
foreach (scandir('code/tie') as $value)
{
    if (basename($value, '.inc.php') != $value)
    {
        $suit->nodes['[code]']['var']['list'][] = 'code/tie/' . $value;
        $suit->nodes['[code]']['var']['list'][] = realpath('code/tie/' . $value);
    }
}
$suit->condition = array();
$suit->loop = array();
include 'code/tie/main.inc.php';
include 'code/tie/index.inc.php';
$template = $suit->parse($suit->nodes, file_get_contents('templates/tie/index.tpl'));
class SLACKS
{
    public function slack($params)
    {
        $params['case'] = $params['var'];
        return $params;
    }
}
$slacks = new SLACKS();
$slacksnodes = array
(
	'<slacks' => array
	(
		'close' => '/>',
        'function' => array
        (
            array
            (
                'class' => $slacks,
                'function' => 'slack'
            )
        ),
		'skip' => true,
		'var' => htmlentities(json_encode($suit->debug))
	)
);
echo $suit->parse($slacksnodes, $template);
?>