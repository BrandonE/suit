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
$path = $suit->tie->navigation->path($_SERVER['SCRIPT_NAME'], array('limit', 'order', 'search', 'start'));
if (isset($_POST['navigation_limit_submit']) && isset($_POST['navigation_limit_value']))
	$suit->tie->navigation->redirect($path . 'start=0&limit=' . intval($_POST['navigation_limit_value']) . '&order=' . $suit->tie->navigation->settings['order'] . '&search=' . $suit->tie->navigation->settings['search'], false, 0);
$content = str_replace('<limit>', $suit->tie->navigation->settings['limit'], $content);
?>