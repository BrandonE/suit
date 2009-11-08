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
$path = $suit->tie->path(array('check', 'limit', 'order', 'search', 'start'));
$checked = ($suit->tie->settings['check']) ?
	'true' :
	'false';
if (isset($_POST['navigation_limit_submit']) && isset($_POST['navigation_limit_value']))
	$suit->tie->redirect($path[0] . $path[2] . 'start=0&limit=' . intval($_POST['navigation_limit_value']) . '&order=' . $suit->tie->settings['order'] . '&search=' . $suit->tie->settings['search'] . '&check=' . $checked, false, 0);
$suit->vars['limit'] = intval($suit->tie->settings['limit']);
?>