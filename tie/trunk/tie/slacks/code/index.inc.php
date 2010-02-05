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
$suit->language = array
(
    'case' => 'Case',
    'contents' => 'Contents',
    'copyright' => 'Copyright &copy; 2008-2010 <a href="http://www.suitframework.com/docs/credits" target="_blank">The SUIT Group</a>. All Rights Reserved.',
    'default' => 'Default',
    'enablejavascript' => 'Enable Javascript',
    'nowrapper' => 'No Wrapper',
    'parallel' => 'Parallel',
    'poweredby' => 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>',
    'slacks' => 'See this page built using SLACKS',
    'slogan' => 'SLACKS Lets Application Coders Know SUIT',
    'suit' => 'SUIT',
    'title' => 'SLACKS',
    'update' => 'Update',
    'wrapper' => 'Wrapper'
);
switch (strtolower($_GET['language']))
{
    case 'english':
        $suit->languagename = 'english';
        break;
    default:
        $suit->languagename = 'default';
        break;
}
function recurse($slacks, $nowrapper, $wrapper)
{
    if (!is_array($slacks))
    {
        return $slacks;
    }
    foreach ($slacks as $key => $value)
    {
        $slacks[$key] = array
        (
            'array' => false,
            'contents' => $value,
            'recursed' => true
        );
        if (is_object($value))
        {
            $slacks[$key] = $value;
            $slacks[$key]->array = true;
            $slacks[$key]->contents = recurse($value->contents, $nowrapper, $wrapper);
            $slacks[$key]->created = (isset($value->create));
            $slacks[$key]->recursed = (!isset($value->original));
            if (!isset($value->node))
            {
                $slacks[$key]->node = $nowrapper;
            }
            elseif (!$value->node)
            {
                $slacks[$key]->node = $wrapper;
            }
            if (!is_array($value->parallel))
            {
                break;
            }
            foreach ($value->parallel as $key2 => $value2)
            {
                $slacks[$key]->parallel[$key2] = array
                (
                    'parallel' => $value2
                );
            }
        }
    }
    return $slacks;
}
function idsort($a, $b)
{
    if ($a->id > $b->id)
    {
        return 1;
    }
    return -1;
}
$suit->loop['slacks'] = array();
if (array_key_exists('submit', $_POST) && $_POST['submit'])
{
    try
    {
        $suit->loop['slacks'] = json_decode($_POST['slacks']);
        if (is_array($suit->loop['slacks']))
        {
            usort($suit->loop['slacks'], 'idsort');
            $suit->loop['slacks'] = recurse($suit->loop['slacks'], $suit->language['nowrapper'], $suit->language['wrapper']);
        }
        else
        {
            $suit->loop['slacks'] = array();
        }
    }
    catch (Exception $e)
    {
        $suit->loop['slacks'] = array();
    }
}
?>