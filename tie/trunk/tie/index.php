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

Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
require 'suit.class.php';
require 'rulebox/templating.class.php';
$suit = new SUIT();
$rules = new Templating();
$suit->rules = $rules->rules;
$suit->rules['[template]']['var']['list'] = array();
foreach (scandir('templates') as $value)
{
    if (basename($value, '.tpl') != $value)
    {
        $suit->rules['[template]']['var']['list'][] = 'templates/' . $value;
        $suit->rules['[template]']['var']['list'][] = realpath('templates/' . $value);
    }
}
foreach (scandir('templates/tie') as $value)
{
    if (basename($value, '.tpl') != $value)
    {
        $suit->rules['[template]']['var']['list'][] = 'templates/tie/' . $value;
        $suit->rules['[template]']['var']['list'][] = realpath('templates/tie/' . $value);
    }
}
$suit->rules['[code]']['var']['list'] = array();
foreach (scandir('code') as $value)
{
    if (basename($value, '.inc.php') != $value)
    {
        $suit->rules['[code]']['var']['list'][] = 'code/' . $value;
        $suit->rules['[code]']['var']['list'][] = realpath('code/' . $value);
    }
}
foreach (scandir('code/languages') as $value)
{
    if (basename($value, '.inc.php') != $value)
    {
        $suit->rules['[code]']['var']['list'][] = 'code/languages/' . $value;
        $suit->rules['[code]']['var']['list'][] = realpath('code/languages/' . $value);
    }
}
foreach (scandir('code/tie') as $value)
{
    if (basename($value, '.inc.php') != $value)
    {
        $suit->rules['[code]']['var']['list'][] = 'code/tie/' . $value;
        $suit->rules['[code]']['var']['list'][] = realpath('code/tie/' . $value);
    }
}
$suit->condition = array();
$suit->loop = array();
include 'code/tie/main.inc.php';
include 'code/tie/index.inc.php';
$template = $suit->execute($suit->rules, file_get_contents('templates/tie/index.tpl'));
if ((array_key_exists('slacks', $_POST) && $_POST['slacks']) || (array_key_exists('slacks', $_GET) && $_GET['slacks']))
{
    $tree = json_encode($suit->log['tree']);
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-type: text/json');
    header('Content-Disposition: attachment; filename=tree.json');
    header('Content-Length: ' . strlen($tree));
    echo $tree;
}
else
{
    echo $template;
}
?>