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
class TIE
{
    public $config = array();

    public $language = array();

    public $owner;

    public $settings = array();

    public $version = '1.3.4';

    /**
    http://www.suitframework.com/docs/TIE+Construct
    **/
    public function __construct($owner, $config)
    {
        $this->owner = $owner;
        $this->helper = new TIEHelper($this);
        $this->config = $config;
        if (array_key_exists('start', $this->config['navigation']['array']))
        {
            $this->settings['start'] = $this->config['navigation']['array']['start'];
        }
        else
        {
            $this->settings['start'] = 0;
        }
        if (array_key_exists('list', $this->config['navigation']['array']))
        {
            $this->settings['list'] = $this->config['navigation']['array']['list'];
        }
        else
        {
            $this->settings['list'] = $this->config['navigation']['list'];
        }
        if (array_key_exists('search', $this->config['navigation']['array']))
        {
            $this->settings['search'] = $this->config['navigation']['array']['search'];
        }
        else
        {
            $this->settings['search'] = '';
        }
        if (array_key_exists('order', $this->config['navigation']['array']) && $this->config['navigation']['array']['order'] == 'desc')
        {
            $this->settings['order'] = 'desc';
        }
        else
        {
            $this->settings['order'] = 'asc';
        }
        if (array_key_exists('order_reverse', $this->config['navigation']['array']) && $this->config['navigation']['array']['order'] == 'asc')
        {
            $this->settings['order_reverse'] = 'asc';
        }
        else
        {
            $this->settings['order_reverse'] ='desc';
        }
        $this->settings['check'] = (isset($this->config['navigation']['array']['check']) && $this->config['navigation']['array']['check'] == 'true');
        if (isset($this->config['cookie']['domain']) && isset($this->config['cookie']['length']) && isset($this->config['cookie']['path']) && isset($this->config['cookie']['prefix']))
        {
            $this->owner->gettemplate('languages/main');
            $this->language = -1;
            if (isset($_COOKIE[$this->config['cookie']['prefix'] . 'language']))
            {
                $this->language = $_COOKIE[$this->config['cookie']['prefix'] . 'language'];
                if (!(isset($this->owner->vars['languages'][$this->language]) || $this->language == -1))
                {
                    $this->language = -1;
                    setcookie($this->config['cookie']['prefix'] . 'language', '', time() - $this->config['cookie']['length'], $this->config['cookie']['path'], $this->config['cookie']['domain']);
                }
            }
            if ($this->language != -1)
            {
                $this->owner->gettemplate($this->owner->vars['languages'][$this->language][1]);
            }
            else
            {
                if (is_array($this->owner->vars['languages']))
                {
                    foreach ($this->owner->vars['languages'] as $value)
                    {
                        if ($value[2])
                        {
                            $this->owner->gettemplate($value[1]);
                        }
                    }
                }
            }
        }
        if (get_magic_quotes_gpc())
        {
            $in = array(&$_GET, &$_POST, &$_COOKIE);
            while (list($k, $v) = each($in))
            {
                foreach ($v as $key => $value)
                {
                    if (!is_array($value))
                    {
                        if (ini_get('magic_quotes_sybase'))
                        {
                            $in[$k][$key] = str_replace('\'\'', '\'', $value);
                        }
                        else
                        {
                            $in[$k][$key] = stripslashes($value);
                        }
                        continue;
                    }
                    $in[] =& $in[$k][$key];
                }
            }
            unset($in);
        }
    }

