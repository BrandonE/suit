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
$suit->condition['version'] = false;
$suit->sitetitle = $suit->language['title'];
if (in_array($_GET['section'], array('code', 'templates')))
{
    $suit->condition['dashboard'] = false;
    $result = $suit->tie->adminarea($_GET['section']);
    $suit->result = $result['return'];
    $section = array
    (
        array
        (
            'title' => $suit->language[$_GET['section']]
        )
    );
    foreach ($result['section'] as $value)
    {
        $section[] = array
        (
            'title' => $value
        );
    }
}
elseif ($_GET['section'] == 'phpinfo')
{
    phpinfo();
    exit;
}
else
{
    $section = array
    (
        array
        (
            'title' => $suit->language['dashboard']
        )
    );
    if ($_GET['tieversion'] == 'true')
    {
        $version = @file_get_contents('http://suitframework.sourceforge.net/tieversion.txt');
        if (!$version)
        {
            $version = $suit->language['na'];
        }
        $suit->condition['currentversion'] = ($suit->tie->version != $version);
        $suit->condition['version'] = true;
        $suit->version = $version;
    }
    elseif ($_GET['suitversion'] == 'true')
    {
        $version = @file_get_contents('http://suitframework.sourceforge.net/suitversion.txt');
        if (!$version)
        {
            $version = $suit->language['na'];
        }
        $suit->condition['currentversion'] = ($suit->version != $version);
        $suit->condition['version'] = true;
        $suit->vars['version'] = $version;
    }
    else
    {
        $suit->condition['dashboard'] = true;
        $suit->condition['fileuploads'] = (ini_get('file_uploads'));
        $suit->condition['magicquotesgpc'] = (ini_get('magic_quotes_gpc'));
        $suit->condition['magicquotesruntime'] = (ini_get('magic_quotes_runtime'));
        $suit->condition['magicquotessybase'] = (ini_get('magic_quotes_sybase'));
        $suit->condition['registerglobals'] = (ini_get('register_globals'));
        $suit->currentsuitversion = $suit->version;
        $suit->currenttieversion = $suit->tie->version;
        $suit->phpversion = PHP_VERSION;
        $suit->servertype = PHP_OS;
    }
}
$suit->loop['section'] = $section;
$suit->path = $suit->tie->path(array('check', 'cmd', 'directory', 'directorytitle', 'list', 'order', 'search', 'section', 'start', 'title'));
?>