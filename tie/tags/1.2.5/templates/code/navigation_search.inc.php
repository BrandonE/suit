<?php
/**
**@This file is part of TIE.
**@TIE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@TIE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with TIE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$exclude = array('limit', 'order', 'search', 'select', 'start');
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], $exclude);
if (isset($_POST['navigation_search_submit']) && isset($_POST['navigation_search_value']))
	$suit->tie->navigation->redirect($path . 'start=0&limit=' . $suit->tie->navigation->settings['limit'] . '&order=' . $suit->tie->navigation->settings['order'] . '&search=' . urlencode($_POST['navigation_search_value']), false, 0);
$content = str_replace('<search>', $suit->tie->navigation->settings['search'], $content);
?>