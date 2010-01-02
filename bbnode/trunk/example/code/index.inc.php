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
    'poweredby' => 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>',
    'slogan' => 'Scripting Using Integrated Templates',
    'suit' => 'SUIT',
    'title' => 'SUIT Framework',
    'update' => 'Update'
);
switch ($_GET['language'])
{
    case 'english':
        $suit->vars['languagename'] = 'english';
        break;
    case 'spanish':
        $suit->vars['languagename'] = 'spanish';
        $suit->vars['language'] = array
        (
            'copyright' => 'Copyright &copy; 2008-2010 <a href="http://www.suitframework.com/docs/credits" target="_blank">The SUIT Group</a>. All Rights Reserved.',
            'default' => 'Predeterminado',
            'example' => 'Example',
            'item' => 'Item',
            'slogan' => 'Scripting Using Integrated Templates',
            'suit' => 'SUIT',
            'title' => 'SUIT Framework',
            'update' => 'Actualizar'
        );
        break;
    default:
        $suit->vars['languagename'] = 'default';
        break;
}
if (array_key_exists('submit', $_POST) && $_POST['submit'])
{
    $suit->vars['bbnodefiles'] = array
    (
        'code' => '../code',
        'templates' => '../templates'
    );
    $suit->vars['bbnodefiletypes'] = array
    (
        'code' => 'inc.php',
        'templates' => 'tpl'
    );
    require '../bbnode.class.php';
    $config = array
    (
        'escape' => ''
    );
    $suit->vars['message'] = htmlentities($_POST['message']);
    $suit->vars['parsed'] = $suit->parse($bbnode, nl2br(htmlspecialchars($_POST['message'])));
}
else
{
    $suit->vars['message'] = '';
}
?>