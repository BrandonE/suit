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
function slacks($params)
{
    $params['tree']['case'] = $params['var'];
    return $params;
}

class TIE
{
    public $config = array();

    public $language = array();

    public $owner;

    public $settings = array();

    public $version = '2.0.0';

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
            include 'code/languages/main.inc.php';
            $this->language = -1;
            if (isset($_COOKIE[$this->config['cookie']['prefix'] . 'language']))
            {
                $this->language = $_COOKIE[$this->config['cookie']['prefix'] . 'language'];
                if (!(isset($languages[$this->language]) || $this->language == -1))
                {
                    $this->language = -1;
                    setcookie($this->config['cookie']['prefix'] . 'language', '', time() - $this->config['cookie']['length'], $this->config['cookie']['path'], $this->config['cookie']['domain']);
                }
            }
            if ($this->language != -1)
            {
                include $languages[$this->language][1];
                $this->owner->language = $language;
            }
            else
            {
                foreach ($languages as $value)
                {
                    if ($value[2])
                    {
                        include $value[1];
                        $this->owner->language = $language;
                        break;
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
        $this->owner->condition['code'] = ($type == 'code');
        $this->owner->condition['box'] = (!in_array($_GET['cmd'], array('copy', 'create', 'rename')));
        $path = $this->path(array('check', 'cmd', 'directory', 'directorytitle', 'list', 'order', 'search', 'start', 'title'));
        $this->owner->path = $path;
        $redirect = $this->path(array('cmd', 'directory', 'directorytitle', 'check', 'title'));
        $redirect = $redirect['regular'];
        if (!$this->logistics())
        {
            $this->owner->template = file_get_contents($config['badrequest']['template']);
            $suit = $this->owner;
            include $config['badrequest']['code'];
        }
        $directory = $this->helper->directorydata($_GET['directory']);
        if (!is_dir($this->config['files'][$type] . $directory['string']))
        {
            $this->owner->template = file_get_contents($config['badrequest']['template']);
            $suit = $this->owner;
            include $config['badrequest']['code'];
        }
        $filetype = $this->config['filetypes'][$type];
        $illegal = array('/', '\\');
        $post = array('template', 'title');
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
                    $error = $this->owner->language['filenotvalid'];
                }
            }
            if ($error === false)
            {
                if (((isset($_POST['add']) || isset($_POST['edit']) || isset($_POST['editandcontinue'])) && isset($posted['title']) && isset($posted['template'])) || ((isset($_POST['copy']) || isset($_POST['create']) || isset($_POST['rename'])) && isset($posted['title'])) || (isset($_POST['import']) && isset($_FILES['file'])) || (((isset($_POST['move']) && isset($_POST['moveto'])) || (isset($_POST['replace']) && isset($_POST['find']) && isset($_POST['replacewith']))) && (isset($_POST['entry']) || isset($_POST['directoryentry']))) || isset($_POST['delete']))
                {
                    $files = array();
                    $directories = array();
                    if (isset($_POST['import']))
                    {
                        $files = array();
                        $xml = new SimpleXMLElement($upload);
                        $getfiles = $xml->xpath('/import/file');
                        foreach ($getfiles as $key => $value)
                        {
                            $filearray = array();
                            foreach ($value as $key2 => $value2)
                            {
                                $filearray[$key2] = $value2;
                            }
                            $array = $directory['array'];
                            if (isset($value->sub))
                            {
                                foreach ($value->sub as $value2)
                                {
                                    $array[] = $value2;
                                }
                            }
                            $filearray['directory'] = $array;
                            if (!isset($value->title) && !isset($value->template))
                            {
                                $error = $this->owner->language['filenotvalid'];
                                break;
                            }
                            $value->title = str_replace($illegal, '', $value->title);
                            $thisdirectory = $this->helper->directorydata($array);
                            $filepath = $this->config['files'][$type] . $thisdirectory['string'] . '/' . $value->title . '.' . $filetype;
                            if (is_file($filepath))
                            {
                                $error = $this->owner->language['duplicatetitle'];
                            }
                            $files[] = $filearray;
                        }
                    }
                    elseif (isset($_POST['delete']) && is_array($_GET['title']))
                    {
                        foreach ($_GET['title'] as $value)
                        {
                            if (is_writable($this->config['files'][$type] . $directory['string']))
                            {
                                if ($value != '')
                                {
                                    $files[] = array
                                    (
                                        'code' => array(),
                                        'template' => array(''),
                                        'directory' => $directory['array'],
                                        'oldtitle' => '',
                                        'title' => $value
                                    );
                                }
                            }
                            else
                            {
                                $error = $this->owner->language['directorynotchmod'];
                                break;
                            }
                        }
                    }
                    elseif (isset($_POST['move']) && is_array($_POST['entry']))
                    {
                        foreach ($_POST['entry'] as $value)
                        {
                            if (is_writable($this->config['files'][$type] . $directory['string']))
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
                                        'template' => file_get_contents($this->config['files'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype),
                                        'directory' => array_merge($array, $moveto),
                                        'oldtitle' => $stripped,
                                        'title' => $stripped
                                    );
                                }
                            }
                            else
                            {
                                $error = $this->owner->language['directorynotchmod'];
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
                                $template = str_replace($_POST['find'], $_POST['replacewith'], file_get_contents($this->config['files'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype));
                                $files[] = array
                                (
                                    'template' => $template,
                                    'directory' => $directory['array'],
                                    'oldtitle' => $stripped,
                                    'title' => $stripped
                                );
                            }
                        }
                    }
                    $strippedget = str_replace($illegal, '', $_GET['title']);
                    $strippedposted = str_replace($illegal, '', $posted['title']);
                    if ($error === false && (isset($_POST['copy']) || isset($_POST['create']) || isset($_POST['delete']) || isset($_POST['import']) || isset($_POST['move']) || isset($_POST['rename']) || isset($_POST['replace'])))
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
                            $directories = array();
                            $getdirectories = $xml->xpath('/import/directory');
                            foreach ($getdirectories as $key => $value)
                            {
                                $directoryarray = array();
                                $array = $directory['array'];
                                if (isset($value->sub))
                                {
                                    foreach ($value->sub as $value2)
                                    {
                                        $array[] = $value2;
                                    }
                                }
                                $directoryarray['directory'] = $array;
                                $value->title = str_replace($illegal, '', $value->title);
                                if (!isset($value->title))
                                {
                                    $error = $this->owner->language['filenotvalid'];
                                    break;
                                }
                                foreach ($value as $key2 => $value2)
                                {
                                    $directoryarray[$key2] = $value2;
                                }
                                $directories[] = $directoryarray;
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
                                        if (!is_dir($this->config['files'][$type] . $directory['string'] . '/' . $stripped))
                                        {
                                            $this->owner->template = file_get_contents($config['badrequest']['template']);
                                            $suit = $this->owner;
                                            include $config['badrequest']['code'];
                                        }
                                        $templates = array_diff($this->helper->rscandir($this->config['files'][$type] . $directory['string'] . '/' . $stripped . '/'), array('.', '..'));
                                        $templates = array_merge
                                        (
                                            array($this->config['files'][$type] . $directory['string'] . '/' . $stripped),
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
                                                $new = str_replace($this->config['files'][$type] . $directory['string'] . '/' . $stripped, $this->config['files'][$type] . $string . $moveto . $showtitle, $value2);
                                                if (substr($new, strlen($new) - 1) == '/')
                                                {
                                                    $new = substr($new, 0, -1);
                                                }
                                                $new = explode($this->config['files'][$type] . '/', $new, 2);
                                                $new = explode('/', $new[1]);
                                                $new = array_values($new);
                                                unset($new[count($new) - 1]);
                                                if (is_file($value2) && !isset($_POST['delete']))
                                                {
                                                    if (isset($_POST['replace']))
                                                    {
                                                        $template = str_replace($_POST['find'], $_POST['replacewith'], file_get_contents($value2));
                                                    }
                                                    else
                                                    {
                                                        $template = file_get_contents($value2);
                                                    }
                                                    $files[] = array
                                                    (
                                                        'template' => $template,
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
                                                $error = $this->owner->language['directorynotchmod'];
                                                break;
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $error = $this->owner->language['cannotmovedirectorytoself'];
                                }
                            }
                        }
                    }
                    else
                    {
                        $files[] = array
                        (
                            'template' => $posted['template'],
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
                            $filepath = $this->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'];
                            if (!isset($_POST['rename']) && !isset($_POST['copy']) && !isset($_POST['replace']))
                            {
                                if (!is_dir($filepath) && $value['title'] == '')
                                {
                                    $error = $this->owner->language['missingtitle'];
                                }
                                elseif (is_dir($filepath))
                                {
                                    $error = $this->owner->language['duplicatetitle'];
                                }
                            }
                            else
                            {
                                if ((!is_dir($filepath) || $value['title'] == $value['oldtitle']) && $value['title'] == '')
                                {
                                    $error = $this->owner->language['missingtitle'];
                                }
                                elseif (is_dir($filepath) && $value['title'] != $value['oldtitle'])
                                {
                                    $error = $this->owner->language['duplicatetitle'];
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
                                if (!is_dir($this->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title']))
                                {
                                    if (is_writable($this->config['files'][$type] . $thisdirectory['string']))
                                    {
                                        mkdir($this->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title']);
                                        chmod($this->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'], 0777);
                                    }
                                    else
                                    {
                                        $error = $this->owner->language['directorynotchmod'];
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
                                    $filepath = $this->config['files'][$type] . $thisdirectory['string'] . '/' . $value['oldtitle'] . '.' . $filetype;
                                    $filepath2 = $this->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype;
                                    if (!isset($_POST['delete']))
                                    {
                                        if (!isset($_POST['rename']))
                                        {
                                            if (!isset($_POST['edit']) && !isset($_POST['editandcontinue']) && !isset($_POST['replace']))
                                            {
                                                if (!is_file($filepath2) && $value['title'] == '')
                                                {
                                                    $error = $this->owner->language['missingtitle'];
                                                }
                                                elseif (is_file($filepath2))
                                                {
                                                    $error = $this->owner->language['duplicatetitle'];
                                                }
                                            }
                                            else
                                            {
                                                if (is_file($filepath))
                                                {
                                                    if ((!is_file($filepath2) || $value['title'] == $value['oldtitle']) && $value['title'] == '')
                                                    {
                                                        $error = $this->owner->language['missingtitle'];
                                                    }
                                                    elseif (is_file($filepath2) && $value['title'] != $value['oldtitle'])
                                                    {
                                                        $error = $this->owner->language['duplicatetitle'];
                                                    }
                                                }
                                                else
                                                {
                                                    $this->owner->template = file_get_contents($config['badrequest']['template']);
                                                    $suit = $this->owner;
                                                    include $config['badrequest']['code'];
                                                }
                                            }
                                        }
                                        if ($error === false)
                                        {
                                            $error = false;
                                            if (!is_file($this->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype))
                                            {
                                                if (is_writable($this->config['files'][$type] . $thisdirectory['string']))
                                                {
                                                    @touch($this->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype) or $return = $this->owner->language['filecouldnotbecreated'];
                                                    @chmod($this->config['files'][$type] . $thisdirectory['string'] . '/' . $value['title'] . '.' . $filetype, 0666);
                                                }
                                                else
                                                {
                                                    $return = $this->owner->language['directorynotchmod'];
                                                }
                                            }
                                            if ($error === false)
                                            {
                                                if (is_writable($filepath2))
                                                {
                                                    $search = array("\r\n", "\r");
                                                    $value['template'] = str_replace($search, "\n", $value['template']);
                                                    file_put_contents($filepath2, $value['template']);
                                                    if ((isset($_POST['edit']) || isset($_POST['editandcontinue'])) && $value['title'] != $value['oldtitle'])
                                                    {
                                                        unlink($filepath);
                                                    }
                                                }
                                                else
                                                {
                                                    $error = $this->owner->language['filenotchmod'];
                                                }
                                            }
                                            if ($error === false && isset($_POST['move']) && is_array($_POST['entry']) && in_array($value['title'], $_POST['entry']))
                                            {
                                                unlink($this->config['files'][$type] . $directory['string'] . '/' . $value['title'] . '.' . $filetype);
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
                                            $this->owner->template = file_get_contents($config['badrequest']['template']);
                                            $suit = $this->owner;
                                            include $config['badrequest']['code'];
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
                                                $templates = array_diff($this->helper->rscandir($this->config['files'][$type] . $directory['string'] . '/' . $value . '/'), array('.', '..'));
                                                $templates = array_reverse($templates);
                                                $templates[] = $this->config['files'][$type] . $directory['string'] . '/' . $value;
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
                                        $redirectmessage = $this->owner->language['addedsuccessfully'];
                                    }
                                    elseif (isset($_POST['edit']) || isset($_POST['editandcontinue']))
                                    {
                                        $redirectmessage = $this->owner->language['editedsuccessfully'];
                                    }
                                    elseif (isset($_POST['delete']))
                                    {
                                        $redirectmessage = $this->owner->language['deletedsuccessfully'];
                                    }
                                    elseif (isset($_POST['create']))
                                    {
                                        $redirectmessage = $this->owner->language['createdsuccessfully'];
                                    }
                                    elseif (isset($_POST['rename']))
                                    {
                                        $redirectmessage = $this->owner->language['renamedsuccessfully'];
                                    }
                                    elseif (isset($_POST['remove']))
                                    {
                                        $redirectmessage = $this->owner->language['removedsuccessfully'];
                                    }
                                    elseif (isset($_POST['copy']))
                                    {
                                        $redirectmessage = $this->owner->language['copiedsuccessfully'];
                                    }
                                    elseif (isset($_POST['move']))
                                    {
                                        $redirectmessage = $this->owner->language['movedsuccessfully'];
                                    }
                                    elseif (isset($_POST['replace']))
                                    {
                                        $redirectmessage = $this->owner->language['replacedsuccessfully'];
                                    }
                                    else
                                    {
                                        $redirectmessage = $this->owner->language['importedsuccessfully'];
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
                    $filesarray = array();
                    $directoriesarray = array();
                    $xml = file_get_contents($config['xml']['template']);
                    if (is_array($files))
                    {
                        foreach ($files as $key => $value)
                        {
                            $files[$key] = $this->config['files'][$type] . $directory['string'] . '/' . str_replace($illegal, '', $value) . '.' . $filetype;
                            if (!is_file($files[$key]))
                            {
                                unset($files[$key]);
                            }
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
                            $directories[$key] = $this->config['files'][$type] . $directory['string'] . '/' . str_replace($illegal, '', $value);
                            if (is_dir($directories[$key]))
                            {
                                $templates = array_diff($this->helper->rscandir($directories[$key] . '/'), array('.', '..'));
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
                        $this->owner->template = file_get_contents($config['badrequest']['template']);
                        $suit = $this->owner;
                        include $config['badrequest']['code'];
                    }
                    foreach ($directories as $value)
                    {
                        $dir = $value;
                        if (substr($dir, strlen($dir) - 1) == '/')
                        {
                            $dir = substr($dir, 0, -1);
                        }
                        $dir = explode($this->config['files'][$type] . $directory['string'] . '/', $dir, 2);
                        $dir = explode('/', $dir[1]);
                        $dir = array_values($dir);
                        unset($dir[count($dir) - 1]);
                        $array = array();
                        foreach ($dir as $value2)
                        {
                            $array[] = array
                            (
                                'arraytoken' => htmlentities($value2)
                            );
                        }
                        $value = basename($value);
                        $directoriesarray[] = array
                        (
                            'titletoken' => htmlentities($value),
                            'array' => $array
                        );
                    }
                    foreach ($files as $value)
                    {
                        $content = file_get_contents($value);
                        $dir = explode($this->config['files'][$type] . $directory['string'] . '/', $value, 2);
                        $dir = explode('/', $dir[1]);
                        $dir = array_values($dir);
                        unset($dir[count($dir) - 1]);
                        $array = array();
                        foreach ($dir as $value2)
                        {
                            $array[] = array
                            (
                                'arraytoken' => htmlentities($value2)
                            );
                        }
                        $title = basename($value, '.' . $filetype);
                        $filesarray[] = array
                        (
                            'array' => $array,
                            'templatetoken' => htmlentities($content),
                            'titletoken' => htmlentities($title)
                        );
                    }
                    $this->owner->loop['directories'] = $directoriesarray;
                    $this->owner->loop['files'] = $filesarray;
                    $xml = $this->owner->execute($this->owner->rules, $xml);
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
            }
        }
        $this->owner->error = $error;
        if (isset($redirectmessage))
        {
            $templates = array_diff(scandir($this->config['files'][$type] . $directory['string']), array('.', '..'));
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
                $redirect = $redirect['regular'] . $directory['url'] . '&start=' . $start;
            }
            $section = array
            (
                array
                (
                    'title' => $this->owner->language[$_GET['section']]
                )
            );
            foreach ($directory['array'] as $value)
            {
                $section[] = array
                (
                    'title' => htmlspecialchars($value)
                );
            }
            $section[] = array
            (
                'title' => $redirectmessage
            );
            $this->owner->loop['section'] = $section;
            $this->redirect($redirect, $redirectmessage);
        }
        if((in_array($_GET['cmd'], array('add', 'copy', 'create', 'delete', 'edit', 'remove', 'rename')) && $type != 'code') || ($_GET['cmd'] == 'view' && $type == 'code'))
        {
            if ($_GET['cmd'] == 'delete')
            {
                $return = file_get_contents($config['delete']['template']);
            }
            else
            {
                $return = file_get_contents($config['form']['template']);
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
                array($this->owner->language[$_GET['cmd']]),
                $sectiontitle
            );
            $stripped = str_replace($illegal, '', $_GET['title']);
            $filepath = $this->config['files'][$type] . $directory['string'] . '/' . $stripped . '.' . $filetype;
            $filepath2 = $this->config['files'][$type] . $directory['string'] . '/' . $stripped;
            if((in_array($_GET['cmd'], array('edit', 'view')) && !is_file($filepath)) || (in_array($_GET['cmd'], array('rename', 'copy')) && (in_array($_GET['title'], array('.', '..')) || !is_dir($filepath2))))
            {
                $this->owner->template = file_get_contents($config['badrequest']['template']);
                $suit = $this->owner;
                include $config['badrequest']['code'];
            }
            $this->owner->name = $_GET['cmd'];
            $this->owner->value = $section[0];
            if ($_GET['cmd'] == 'delete')
            {
                $titles = array();
                $directorytitles = array();
                if (is_array($_GET['title']))
                {
                    foreach ($_GET['title'] as $value)
                    {
                        $filepath = $this->config['files'][$type] . $directory['string'] . '/' . str_replace($illegal, '', $value) . '.' . $filetype;
                        if (is_file($filepath) && !in_array($value, array('.', '..')))
                        {
                            $titles[] = array
                            (
                                'title' => htmlspecialchars($value)
                            );
                        }
                    }
                }
                if (is_array($_GET['directorytitle']))
                {
                    foreach ($_GET['directorytitle'] as $value)
                    {
                        $filepath = $this->config['files'][$type] . $directory['string'] . '/' . str_replace($illegal, '', $value);
                        if (is_dir($filepath) && !in_array($value, array('', '.', '..')))
                        {
                            $directorytitles[] = array
                            (
                                'title' => htmlspecialchars($value)
                            );
                        }
                    }
                }
                if (empty($titles) && empty($directorytitles))
                {
                    $this->owner->template = file_get_contents($config['badrequest']['template']);
                    $suit = $this->owner;
                    include $config['badrequest']['code'];
                }
                $message = $this->owner->language['deleteconfirm'];
                $this->owner->condition['titles'] = (!empty($titles));
                $this->owner->condition['directorytitles'] = (!empty($directorytitles));
                $this->owner->condition['plural'] = (count($titles) != 1);
                $this->owner->condition['directoryplural'] = (count($directorytitles) != 1);
                $this->owner->loop['titles'] = $titles;
                $this->owner->loop['directorytitles'] = $directorytitles;
                $this->owner->message = $message;
            }
            else
            {
                $this->owner->condition['editing'] = ($_GET['cmd'] == 'edit');
                if (!isset($posted['title']))
                {
                    $posted['title'] = $_GET['title'];
                }
                if (!isset($posted['template']))
                {
                    $posted['template'] = '';
                }
                if (isset($filepath) && is_file($filepath) && !$posted['template'])
                {
                    $posted['template'] = file_get_contents($filepath);
                }
                $this->owner->template = htmlspecialchars(strval($posted['template']));
                $this->owner->title = htmlspecialchars(strval($posted['title']));
            }
            $return = $this->owner->execute($this->owner->rules, $return);
        }
        else
        {
            $return = file_get_contents($config['entries']['template']);
            $section = array($this->owner->language['page'] . ($this->settings['start'] / $this->settings['list'] + 1));
            $templates = array_diff(scandir($this->config['files'][$type] . $directory['string']), array('.', '..'));
            $files = array();
            $directories = array();
            if (is_array($templates))
            {
                foreach ($templates as $key => $value)
                {
                    $pos = true;
                    if ($this->settings['search'] != '')
                    {
                        $pos = stripos(basename($value, '.' . $filetype), $this->settings['search']);
                    }
                    $filepath = $this->config['files'][$type] . $directory['string'] . '/' . $value;
                    if (is_file($filepath) && $value != basename($value, '.' . $filetype))
                    {
                        $file = false;
                        if ($this->settings['search'] != '' && stripos(file_get_contents($filepath), $this->settings['search']))
                        {
                            $file = true;
                        }
                        if ($pos !== false || $file)
                        {
                            $files[] = $value;
                        }
                    }
                    elseif (is_dir($this->config['files'][$type] . $directory['string'] . '/' . $value) && $pos !== false)
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
                $this->owner->template = file_get_contents($config['badrequest']['template']);
                $suit = $this->owner;
                include $config['badrequest']['code'];
            }
            $this->owner->link = $this->pagination($count);
            $iterations = 0;
            $entries = array();
            if (!empty($templates))
            {
                $this->owner->highlight = htmlspecialchars($this->settings['search']);
                foreach ($templates as $value)
                {
                    if ($iterations >= $this->settings['start'])
                    {
                        if (is_file($this->config['files'][$type] . $directory['string'] . '/' . $value))
                        {
                            $title = basename($value, '.' . $filetype);
                        }
                        else
                        {
                            $title = $value;
                        }
                        $entries[] = array
                        (
                            'file' => (is_file($this->config['files'][$type] . $directory['string'] . '/' . $value)),
                            'displaytitle' => htmlspecialchars($title),
                            'title' => urlencode($title),
                            'up' => ($value == '..')
                        );
                    }
                    $iterations++;
                    if ($iterations == $this->settings['start'] + $this->settings['list'])
                    {
                        break;
                    }
                }
            }
            $this->owner->loop['directories'] = $directory['loop'];
            unset($directory['loop'][count($directory['loop']) - 1]);
            $this->owner->loop['updirectories'] = $directory['loop'];
            $this->owner->loop['entries'] = $entries;
            $this->owner->condition['entries'] = (!empty($entries));
            $this->owner->condition['desc'] = ($this->settings['order'] == 'desc');
            $this->owner->condition['checked'] = ($this->settings['check']);
            $this->owner->count = $count;
            $this->owner->display = ($this->settings['start'] / $this->settings['list']) + 1;
            $this->owner->list = urlencode($this->settings['list']);
            $this->owner->search = urlencode($this->settings['search']);
            $this->owner->start = urlencode($this->settings['start']);
            $return = $this->owner->execute($this->owner->rules, $return);
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
        $pagelink = file_get_contents($config['pagelink']['template']);
        $return['current'] = $pagelink;
        $this->owner->condition['checked'] = ($this->settings['check']);
        $this->owner->condition['current'] = true;
        $this->owner->display = ($this->settings['start'] / $this->settings['list']) + 1;
        $this->owner->list = urlencode($this->settings['list']);
        $this->owner->order = urlencode($this->settings['order']);
        $this->owner->navigationpath = $path;
        $this->owner->search = urlencode($this->settings['search']);
        $this->owner->start = urlencode($this->settings['start']);
        $return['current'] = $this->owner->execute($this->owner->rules, $return['current']);
        $num = $this->reduce($count - 1);
        $array = array();
        $result = $this->helper->pagelink($count, ($this->settings['start'] - ($this->settings['list'] * ($config['pages'] + 1))), 0, $this->owner->language['first'], false, $pagelink);
        if ($result)
        {
            $array[] = $result;
        }
        for ($x = $config['pages']; $x != 0; $x--)
        {
            $result = $this->helper->pagelink($count, ($this->settings['start'] - ($this->settings['list'] * $x)), -1, (($this->settings['start'] / $this->settings['list']) - ($x - 1)), false, $pagelink);
            if ($result)
            {
                $array[] = $result;
            }
        }
        $return['previous'] = implode(' ', $array);
        $array = array();
        for ($x = 1; $x <= $config['pages']; $x++)
        {
            $result = $this->helper->pagelink($count, ($this->settings['start'] + ($this->settings['list'] * $x)), -1, (($this->settings['start'] / $this->settings['list']) + ($x + 1)), true, $pagelink);
            if ($result)
            {
                $array[] = $result;
            }
        }
        $result = $this->helper->pagelink($count, ($this->settings['start'] + ($this->settings['list'] * ($config['pages'] + 1))), strval($num), $this->owner->language['last'], true, $pagelink);
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
        $content = file_get_contents($config['redirect']['template']);
        if ($config['refresh'])
        {
            $this->owner->condition['s'] = ($config['refresh'] != 1);
            $this->owner->seconds = $this->owner->language['seconds'];
            $this->owner->refresh = $config['refresh'];
            $this->owner->message = $message;
            $this->owner->name = $this->owner->language['redirecting'];
            $this->owner->url = htmlspecialchars($url);
            $content = $this->owner->execute($this->owner->rules, $content);
            $nodes = array
            (
                '<slacks' => array
                (
                    'close' => '/>',
                    'function' => array
                    (
                        array
                        (
                            'function' => 'slacks'
                        )
                    ),
                    'skip' => true,
                    'var' => htmlentities(json_encode($this->owner->log))
                )
            );
            $content = $this->owner->execute($nodes, $content);
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
        $return['loop'] = array();
        $return['url'] = '';
        foreach ($return['array'] as $key => $value)
        {
            if ($value == '.' || $value == '..')
            {
                unset($return['array'][$key]);
            }
            else
            {
                $return['loop'][] = array
                (
                    'directory' => urlencode($value)
                );
                $return['string'] .= '/' . $value;
                $return['url'] .= '&directory[]=' . $value;
            }
        }
        return $return;
    }

    public function pagelink($count, $check, $start, $display, $ahead, $pagelink)
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
            $this->owner->owner->condition['checked'] = ($this->owner->settings['check']);
            $this->owner->owner->condition['current'] = false;
            $this->owner->owner->display = $display;
            $this->owner->owner->list = urlencode($this->owner->settings['list']);
            $this->owner->owner->order = urlencode($this->owner->settings['order']);
            $this->owner->owner->navigationpath = $path;
            $this->owner->owner->search = urlencode($this->owner->settings['search']);
            $this->owner->owner->start = urlencode($start);
            $return = $this->owner->owner->execute($this->owner->owner->rules, $return);
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
                $data = $this->rscandir($base . $value . '/', $data);
            }
            elseif (is_file($base . $value))
            {
                $data[] = $base . $value;
            }
        }
        return $data;
    }
}
require 'code/tie/config.inc.php';
$suit->tie = new TIE($suit, $config);
?>