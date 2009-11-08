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
$array = array
(
	array('<limit>', $suit->tie->navigation->settings['limit']),
	array('<order>', $suit->tie->navigation->settings['order']),
	array('<path>', htmlentities($path)),
	array('<search>', $suit->tie->navigation->settings['search']),
	array('<start>', $suit->tie->navigation->settings['start']),
);
$content = $suit->tie->replace($array, $content);
?>