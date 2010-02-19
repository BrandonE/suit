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
$config = array
(
    'cookie' => array
    (
        'domain' => '',
        'length' => 2678400,
        'path' => '',
        'prefix' => ''
    ),
    'files' => array
    (
        'code' => 'code',
        'templates' => 'templates'
    ),
    'filetypes' => array
    (
        'code' => 'inc.php',
        'templates' => 'tpl'
    ),
    'navigation' => array
    (
        'array' => $_GET,
        'list' => 10,
        'pages' => 2,
        'refresh' => 2
    ),
    'templates' => array
    (
        'badrequest' => array
        (
            'template' => 'templates/tie/badrequest.tpl',
            'code' => 'code/tie/badrequest.inc.php'
        ),
        'delete' => array
        (
            'template' => 'templates/tie/delete.tpl'
        ),
        'form' => array
        (
            'template' => 'templates/tie/form.tpl'
        ),
        'entries' => array
        (
            'template' => 'templates/tie/entries.tpl'
        ),
        'pagelink' => array
        (
            'template' => 'templates/tie/pagelink.tpl'
        ),
        'redirect' => array
        (
            'template' => 'templates/tie/redirect.tpl'
        ),
        'xml' => array
        (
            'template' => 'templates/tie/xml.tpl'
        )
    )
);
?>