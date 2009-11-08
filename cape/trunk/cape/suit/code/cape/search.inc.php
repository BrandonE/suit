<?php
/**
**@This file is part of CAPE.
**@CAPE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@CAPE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with CAPE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$exclude = array('check', 'limit', 'order', 'search', 'start');
$path = $suit->tie->path($exclude);
$checked = ($suit->tie->settings['check']) ?
	'true' :
	'false';
if (isset($_POST['navigation_search_submit']) && isset($_POST['navigation_search_value']))
	$suit->tie->redirect((($suit->community->config['mod_rewrite']) ?
		$suit->community->config['mod_rewriteurl'] . '/' . $suit->vars['string'] . '/0/' . $suit->tie->settings['limit'] . '/' . $suit->tie->settings['order'] . '/' . $checked . '/' . urlencode($_POST['navigation_search_value']) . '/' :
		$path[0] . $path[2] . 'start=0&limit=' . $suit->tie->settings['limit'] . '&order=' . $suit->tie->settings['order'] . '&search=' . urlencode($_POST['navigation_search_value']) . '&check=' . $checked), false, 0);
$suit->vars['search'] = htmlentities($suit->tie->settings['search']);
?>