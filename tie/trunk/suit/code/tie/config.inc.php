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

Copyright (C) 2008-2009 The SUIT Group.
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
    'flag' => array
    (
        'debug' => false
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
            'template' => $suit->config['files']['templates'] . '/tie/badrequest.tpl',
            'code' => array($suit->config['files']['code'] . '/tie/badrequest.inc.php')
        ),
        'delete' => array
        (
            'template' => $suit->config['files']['templates'] . '/tie/delete.tpl'
        ),
        'form' => array
        (
            'template' => $suit->config['files']['templates'] . '/tie/form.tpl'
        ),
        'entries' => array
        (
            'template' => $suit->config['files']['templates'] . '/tie/entries.tpl'
        ),
        'pagelink' => array
        (
            'template' => $suit->config['files']['templates'] . '/tie/pagelink.tpl'
        ),
        'redirect' => array
        (
            'template' => $suit->config['files']['templates'] . '/tie/redirect.tpl'
        ),
        'xml' => array
        (
            'template' => $suit->config['files']['templates'] . '/tie/xml.tpl'
        )
    )
);
?>