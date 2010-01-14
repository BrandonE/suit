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
    'copyright' => 'Copyright &copy; 2008-2010 <a href="http://www.suitframework.com/docs/credits" target="_blank">The SUIT Group</a>. All Rights Reserved.',
    'default' => 'Default',
    'htmlmode' => 'HTML Mode',
    'next' => 'Next',
    'poweredby' => 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>',
    'previous' => 'Previous',
    'slacks' => 'See this page built using SLACKS',
    'slogan' => 'SLACKS Lets Application Coders Know SUIT',
    'suit' => 'SUIT',
    'textmode' => 'Text Mode',
    'title' => 'SLACKS',
    'update' => 'Update'
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
function recurse($slacks)
{
    foreach ($slacks as $key => $value)
    {
        foreach ($value->steps as $key2 => $value2)
        {
            if (isset($value2->recurse))
            {
                $slacks[$key]->steps[$key2]->recurse = recurse($value2->recurse);
            }
            $slacks[$key]->steps[$key2]->return = str_replace('<slacks />', '', $value2->return);
            $slacks[$key]->steps[$key2]->text = htmlspecialchars($value2->return);
            $slacks[$key]->steps[$key2]->recursing = (isset($value2->recurse) && $value2->recurse);
        }
    }
    return $slacks;
}
if (array_key_exists('submit', $_POST) && $_POST['submit'])
{
    $suit->loop['slacks'] = json_decode($_POST['slacks']);
    $suit->loop['slacks'] = recurse($suit->loop['slacks']);
}
else
{
    $suit->loop['slacks'] = array();
}
?>