    /**
    http://www.suitframework.com/docs/adminArea
    **/
    public function adminarea($type, $config = array())
    {
        if (!array_key_exists('badrequest', $config))
        {
            $config['badrequest'] = $this->config['templates']['badrequest'];
        }
        if (!array_key_exists('delete', $config))
        {
            $config['delete'] = $this->config['templates']['delete'];
        }
        if (!array_key_exists('form', $config))
        {
            $config['form'] = $this->config['templates']['form'];
        }
        if (!array_key_exists('entries', $config))
        {
            $config['entries'] = $this->config['templates']['entries'];
        }
        if (!array_key_exists('xml', $config))
        {
            $config['xml'] = $this->config['templates']['xml'];
        }
        $error = false;
        $nodes = $this->owner->config['parse']['nodes'];
        $nodes = array_merge
        (
            $nodes,
            $this->owner->section->condition('if code', ($type == 'code'), 'else code'),
            $this->owner->section->condition('if content', ($type == 'content')),
            $this->owner->section->condition('if glue', ($type == 'glue')),
            $this->owner->section->condition('if box', (!in_array($_GET['cmd'], array('copy', 'create', 'rename'))))
        );
        $path = $this->path(array('boxes', 'cmd', 'directory', 'directorytitle', 'list', 'order', 'search', 'check', 'start', 'title'));
        $this->owner->vars['path'] = $path;
        $redirect = $this->path(array('boxes', 'cmd', 'directory', 'directorytitle', 'check', 'title'));
        $redirect = $redirect['regular'];
        if (!$this->logistics())
        {
            $this->owner->gettemplate($config['badrequest']);
        }
        $directory = $this->helper->directorydata($_GET['directory']);
        if (!is_dir($this->owner->config['files'][$type] . $directory['string']))
        {
            $this->owner->gettemplate($config['badrequest']);
        }
        if ($type != 'code')
        {
            if ($type == 'glue')
            {
                $filetype = 'txt';
            }
            else
            {
                $filetype = 'tpl';
            }
        }
        else
        {
            $filetype = 'inc.php';
        }
        switch ($type)
        {
            case 'content':
                $filetype = 'tpl';
                break;
            case 'code':
                $filetype = 'inc.php';
                break;
            case 'glue':
                $filetype = 'txt';
                break;
        }
        $illegal = array('/', '\\');
        if ($type == 'glue')
        {
            $post = array('code', 'content', 'title');
        }
        else
        {
            $post = array('content', 'title');
        }
        if ($type != 'code')
        {
            $posted = array();
            foreach ($post as $value)
            {
                if (isset($_POST[$value]))
                {
                    $posted[$value] = $_POST[$value];
                }
                else
                {
                    $posted[$value] = NULL;
                }
            }
            if (isset($_POST['import']))
            {
                if ($_FILES['file']['type'] == 'text/xml')
                {
                    $upload = file_get_contents($_FILES['file']['tmp_name']);
                }
                else
                {
                    $error = $this->owner->vars['language']['filenotvalid'];
                }
            }
            if ($error === false)
            {
                if (((isset($_POST['add']) || isset($_POST['edit']) || isset($_POST['editandcontinue'])) && isset($posted['title']) && isset($posted['content'])) || ((isset($_POST['copy']) || isset($_POST['create']) || isset($_POST['rename'])) && isset($posted['title'])) || (isset($_POST['import']) && isset($_FILES['file'])) || (((isset($_POST['move']) && isset($_POST['moveto'])) || (isset($_POST['replace']) && isset($_POST['find']) && isset($_POST['replacewith']))) && (isset($_POST['entry']) || isset($_POST['directoryentry']))) || isset($_POST['delete']))
                {
                    $getconfig = array
                    (
                        'close' => '>',
                        'end' => '/',
                        'escape' => '\\',
                        'open' => '<'
                    );
                    $files = array();
                    $directories = array();
                    if (isset($_POST['import']))
                    {
                        $files = $this->owner->section->get('file', &$upload, $getconfig);
                        foreach ($files as $key => $value)
                        {
                            $files[$key] = array
                            (
                                'code' => $this->owner->section->get('code', &$value, $getconfig),
                                'content' => $this->owner->section->get('content', $value, $getconfig),
                                'directory' => array_merge
                                (
                                    $directory['array'],
                                    $this->owner->section->get('array', $value, $getconfig)
                                ),
                                'title' => $this->owner->section->get('title', $value, $getconfig)
                            );
                            if (isset($files[$key]['title'][0]) && isset($files[$key]['content'][0]))
                            {
                                $files[$key]['title'] = str_replace($illegal, '', $files[$key]['title'][0]);
                                $files[$key]['oldtitle'] = $files[$key]['title'];
                                $files[$key]['content'] = $files[$key]['content'][0];
                                $this->owner->section->get('code', &$files[$key]['title'], $getconfig);
                                $this->owner->section->get('content', &$files[$key]['title'], $getconfig);
                                $this->owner->section->get('array', &$files[$key]['title'], $getconfig);
                                $this->owner->section->get('directory', &$files[$key]['title'], $getconfig);
                                $this->owner->section->get('code', &$files[$key]['content'], $getconfig);
                                $this->owner->section->get('array', &$files[$key]['content'], $getconfig);
                                $this->owner->section->get('title', &$files[$key]['content'], $getconfig);
                                $this->owner->section->get('directory', &$files[$key]['content'], $getconfig);
                                foreach ($files[$key]['code'] as $key2 => $value2)
                                {
                                    $this->owner->section->get('content', &$files[$key]['code'][$key2], $getconfig);
                                    $this->owner->section->get('array', &$files[$key]['code'][$key2], $getconfig);
                                    $this->owner->section->get('title', &$files[$key]['code'][$key2], $getconfig);
                                    $this->owner->section->get('directory', &$files[$key]['code'][$key2], $getconfig);
                                }
                                foreach ($files[$key]['directory'] as $key2 => $value2)
                                {
                                    $this->owner->section->get('code', &$files[$key]['directory'][$key2], $getconfig);
                                    $this->owner->section->get('content', &$files[$key]['directory'][$key2], $getconfig);
                                    $this->owner->section->get('title', &$files[$key]['directory'][$key2], $getconfig);
                                    $this->owner->section->get('directory', &$files[$key]['directory'][$key2], $getconfig);
                                }
                            }
                            else
                            {
                                $error = $this->owner->vars['language']['filenotvalid'];
                            }
                            $thisdirectory = $this->helper->directorydata($files[$key]['directory']);
                            $filepath = $this->owner->config['files'][$type] . $thisdirectory['string'] . '/' . $files[$key]['title'] . '.' . $filetype;
                            if (is_file($filepath))
                            {
                                $error = $this->owner->vars['language']['duplicatetitle'];
                            }
                        }
                    }
                    elseif (isset($_POST['delete']) && is_array($_GET['title']))
                    {
                        foreach ($_GET['title'] as $value)
                        {
                            if (is_writable($this->owner->config['files'][$type] . $directory['string']))
                            {
                                if ($value != '')
                                {
                                    $files[] = array
                                    (
                                        'code' => array(),
                                        'content' => array(''),
                                        'directory' => $directory['array'],
                                        'oldtitle' => '',
                                        'title' => $value
                                    );
                                }
                            }
                            else
                            {
                                $error = $this->owner->vars['language']['directorynotchmod'];
                                break;
                            }
                        }
                    }
                    elseif (isset($_POST['move']) && is_array($_POST['entry']))
                    {
                        foreach ($_POST['entry'] as $value)
                        {
                            if (is_writable($this->owner->config['files'][$type] . $directory['string']))
                            {
                                if ($value != '')
                                {
                                    $array = $directory['array'];
                                    if ($_POST['moveto'] == '..')
                                    {
                                        $array = array_values($array);
                                        unset($array[count($array) - 1]);
                                        $moveto = array();
                                    }
                                    else
                                    {
                                        $moveto = array($_POST['moveto']);
                                    }
                                    $stripped = str_replace($illegal, '', $value);
                                    $files[] = array
                                    (
                                        'code' => array(),
                                        'content' => file_get_contents($this->owner->config['files'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype),
                                        'directory' => array_merge($array, $moveto),
                                        'oldtitle' => $stripped,
                                        'title' => $stripped
                                    );
                                }
                            }
                            else
                            {
                                $error = $this->owner->vars['language']['directorynotchmod'];
                                break;
                            }
                        }
                    }
                    elseif (isset($_POST['replace']) && is_array($_POST['entry']))
                    {
                        foreach ($_POST['entry'] as $value)
                        {
                            if ($value != '')
                            {
                                $stripped = str_replace($illegal, '', $value);
                                if ($type == 'glue')
                                {
                                    $glue = $this->owner->explodeunescape('=', file_get_contents($this->owner->config['files'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype), '\\');
                                    foreach ($glue as $key2 => $value2)
                                    {
                                        $glue[$key2] = str_replace($_POST['find'], $_POST['replacewith'], $value2);
                                    }
                                    $contenttoken = implode('=', $glue);
                                }
                                else
                                {
                                    $contenttoken = str_replace($_POST['find'], $_POST['replacewith'], file_get_contents($this->owner->config['files'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype));
                                }
                                $files[] = array
                                (
                                    'code' => array(),
                                    'content' => $contenttoken,
                                    'directory' => $directory['array'],
                                    'oldtitle' => $stripped,
                                    'title' => $stripped
                                );
                            }
                        }
                    }
                    $strippedget = str_replace($illegal, '', $_GET['title']);
                    $strippedposted = str_replace($illegal, '', $posted['title']);
                    if (isset($_POST['copy']) || isset($_POST['create']) || isset($_POST['delete']) || isset($_POST['import']) || isset($_POST['move']) || isset($_POST['rename']) || isset($_POST['replace']))
                    {
                        if (isset($_POST['delete']))
                        {
                            $title = $_GET['directorytitle'];
                        }
                        elseif (isset($_POST['move']) || isset($_POST['replace']))
                        {
                            $title = $_POST['directoryentry'];
                        }
                        elseif (isset($_POST['import']))
                        {
                            $directories = $this->owner->section->get('directory', &$upload, $getconfig);
                            foreach ($directories as $key => $value)
                            {
                                $directories[$key] = array
                                (
                                    'directory' => array_merge
                                    (
                                        $directory['array'],
                                        $this->owner->section->get('array', &$value, $getconfig)
                                    ),
                                    'title' => $this->owner->section->get('title', $value, $getconfig)
                                );
                                if (isset($directories[$key]['title'][0]))
                                {
                                    $directories[$key]['title'] = str_replace($illegal, '', $directories[$key]['title'][0]);
                                    $this->owner->section->get('code', &$directories[$key]['title'], $getconfig);
                                    $this->owner->section->get('content', &$directories[$key]['title'], $getconfig);
                                    $this->owner->section->get('array', &$directories[$key]['title'], $getconfig);
                                    $this->owner->section->get('directory', &$directories[$key]['title'], $getconfig);
                                    foreach ($directories[$key]['directory'] as $key2 => $value2)
                                    {
                                        $this->owner->section->get('code', &$directories[$key]['directory'][$key2], $getconfig);
                                        $this->owner->section->get('content', &$directories[$key]['directory'][$key2], $getconfig);
                                        $this->owner->section->get('title', &$directories[$key]['directory'][$key2], $getconfig);
                                        $this->owner->section->get('directory', &$directories[$key]['directory'][$key2], $getconfig);
                                    }
                                }
                                else
                                {
                                    $error = $this->owner->vars['language']['filenotvalid'];
                                    break;
                                }
                            }
                        }
                        else
                        {
                            $directories[] = array
                            (
                                'directory' => $directory['array'],
                                'oldtitle' => $strippedget,
                                'title' => $strippedposted
                            );
                            $title = array($_GET['title']);
                        }
                        if((isset($_POST['copy']) || isset($_POST['delete']) || isset($_POST['move']) || isset($_POST['rename']) || isset($_POST['replace'])) && is_array($title))
                        {
                            foreach ($title as $value)
                            {
                                if ($value != $_POST['moveto'] || !isset($_POST['move']))
                                {
                                    if ($value != '')
                                    {
                                        $stripped = str_replace($illegal, '', $value);
                                        if (!is_dir($this->owner->config['files'][$type] . $directory['string'] . '/' . $stripped))
                                        {
                                            $this->owner->gettemplate($config['badrequest']);
                                        }
                                        $templates = array_diff($this->rscandir($this->owner->config['files'][$type] . $directory['string'] . '/' . $stripped . '/'), array('.', '..'));
                                        $templates = array_merge
                                        (
                                            array($this->owner->config['files'][$type] . $directory['string'] . '/' . $stripped),
                                            $templates
                                        );
                                        foreach ($templates as $value2)
                                        {
                                            $check = $value2;
                                            if (substr($check, strlen($check) - 1) == '/')
                                            {
                                                $check = substr($check, 0, -1);
                                            }
                                            $check = explode('/', $check);
                                            $check = array_values($check);
                                            unset($check[count($check) - 1]);
                                            $check = implode('/', $check);
                                            if (is_writable($check))
                                            {
                                                if (!isset($_POST['move']) && !isset($_POST['replace']))
                                                {
                                                    $showtitle = '/' . $strippedposted;
                                                }
                                                else
                                                {
                                                    $showtitle = '/' . $stripped;
                                                }
                                                if (isset($_POST['move']) && ($_POST['moveto'] != '..'))
                                                {
                                                    $moveto = '/' . str_replace($illegal, '', $_POST['moveto']);
                                                }
                                                else
                                                {
                                                    $moveto = '';
                                                }
                                                $newdirectory = explode('/', $directory['string']);
                                                $newdirectory = array_values($newdirectory);
                                                unset($newdirectory[count($newdirectory) - 1]);
                                                $newdirectory = implode('/', $newdirectory);
                                                if (isset($_POST['move']) && ($_POST['moveto'] == '..'))
                                                {
                                                    $string = $newdirectory;
                                                }
                                                else
                                                {
                                                    $string = $directory['string'];
                                                }
                                                $new = str_replace($this->owner->config['files'][$type] . $directory['string'] . '/' . $stripped, $this->owner->config['files'][$type] . $string . $moveto . $showtitle, $value2);
                                                if (substr($new, strlen($new) - 1) == '/')
                                                {
                                                    $new = substr($new, 0, -1);
                                                }
                                                $new = explode($this->owner->config['files'][$type] . '/', $new, 2);
                                                $new = explode('/', $new[1]);
                                                $new = array_values($new);
                                                unset($new[count($new) - 1]);
                                                if (is_file($value2) && !isset($_POST['delete']))
                                                {
                                                    if (isset($_POST['replace']))
                                                    {
                                                        if ($type == 'glue')
                                                        {
                                                            $glue = $this->owner->explodeunescape('=', file_get_contents($value2), '\\');
                                                            foreach ($glue as $key3 => $value3)
                                                            {
                                                                $glue[$key3] = str_replace($_POST['find'], $_POST['replacewith'], $value3);
                                                            }
                                                            $contenttoken = implode('=', $glue);
                                                        }
                                                        else
                                                        {
                                                            $contenttoken = str_replace($_POST['find'], $_POST['replacewith'], file_get_contents($value2));
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $contenttoken = file_get_contents($value2);
                                                    }
                                                    $files[] = array
                                                    (
                                                        'code' => '',
                                                        'content' => $contenttoken,
                                                        'directory' => $new,
                                                        'oldtitle' => basename($value2, '.' . $filetype),
                                                        'title' => basename($value2, '.' . $filetype),
                                                    );
                                                }
                                                elseif (!isset($_POST['delete']))
                                                {
                                                    $directories[] = array
                                                    (
                                                        'directory' => $new,
                                                        'oldtitle' => basename($value2),
                                                        'title' => basename($value2)
                                                    );
                                                }
                                            }
                                            else
                                            {
                                                $error = $this->owner->vars['language']['directorynotchmod'];
                                                break;
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $error = $this->owner->vars['language']['cannotmovedirectorytoself'];
                                }
                            }
                        }
                    }
                    else
                    {
                        $files[] = array
                        (
                            'code' => $posted['code'],
                            'content' => $posted['content'],
                            'directory' => $directory['array'],
                            'oldtitle' => $strippedget,
                            'title' => $strippedposted,
                        );
                    }
                    if ($error === false)
                    {
                        foreach ($directories as $value)
                        {
                            $thisdirectory = $this->helper->directorydata($value['directory']);
                            $filepath = $this->owner->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'];
                            if (!isset($_POST['rename']) && !isset($_POST['copy']) && !isset($_POST['replace']))
                            {
                                if (!is_dir($filepath) && $value['title'] == '')
                                {
                                    $error = $this->owner->vars['language']['missingtitle'];
                                }
                                elseif (is_dir($filepath))
                                {
                                    $error = $this->owner->vars['language']['duplicatetitle'];
                                }
                            }
                            else
                            {
                                if ((!is_dir($filepath) || $value['title'] == $value['oldtitle']) && $value['title'] == '')
                                {
                                    $error = $this->owner->vars['language']['missingtitle'];
                                }
                                elseif (is_dir($filepath) && $value['title'] != $value['oldtitle'])
                                {
                                    $error = $this->owner->vars['language']['duplicatetitle'];
                                }
                            }
                            if ($error !== false)
                            {
                                break;
                            }
                        }
                        if ($error === false)
                        {
                            foreach ($directories as $value)
                            {
                                $thisdirectory = $this->helper->directorydata($value['directory']);
                                $error = false;
                                if (!is_dir($this->owner->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title']))
                                {
                                    if (is_writable($this->owner->config['files'][$type] . $thisdirectory['string']))
                                    {
                                        mkdir($this->owner->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title']);
                                        chmod($this->owner->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'], 0777);
                                    }
                                    else
                                    {
                                        $error = $this->owner->vars['language']['directorynotchmod'];
                                    }
                                }
                                if ($error !== false)
                                {
                                    break;
                                }
                            }
                            if ($error === false)
                            {
                                foreach ($files as $value)
                                {
                                    $thisdirectory = $this->helper->directorydata($value['directory']);
                                    $filepath = $this->owner->config['files'][$type] . $thisdirectory['string'] . '/' . $value['oldtitle'] . '.' . $filetype;
                                    $filepath2 = $this->owner->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype;
                                    if (!isset($_POST['delete']))
                                    {
                                        if (!isset($_POST['rename']))
                                        {
                                            if (!isset($_POST['edit']) && !isset($_POST['editandcontinue']) && !isset($_POST['replace']))
                                            {
                                                if (!is_file($filepath2) && $value['title'] == '')
                                                {
                                                    $error = $this->owner->vars['language']['missingtitle'];
                                                }
                                                elseif (is_file($filepath2))
                                                {
                                                    $error = $this->owner->vars['language']['duplicatetitle'];
                                                }
                                            }
                                            else
                                            {
                                                if (is_file($filepath))
                                                {
                                                    if ((!is_file($filepath2) || $value['title'] == $value['oldtitle']) && $value['title'] == '')
                                                    {
                                                        $error = $this->owner->vars['language']['missingtitle'];
                                                    }
                                                    elseif (is_file($filepath2) && $value['title'] != $value['oldtitle'])
                                                    {
                                                        $error = $this->owner->vars['language']['duplicatetitle'];
                                                    }
                                                }
                                                else
                                                {
                                                    $this->owner->gettemplate($config['badrequest']);
                                                }
                                            }
                                        }
                                        if ($error === false)
                                        {
                                            $error = false;
                                            if (!is_file($this->owner->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype))
                                            {
                                                if (is_writable($this->owner->config['files'][$type] . $thisdirectory['string']))
                                                {
                                                    @touch($this->owner->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype) or $return = $this->owner->vars['language']['filecouldnotbecreated'];
                                                    @chmod($this->owner->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype, 0666);
                                                }
                                                else
                                                {
                                                    $return = $this->owner->vars['language']['directorynotchmod'];
                                                }
                                            }
                                            if ($error === false)
                                            {
                                                if (is_writable($filepath2))
                                                {
                                                    if ($type == 'glue' && !isset($_POST['rename']) && !isset($_POST['copy']) && !isset($_POST['move']) && !isset($_POST['replace']))
                                                    {
                                                        if (!empty($value['code']))
                                                        {
                                                            foreach ($value['code'] as $key => $value2)
                                                            {
                                                                $value['code'][$key] = $this->owner->escape(array('='), $value2, '\\');
                                                            }
                                                        }
                                                        $value['content'] = $this->owner->escape(array('='), $value['content'], '\\');
                                                        if (!empty($value['code']))
                                                        {
                                                            $value['content'] .= '=' . implode('=', $value['code']);
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $search = array("\r\n", "\r");
                                                        $value['content'] = str_replace($search, "\n", $value['content']);
                                                    }
                                                    file_put_contents($filepath2, $value['content']);
                                                    if ((isset($_POST['edit']) || isset($_POST['editandcontinue'])) && $value['title'] != $value['oldtitle'])
                                                    {
                                                        unlink($filepath);
                                                    }
                                                }
                                                else
                                                {
                                                    $error = $this->owner->vars['language']['filenotchmod'];
                                                }
                                            }
                                            if ($error === false && isset($_POST['move']) && is_array($_POST['entry']) && in_array($value['title'], $_POST['entry']))
                                            {
                                                unlink($this->owner->config['files'][$type] . $directory['string'] . '/' . $value['title'] . '.' . $filetype);
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if (is_file($filepath2))
                                        {
                                            unlink($filepath2);
                                        }
                                        else
                                        {
                                            $this->owner->gettemplate($config['badrequest']);
                                        }
                                    }
                                }
                                if ($error === false)
                                {
                                    if((isset($_POST['rename']) && $posted['title'] != $_GET['title']) || (isset($_POST['delete']) && is_array($_GET['directorytitle'])) || (isset($_POST['move']) && is_array($_POST['directoryentry'])))
                                    {
                                        if (isset($_POST['delete']))
                                        {
                                            $title = $_GET['directorytitle'];
                                        }
                                        else
                                        {
                                            if (isset($_POST['rename']))
                                            {
                                                $title = array($_GET['title']);
                                            }
                                            else
                                            {
                                                $title = $_POST['directoryentry'];
                                            }
                                        }
                                        foreach ($title as $value)
                                        {
                                            if (!in_array($value, array('', '.', '..')) && !(isset($_POST['move']) && $value == $_POST['moveto']))
                                            {
                                                $templates = array_diff($this->rscandir($this->owner->config['files'][$type] . $directory['string'] . '/' . $value . '/'), array('.', '..'));
                                                $templates = array_reverse($templates);
                                                $templates[] = $this->owner->config['files'][$type] . $directory['string'] . '/' . $value;
                                                foreach ($templates as $value2)
                                                {
                                                    if (is_file($value2))
                                                    {
                                                        unlink($value2);
                                                    }
                                                    else
                                                    {
                                                        rmdir($value2);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if (isset($_POST['editandcontinue']))
                                    {
                                        $redirect .= $directory['url'] . '&cmd=edit&title=' . $value['title'];
                                    }
                                    else
                                    {
                                        $redirect .= $directory['url'];
                                    }
                                    if (isset($_POST['add']))
                                    {
                                        $redirectmessage = $this->owner->vars['language']['addedsuccessfully'];
                                    }
                                    elseif (isset($_POST['edit']) || isset($_POST['editandcontinue']))
                                    {
                                        $redirectmessage = $this->owner->vars['language']['editedsuccessfully'];
                                    }
                                    elseif (isset($_POST['delete']))
                                    {
                                        $redirectmessage = $this->owner->vars['language']['deletedsuccessfully'];
                                    }
                                    elseif (isset($_POST['create']))
                                    {
                                        $redirectmessage = $this->owner->vars['language']['createdsuccessfully'];
                                    }
                                    elseif (isset($_POST['rename']))
                                    {
                                        $redirectmessage = $this->owner->vars['language']['renamedsuccessfully'];
                                    }
                                    elseif (isset($_POST['remove']))
                                    {
                                        $redirectmessage = $this->owner->vars['language']['removedsuccessfully'];
                                    }
                                    elseif (isset($_POST['copy']))
                                    {
                                        $redirectmessage = $this->owner->vars['language']['copiedsuccessfully'];
                                    }
                                    elseif (isset($_POST['move']))
                                    {
                                        $redirectmessage = $this->owner->vars['language']['movedsuccessfully'];
                                    }
                                    elseif (isset($_POST['replace']))
                                    {
                                        $redirectmessage = $this->owner->vars['language']['replacedsuccessfully'];
                                    }
                                    else
                                    {
                                        $redirectmessage = $this->owner->vars['language']['importedsuccessfully'];
                                    }
                                }
                            }
                        }
                    }
                }
                elseif(($_GET['cmd']=='export') || (isset($_POST['exportchecked']) && ((isset($_POST['entry']) && is_array($_POST['entry'])) || (isset($_POST['directoryentry']) && is_array($_POST['directoryentry'])))))
                {
                    if ($_GET['cmd'] == 'export')
                    {
                        $files = $_GET['title'];
                    }
                    else
                    {
                        $files = $_POST['entry'];
                    }
                    if ($_GET['cmd'] == 'export')
                    {
                        $directories = $_GET['directorytitle'];
                    }
                    else
                    {
                        $directories = $_POST['directoryentry'];
                    }
                    $nodes = array();
                    $filesarray = array();
                    $directoriesarray = array();
                    $xml = $this->owner->gettemplate($config['xml']);
                    if (is_array($files))
                    {
                        foreach ($files as $key => $value)
                        {
                            $files[$key] = $this->owner->config['files'][$type] . $directory['string'] . '/' . str_replace($illegal, '', $value) . '.' . $filetype;
                            if (!is_file($files[$key]))
                                unset($files[$key]);
                        }
                    }
                    else
                    {
                        $files = array();
                    }
                    if (is_array($directories))
                    {
                        foreach ($directories as $key => $value)
                        {
                            $directories[$key] = $this->owner->config['files'][$type] . $directory['string'] . '/' . str_replace($illegal, '', $value);
                            if (is_dir($directories[$key]))
                            {
                                $templates = array_diff($this->rscandir($directories[$key] . '/'), array('.', '..'));
                                foreach ($templates as $value2)
                                {
                                    if (is_file($value2))
                                    {
                                        $files[] = $value2;
                                    }
                                    else
                                    {
                                        $directories[] = $value2;
                                    }
                                }
                            }
                            else
                            {
                                unset($directories[$key]);
                            }
                        }
                    }
                    else
                    {
                        $directories = array();
                    }
                    if (empty($files) && empty($directories))
                    {
                        $this->owner->gettemplate($config['badrequest']);
                    }
                    $strings = array('<array>', '<code>', '<content>', '<directory>', '<file>', '<title>', '</array>', '</code>', '</content>', '</directory>', '</file>', '</title>');
                    foreach ($directories as $value)
                    {
                        $dir = $value;
                        if (substr($dir, strlen($dir) - 1) == '/')
                        {
                            $dir = substr($dir, 0, -1);
                        }
                        $dir = explode($this->owner->config['files'][$type] . $directory['string'] . '/', $dir, 2);
                        $dir = explode('/', $dir[1]);
                        $dir = array_values($dir);
                        unset($dir[count($dir) - 1]);
                        $array = array();
                        foreach ($dir as $value2)
                        {
                            $array[] = array
                            (
                                'vars' => array
                                (
                                    'arraytoken' => $this->owner->escape($strings, $value2, '\\')
                                )
                            );
                        }
                        $value = basename($value);
                        $directoriesarray[] = array
                        (
                            'vars' => array
                            (
                                'titletoken' => $this->owner->escape($strings, $value, '\\')
                            ),
                            'nodes' => $this->owner->section->loop('loop array', $array)
                        );
                    }
                    foreach ($files as $value)
                    {
                        $code = array();
                        if ($type == 'glue')
                        {
                            $array = $this->owner->explodeunescape('=', file_get_contents($value), '\\');
                            if (isset($array[0]))
                            {
                                $content = $array[0];
                            }
                            else
                            {
                                $content = '';
                            }
                            unset($array[0]);
                            foreach ($array as $value2)
                            {
                                $code[] = array
                                (
                                    'vars' => array
                                    (
                                        'codetoken' => $this->owner->escape($strings, $value2, '\\')
                                    )
                                );
                            }
                        }
                        else
                        {
                            $content = file_get_contents($value);
                        }
                        $dir = explode($this->owner->config['files'][$type] . $directory['string'] . '/', $value, 2);
                        $dir = explode('/', $dir[1]);
                        $dir = array_values($dir);
                        unset($dir[count($dir) - 1]);
                        $array = array();
                        foreach ($dir as $value2)
                        {
                            $array[] = array
                            (
                                'vars' => array
                                (
                                    'arraytoken' => $this->owner->escape($strings, $value2, '\\')
                                )
                            );
                        }
                        $title = basename($value, '.' . $filetype);
                        $filesarray[] = array
                        (
                            'vars' => array
                            (
                                'contenttoken' => $this->owner->escape($strings, $content, '\\'),
                                'titletoken' => $this->owner->escape($strings, $title, '\\')
                            ),
                            'nodes' => array_merge
                            (
                                $this->owner->section->loop('loop array', $array),
                                $this->owner->section->loop('loop code', $code)
                            )
                        );
                    }
                    $nodes = array_merge
                    (
                        $nodes,
                        $this->owner->section->loop('loop directories', $directoriesarray),
                        $this->owner->section->loop('loop files', $filesarray)
                    );
                    $xml = $this->owner->parse($nodes, $xml);
                    header('Pragma: public');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Content-type: text/xml');
                    header('Content-Disposition: attachment; filename=' . $type . '.xml');
                    header('Content-Length: ' . strlen($xml));
                    exit($xml);
                }
                elseif(isset($_POST['deletechecked']) && ((isset($_POST['entry']) && is_array($_POST['entry'])) || (isset($_POST['directoryentry']) && is_array($_POST['directoryentry']))))
                {
                    if (isset($_POST['entry']))
                    {
                        $titles = implode('&title[]=', $_POST['entry']);
                    }
                    else
                    {
                        $titles = '';
                    }
                    if (isset($_POST['directoryentry']))
                    {
                        $directorytitles = implode('&directorytitle[]=', $_POST['directoryentry']);
                    }
                    else
                    {
                        $directorytitles = '';
                    }
                    $config = array
                    (
                        'refresh' => 0
                    );
                    $this->redirect($redirect . $directory['url'] . '&cmd=delete&title[]=' . $titles . '&directorytitle[]=' . $directorytitles, '', $config);
                }
                elseif (isset($_POST['boxes']) && ($_POST['boxes'] >= 0) && isset($_POST['boxes_submit']) && in_array($_GET['cmd'], array('add', 'edit')) && $type == 'glue')
                {
                    $error = $this->owner->vars['language']['displayed'];
                }
            }
        }
        $this->owner->vars['error'] = $error;
        $nodes = array_merge($nodes, $this->owner->section->condition('if error', ($error)));
        if (isset($redirectmessage))
        {
            $templates = array_diff(scandir($this->owner->config['files'][$type] . $directory['string']), array('.', '..'));
            if (!empty($directory['array']))
            {
                $templates = array_merge
                (
                    array('..'),
                    $templates
                );
            }
            if (count($templates) <= $this->settings['start'])
            {
                $start = $this->reduce(count($templates), true);
                if ($start < 0)
                {
                    $start = 0;
                }
                $redirect = $this->path(array('boxes', 'cmd', 'directory', 'check', 'start', 'title'));
                $directory['url'] = substr($directory['url'], 1);
                $redirect = $redirect[0] . $redirect[2] . $directory['url'] . '&start=' . $start;
            }
            $this->redirect($redirect, $redirectmessage);
        }
        if((in_array($_GET['cmd'], array('add', 'copy', 'create', 'delete', 'edit', 'remove', 'rename')) && $type != 'code') || ($_GET['cmd'] == 'view' && $type == 'code'))
        {
            if ($_GET['cmd'] == 'delete')
            {
                $return = $this->owner->gettemplate($config['delete']);
            }
            else
            {
                $return = $this->owner->gettemplate($config['form']);
            }
            if (!in_array($_GET['cmd'], array('add', 'create', 'delete')))
            {
                $sectiontitle = array(htmlspecialchars($_GET['title']));
            }
            else
            {
                $sectiontitle = array();
            }
            $section = array_merge
            (
                array($this->owner->vars['language'][$_GET['cmd']]),
                $sectiontitle
            );
            $stripped = str_replace($illegal, '', $_GET['title']);
            $filepath = $this->owner->config['files'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype;
            $filepath2 = $this->owner->config['files'][$type] . $directory['string'] . '/' . $stripped;
            if((in_array($_GET['cmd'], array('edit', 'view')) && !is_file($filepath)) || (in_array($_GET['cmd'], array('rename', 'copy')) && (in_array($_GET['title'], array('.', '..')) || !is_dir($filepath2))))
            {
                $this->owner->gettemplate($config['badrequest']);
            }
            $this->owner->vars['name'] = $_GET['cmd'];
            $this->owner->vars['value'] = $section[0];
            if ($_GET['cmd'] == 'delete')
            {
                $delimiter = $this->owner->section->get('section delimiter', &$return);
                if (!empty($delimiter))
                {
                    $delimiter = $delimiter[0];
                }
                else
                {
                    $delimiter = '';
                }
                $titles = array();
                $directorytitles = array();
                if (is_array($_GET['title']))
                {
                    foreach ($_GET['title'] as $value)
                    {
                        $filepath = $this->owner->config['files'][$type] . $directory['string'] . '/' . str_replace($illegal, '', $value) . '.' . $filetype;
                        if (is_file($filepath) && !in_array($value, array('.', '..')))
                        {
                            $titles[] = array
                            (
                                'vars' => array
                                (
                                    'title' => htmlspecialchars($value)
                                )
                            );
                        }
                    }
                }
                if (is_array($_GET['directorytitle']))
                {
                    foreach ($_GET['directorytitle'] as $value)
                    {
                        $filepath = $this->owner->config['files'][$type] . $directory['string'] . '/' . str_replace($illegal, '', $value);
                        if (is_dir($filepath) && !in_array($value, array('', '.', '..')))
                        {
                            $directorytitles[] = array
                            (
                                'vars' => array
                                (
                                    'title' => htmlspecialchars($value)
                                )
                            );
                        }
                    }
                }
                if (empty($titles) && empty($directorytitles))
                {
                    $this->owner->gettemplate($config['badrequest']);
                }
                $message = $this->owner->vars['language']['deleteconfirm'];
                $message = $this->owner->parse
                (
                    array_merge
                    (
                        $nodes,
                        array_merge
                        (
                            $this->owner->section->condition('if titles', (!empty($titles))),
                            $this->owner->section->condition('if directorytitles', (!empty($directorytitles))),
                            $this->owner->section->condition('if plural', (count($titles) != 1)),
                            $this->owner->section->condition('if directoryplural', (count($directorytitles) != 1), 'else directoryplural'),
                            $this->owner->section->loop('loop titles', $titles, $delimiter),
                            $this->owner->section->loop('loop directorytitles', $directorytitles, $delimiter)
                        )
                    ),
                    $message
                );
                $this->owner->vars['message'] = $message;
                $nodes = array_merge($nodes, $this->owner->section->condition('section delimiter', false));
            }
            else
            {
                $nodes = array_merge($nodes, $this->owner->section->condition('if editing', ($_GET['cmd'] == 'edit')));
                if (!isset($posted['title']))
                {
                    $posted['title'] = $_GET['title'];
                }
                if ($type == 'glue')
                {
                    if (isset($filepath) && is_file($filepath))
                    {
                        $glue = file_get_contents($filepath);
                    }
                    else
                    {
                        $glue = '=';
                    }
                    $glue = $this->owner->explodeunescape('=', $glue, '\\');
                    if (isset($glue[0]) && !$posted['content'])
                    {
                        $posted['content'] = $glue[0];
                    }
                    unset($glue[0]);
                    if (!$posted['code'])
                    {
                        $posted['code'] = $glue;
                    }
                    if (isset($_POST['boxes']) && (intval($_POST['boxes']) >= 0))
                    {
                        $boxes = intval($_POST['boxes']);
                    }
                    else
                    {
                        $boxes = count($posted['code']);
                    }
                    $number = 1;
                    $code = array();
                    foreach ($posted['code'] as $value)
                    {
                        if ($number > $boxes)
                        {
                            break;
                        }
                        $code[] = array
                        (
                            'vars' => array
                            (
                                'code' => $value,
                                'number' => $number
                            )
                        );
                        $number++;
                    }
                    for ($number; $number <= $boxes; $number++)
                    {
                        $code[] = array
                        (
                            'vars' => array
                            (
                                'code' => '',
                                'number' => $number
                            )
                        );
                    }
                    $nodes = array_merge($nodes, $this->owner->section->loop('loop code', $code));
                    $this->owner->vars['boxes'] = $boxes;
                }
                else
                {
                    if (!isset($posted['content']))
                    {
                        $posted['content'] = '';
                    }
                    if (isset($filepath) && is_file($filepath) && !$posted['content'])
                    {
                        $posted['content'] = file_get_contents($filepath);
                    }
                }
                $this->owner->vars['content'] = htmlspecialchars(strval($posted['content']));
                $this->owner->vars['title'] = htmlspecialchars(strval($posted['title']));
            }
            $return = $this->owner->parse($nodes, $return);
        }
        else
        {
            $return = $this->owner->gettemplate($config['entries']);
            $page = $this->owner->section->get('section page', &$return);
            if (!empty($page))
            {
                $page = $page[0];
            }
            else
            {
                $page = '';
            }
            $section = array($this->owner->vars['language']['page'] . $page . ($this->settings['start'] / $this->settings['list'] + 1));
            $templates = array_diff(scandir($this->owner->config['files'][$type] . $directory['string']), array('.', '..'));
            $files = array();
            $directories = array();
            if (is_array($templates))
            {
                foreach ($templates as $key => $value)
                {
                    $pos = true;
                    if ($this->settings['search'] != '')
                    {
                        $pos = $this->owner->helper->strpos(basename($value, '.' . $filetype), $this->settings['search']);
                    }
                    if (is_file($filepath = $this->owner->config['files'][$type] . $directory['string'] . '/' . $value) && $value != basename($value, '.' . $filetype))
                    {
                        $file = false;
                        if ($type == 'glue')
                        {
                            $glue = $this->owner->explodeunescape('=', file_get_contents($filepath), '\\');
                            foreach ($glue as $value2)
                            {
                                if ($this->settings['search'] != '' && $this->owner->helper->strpos($value2, $this->settings['search']) !== false)
                                {
                                    $file = true;
                                    break;
                                }
                            }
                        }
                        elseif ($this->settings['search'] != '' && $this->owner->helper->strpos(file_get_contents($filepath), $this->settings['search']))
                        {
                            $file = true;
                        }
                        if ($pos !== false || $file)
                        {
                            $files[] = $value;
                        }
                    }
                    elseif (is_dir($this->owner->config['files'][$type] . $directory['string'] . '/' . $value) && $pos !== false)
                    {
                        $directories[] = $value;
                    }
                }
            }
            natcasesort($files);
            natcasesort($directories);
            $templates = array_merge
            (
                $directories,
                $files
            );
            if ($this->settings['order'] == 'desc')
            {
                $templates = array_reverse($templates);
            }
            if (!empty($directory['array']))
            {
                $templates = array_merge
                (
                    array('..'),
                    $templates
                );
            }
            if ($this->settings['start'] > (($count = count($templates)) - 1) && $this->settings['start'])
            {
                $this->owner->gettemplate($config['badrequest']);
            }
            $this->owner->vars['link'] = $this->pagination($count);
            $iterations = 0;
            $entries = array();
            if (!empty($templates))
            {
                $highlightstart = $this->owner->section->get('section highlightstart', &$return);
                if (!empty($highlightstart))
                {
                    $highlightstart = $highlightstart[0];
                }
                else
                {
                    $highlightstart = '';
                }
                if (!empty($highlightend))
                {
                    $highlightend = $highlightend[0];
                }
                else
                {
                    $highlightend = '';
                }
                foreach ($templates as $value)
                {
                    if ($iterations >= $this->settings['start'])
                    {
                        if (is_file($this->owner->config['files'][$type] . $directory['string'] . '/' . $value))
                        {
                            $title = basename($value, '.' . $filetype);
                        }
                        else
                        {
                            $title = $value;
                        }
                        $displaytitle = str_replace(htmlspecialchars($this->settings['search']), $highlightstart . $this->settings['search'] . $highlightend, htmlspecialchars($title));
                        $entries[] = array
                        (
                            'vars' => array
                            (
                                'displaytitle' => $displaytitle,
                                'title' => urlencode($title)
                            ),
                            'nodes' => array_merge
                            (
                                $this->owner->section->condition('if file', (is_file($this->owner->config['files'][$type] . $directory['string'] . '/' . $value)), 'else file'),
                                $this->owner->section->condition('if checked', ($this->settings['check'])),
                                $this->owner->section->condition('if up', ($value == '..'), 'else up')
                            )
                        );
                    }
                    $iterations++;
                    if ($iterations == $this->settings['start'] + $this->settings['list'])
                    {
                        break;
                    }
                }
            }
            $nodes = array_merge
            (
                $nodes,
                $this->owner->section->condition('section highlightstart', false),
                $this->owner->section->condition('section highlightend', false),
                $this->owner->section->condition('section page', false),
                $this->owner->section->condition('if entries', (!empty($entries)), 'else entries'),
                $this->owner->section->loop('loop directories', $directory['replace'])
            );
            unset($directory['replace'][count($directory['replace']) - 1]);
            $nodes = array_merge
            (
                $nodes,
                $this->owner->section->loop('loop updirectories', $directory['replace']),
                $this->owner->section->loop('loop entries', $entries)
            );
            $this->owner->vars['count'] = $count;
            $this->owner->vars['display'] = ($this->settings['start'] / $this->settings['list']) + 1;
            $this->owner->vars['list'] = urlencode($this->settings['list']);
            $this->owner->vars['order'] = urlencode($this->settings['order']);
            $this->owner->vars['search'] = urlencode($this->settings['search']);
            $this->owner->vars['start'] = urlencode($this->settings['start']);
            $return = $this->owner->parse($nodes, $return);
        }
        $array = array();
        foreach ($directory['array'] as $value)
        {
            $array[] = htmlspecialchars($value);
        }
        $section = array_merge
        (
            $array,
            $section
        );
        return array
        (
            'return' => $return,
            'section' => $section
        );
    }

    /**
    http://www.suitframework.com/docs/logistics
    **/
    public function logistics()
    {
        return ($this->settings['start'] >= 0 && $this->settings['list'] > 0 && $this->settings['start'] % $this->settings['list'] == 0);
    }

    /**
    http://www.suitframework.com/docs/pagination
    **/
    public function pagination($count, $config = array())
    {
        if (!array_key_exists('pages', $config))
        {
            $config['pages'] = $this->config['navigation']['pages'];
        }
        if (!array_key_exists('pagelink', $config))
        {
            $config['pagelink'] = $this->config['templates']['pagelink'];
        }
        $path = $this->path(array('check', 'list', 'order', 'search', 'start'));
        $return = array();
        $pagelink = $this->owner->gettemplate($config['pagelink']);
        $return['current'] = $pagelink;
        $nodes = array_merge
        (
            $this->owner->config['parse']['nodes'],
            $this->owner->section->condition('if checked', ($this->settings['check']), 'else checked'),
            $this->owner->section->condition('if current', true, 'else current')
        );
        $this->owner->vars['display'] = ($this->settings['start'] / $this->settings['list']) + 1;
        $this->owner->vars['list'] = urlencode($this->settings['list']);
        $this->owner->vars['order'] = urlencode($this->settings['order']);
        $this->owner->vars['navigationpath'] = $path;
        $this->owner->vars['search'] = urlencode($this->settings['search']);
        $this->owner->vars['start'] = urlencode($this->settings['start']);
        $return['current'] = $this->owner->parse($nodes, $return['current']);
        $num = $this->reduce($count - 1);
        $array = array();
        $result = $this->helper->pageLink($count, ($this->settings['start'] - ($this->settings['list'] * ($config['pages'] + 1))), 0, $this->owner->vars['language']['first'], false, $pagelink);
        if ($result)
        {
            $array[] = $result;
        }
        for ($x = $config['pages']; $x != 0; $x--)
        {
            $result = $this->helper->pageLink($count, ($this->settings['start'] - ($this->settings['list'] * $x)), -1, (($this->settings['start'] / $this->settings['list']) - ($x - 1)), false, $pagelink);
            if ($result)
            {
                $array[] = $result;
            }
        }
        $return['previous'] = implode(' ', $array);
        $array = array();
        for ($x = 1; $x <= $config['pages']; $x++)
        {
            $result = $this->helper->pageLink($count, ($this->settings['start'] + ($this->settings['list'] * $x)), -1, (($this->settings['start'] / $this->settings['list']) + ($x + 1)), true, $pagelink);
            if ($result)
            {
                $array[] = $result;
            }
        }
        $result = $this->helper->pageLink($count, ($this->settings['start'] + ($this->settings['list'] * ($config['pages'] + 1))), strval($num), $this->owner->vars['language']['last'], true, $pagelink);
        if ($result)
        {
            $array[] = $result;
        }
        $return['next'] = implode(' ', $array);
        return $return;
    }

    /**
    http://www.suitframework.com/docs/path
    **/
    public function path($exclude = array())
    {
        $regular = $_SERVER['SCRIPT_NAME'];
        $url = $_SERVER['SCRIPT_NAME'];
        $querychar = '?';
        $urlquerychar = '?';
        foreach ($_GET as $key => $value)
        {
            if (!in_array($key, $exclude))
            {
                if (is_array($value))
                {
                    foreach ($value as $value2)
                    {
                        $regular .= $querychar . $key . '[]=' . $value2;
                        $url .= $urlquerychar . urlencode($key) . '[]=' . urlencode($value2);
                        if ($querychar == '?')
                        {
                            $querychar = '&';
                            $urlquerychar = '&amp;';
                        }
                    }
                }
                else
                {
                    $regular .= $querychar . $key . '=' . $value;
                    $url .= $urlquerychar . urlencode($key) . '=' . urlencode($value);
                    if ($querychar == '?')
                    {
                        $querychar = '&';
                        $urlquerychar = '&amp;';
                    }
                }
            }
        }
        return array
        (
            'regular' => $regular,
            'url' => $url,
            'querychar' => $querychar,
            'urlquerychar' => $urlquerychar
        );
    }

    /**
    http://www.suitframework.com/docs/redirect
    **/
    public function redirect($url, $message = '', $config = array())
    {
        if (!array_key_exists('refresh', $config))
        {
            $config['refresh'] = $this->config['navigation']['refresh'];
        }
        if (!array_key_exists('redirect', $config))
        {
            $config['redirect'] = $this->config['templates']['redirect'];
        }
        $content = $this->owner->gettemplate($config['redirect']);
        if ($config['refresh'])
        {
            $nodes = array_merge($this->owner->config['parse']['nodes'], $this->owner->section->condition('if s', ($config['refresh'] != 1)));
            $this->owner->vars['seconds'] = $this->owner->vars['language']['seconds'];
            $this->owner->vars['refresh'] = $config['refresh'];
            $this->owner->vars['seconds'] = $this->owner->parse($nodes, $this->owner->vars['seconds']);
            $this->owner->vars['message'] = $message;
            $this->owner->vars['name'] = $this->owner->vars['language']['redirecting'];
            $this->owner->vars['url'] = htmlspecialchars($url);
            $content = $this->owner->parse($nodes, $content);
            $this->owner->vars['debug'] = $this->owner->debug;
            $debug = $this->owner->gettemplate('tie/debug');
            $nodes = array
            (
                '<debug' => array
                (
                    'close' => ' />',
                    'function' => 'nodedebug',
                    'skip' => true,
                    'var' => $debug
                )
            );
            $content = $this->owner->parse($nodes, $content);
        }
        else
        {
            $content = '';
        }
        header('refresh: ' . $config['refresh'] . '; url=' . $url);
        exit($content);
    }

    /**
    http://www.suitframework.com/docs/reduce
    **/
    public function reduce($return, $once = false)
    {
        if ($return % $this->settings['list'] || $once)
        {
            do
            {
                $return--;
            }
            while ($return % $this->settings['list']);
        }
        return $return;
    }
}

class TIEHelper
{
    public function __construct($owner)
    {
        $this->owner = $owner;
    }

    public function directorydata($array)
    {
        $return = array();
        if ($array && is_array($array))
        {
            $return['array'] = $array;
        }
        else
        {
            $return['array'] = array();
        }
        $return['string'] = '';
        $return['replace'] = array();
        $return['url'] = '';
        foreach ($return['array'] as $key => $value)
        {
            if ($value == '.' || $value == '..')
            {
                unset($return['array'][$key]);
            }
            else
            {
                $return['replace'][] = array
                (
                    'vars' => array
                    (
                        'directory' => urlencode($value)
                    )
                );
                $return['string'] .= '/' . $value;
                $return['url'] .= '&directory[]=' . $value;
            }
        }
        return $return;
    }

    public function pageLink($count, $check, $start, $display, $ahead, $pagelink)
    {
        $return = '';
        $path = $this->owner->path(array('check', 'list', 'order', 'search', 'start'));
        $success = false;
        if ($ahead)
        {
            if ($count - 1 >= $check)
            {
                $success = true;
            }
        }
        elseif ($check >= 0)
        {
            $success = true;
        }
        if ($start == -1)
        {
            $start = $check;
        }
        if ($success)
        {
            $return = $pagelink;
            $nodes = $this->owner->owner->config['parse']['nodes'];
            $nodes = array_merge
            (
                $nodes,
                $this->owner->owner->section->condition('if checked', ($this->owner->settings['check']), 'else checked'),
                $this->owner->owner->section->condition('if current', false, 'else current')
            );
            $this->owner->owner->vars['display'] = $display;
            $this->owner->owner->vars['list'] = urlencode($this->owner->settings['list']);
            $this->owner->owner->vars['order'] = urlencode($this->owner->settings['order']);
            $this->owner->owner->vars['navigationpath'] = $path;
            $this->owner->owner->vars['search'] = urlencode($this->owner->settings['search']);
            $this->owner->owner->vars['start'] = urlencode($start);
            $return = $this->owner->owner->parse($nodes, $return);
        }
        return $return;
    }

    public function rscandir($base = '', $data = array())
    {
        $array = array_diff(scandir($base), array('.', '..'));
        foreach($array as $value)
        {
            if (is_dir($base . $value))
            {
                $data[] = $base . $value . '/';
                $data = $this->owner->rscandir($base . $value . '/', $data);
            }
            elseif (is_file($base . $value))
            {
                $data[] = $base . $value;
            }
        }
        return $data;
    }
}

$suit->gettemplate('tie/config');
$suit->tie = new TIE($suit, $suit->vars['config']);
?>