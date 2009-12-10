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
$path = $suit->tie->path(array('boxes', 'cmd', 'directory', 'directorytitle', 'list', 'order', 'search', 'check', 'section', 'start', 'title'));
$suit->vars['sitetitle'] = $suit->vars['language']['title'];
$suit->vars['path'] = $path;
$separator = $suit->section->get('section separator', &$template);
if (!empty($separator))
{
    $separator = $separator[0];
}
else
{
    $separator = '';
}
if (in_array($_GET['section'], array('code', 'templates')))
{
    $suit->vars['condition']['dashboard'] = false;
    $return = $suit->tie->adminarea($_GET['section']);
    $suit->vars['tie'] = $return['return'];
    $section = array_merge
    (
        array($suit->vars['language'][$_GET['section']]),
        $return['section']
    );
    $section = implode($separator, $section);
}
elseif ($_GET['section'] == 'phpinfo')
{
    if ($suit->tie->config['flag']['debug'])
    {
        echo $suit->template(
            file_get_contents($suit->vars['files']['templates'] . '/tie/debug.tpl'),
            array($suit->vars['files']['code'] . '/tie/debug.inc.php')
        );
    }
    phpinfo();
    exit;
}
else
{
    $section = $suit->vars['language']['dashboard'];
    if ($_GET['tieversion'] == 'true')
    {
        $version = @file_get_contents('http://suitframework.sourceforge.net/tieversion.txt');
        if (!$version)
        {
            $version = $suit->vars['language']['na'];
        }
        $suit->vars['condition']['currentversion'] = ($suit->tie->version != $version);
        $suit->vars['condition']['version'] = true;
        $suit->vars['version'] = $version;
        $template = $suit->parse($suit->config['parse']['nodes'], $template);
        exit($template);
    }
    if ($_GET['suitversion'] == 'true')
    {
        $version = @file_get_contents('http://suitframework.sourceforge.net/suitversion.txt');
        if (!$version)
        {
            $version = $suit->vars['language']['na'];
        }
        $suit->vars['condition']['currentversion'] = ($suit->version != $version);
        $suit->vars['condition']['version'] = true;
        $suit->vars['version'] = $version;
        $template = $suit->parse($suit->config['parse']['nodes'], $template);
        exit($template);
    }
    $suit->vars['condition']['dashboard'] = true;
    $suit->vars['condition']['fileuploads'] = (ini_get('file_uploads'));
    $suit->vars['condition']['magicquotesgpc'] = (ini_get('magic_quotes_gpc'));
    $suit->vars['condition']['magicquotesruntime'] = (ini_get('magic_quotes_runtime'));
    $suit->vars['condition']['magicquotessybase'] = (ini_get('magic_quotes_sybase'));
    $suit->vars['condition']['registerglobals'] = (ini_get('register_globals'));
    $suit->vars['currentsuitversion'] = $suit->version;
    $suit->vars['currenttieversion'] = $suit->tie->version;
    $suit->vars['phpversion'] = PHP_VERSION;
    $suit->vars['servertype'] = PHP_OS;
}
$suit->vars['name'] = $section;
$suit->vars['section'] = $section;
$suit->vars['condition']['version'] = false;
$template = $suit->parse($suit->config['parse']['nodes'], $template);
?>