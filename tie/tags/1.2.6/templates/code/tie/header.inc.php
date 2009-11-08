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
$exclude = array('check', 'cmd', 'directory', 'directorytitle', 'limit', 'order', 'search', 'section', 'start', 'title');
$templates = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
$dashboard = substr($templates, 0, -1);
$content = $suit->tie->replace($suit->tie->parseConditional('if admin', (in_array('tie/index', $suit->chain)), $content), $content);
$array = array
(
	array('<dashboard>', htmlentities($dashboard)),
	array('<templates>', htmlentities($templates))
);
$content = $suit->tie->replace($array, $content);
?>