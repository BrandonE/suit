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
    'contents' => 'Contents of',
    'copyright' => 'Copyright &copy; 2008-2010 <a href="http://www.suitframework.com/docs/credits" target="_blank">The SUIT Group</a>. All Rights Reserved.',
    'default' => 'Default',
    'example' => 'Example',
    'executed' => 'Executed',
    'item' => 'Item',
    'poweredby' => 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>',
    'slacks' => 'See this page built using SLACKS',
    'slogan' => 'Scripting Using Integrated Templates',
    'submit' => 'Submit',
    'suit' => 'SUIT',
    'template' => 'Template',
    'title' => 'SUIT Framework',
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
if (array_key_exists('submit', $_POST) && $_POST['submit'])
{
    $suit->template = $_POST['template'];
}
else
{
    $suit->template = file_get_contents('templates/example.tpl');
}
$suit->variablescode = highlight_string(file_get_contents('code/variables.inc.php'), true);
$suit->exceptioncode = highlight_string(file_get_contents('code/exception.inc.php'), true);
?>