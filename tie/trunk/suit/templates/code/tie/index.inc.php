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
$nodes = $suit->config['parse']['nodes'];
$path = $suit->tie->path(array('boxes', 'cmd', 'directory', 'directorytitle', 'list', 'order', 'search', 'check', 'section', 'start', 'title'));
$suit->vars['sitetitle'] = $suit->vars['language']['title'];
$suit->vars['path'] = $path;
$separator = $suit->section->get('section separator', &$content);
if (!empty($separator))
{
    $separator = $separator[0];
}
else
{
    $separator = '';
}
if (in_array($_GET['section'], array('code', 'content', 'glue')))
{
    $nodes = array_merge($nodes, $suit->section->condition('if dashboard', false, 'else dashboard'));
    $return = $suit->tie->adminArea($_GET['section']);
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
        echo $suit->gettemplate('tie/debug');
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
        $nodes = array_merge
        (
            $nodes,
            $suit->section->condition('if currentversion', ($suit->tie->version != $version)),
            $suit->section->condition('if version', true, 'else version')
        );
        $suit->vars['version'] = $version;
        $content = $suit->parse($nodes, $content);
        exit($content);
    }
    if ($_GET['suitversion'] == 'true')
    {
        $version = @file_get_contents('http://suitframework.sourceforge.net/suitversion.txt');
        if (!$version)
        {
            $version = $suit->vars['language']['na'];
        }
        $nodes = array_merge
        (
            $nodes,
            $suit->section->condition('if currentversion', ($suit->version != $version)),
            $suit->section->condition('if version', true, 'else version')
        );
        $suit->vars['version'] = $version;
        $content = $suit->parse($nodes, $content);
        exit($content);
    }
    $nodes = array_merge
    (
        $nodes,
        $suit->section->condition('if dashboard', true, 'else dashboard'),
        $suit->section->condition('if fileuploads', (ini_get('file_uploads')), 'else fileuploads'),
        $suit->section->condition('if magicquotesgpc', (ini_get('magic_quotes_gpc')), 'else magicquotesgpc'),
        $suit->section->condition('if magicquotesruntime', (ini_get('magic_quotes_runtime')), 'else magicquotesruntime'),
        $suit->section->condition('if magicquotessybase', (ini_get('magic_quotes_sybase')), 'else magicquotessybase'),
        $suit->section->condition('if registerglobals', (ini_get('register_globals')), 'else registerglobals')
    );
    $suit->vars['currentsuitversion'] = $suit->version;
    $suit->vars['currenttieversion'] = $suit->tie->version;
    $suit->vars['phpversion'] = PHP_VERSION;
    $suit->vars['servertype'] = PHP_OS;
}
$suit->vars['name'] = $section;
$suit->vars['section'] = $section;
$nodes = array_merge
(
    $nodes,
    $suit->section->condition('section separator', false),
    $suit->section->condition('if version', false, 'else version')
);
$content = $suit->parse($nodes, $content);
?>