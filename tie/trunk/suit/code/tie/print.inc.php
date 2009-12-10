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
if ($_GET['css'] == 'true')
{
    header('Content-type: text/css');
    exit(file_get_contents($suit->vars['files']['templates'] . '/tie/css.tpl'));
}
if ($_GET['jquery'] == 'true')
{
    header('Content-type: text/javascript');
    exit(file_get_contents($suit->vars['files']['templates'] . '/tie/jquery.tpl'));
}
if ($_GET['javascript'] == 'true')
{
    header('Content-type: text/javascript');
    exit(file_get_contents($suit->vars['files']['templates'] . '/tie/javascript.tpl'));
}
?>