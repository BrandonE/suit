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
$path = $suit->tie->path(array('check', 'list', 'order', 'search', 'start'));
$suit->vars['condition']['checked'] = ($suit->tie->settings['check']);
$suit->vars['condition']['desc'] = ($suit->tie->settings['order'] == 'desc');
$suit->vars['list'] = urlencode($suit->tie->settings['list']);
$suit->vars['order'] = urlencode($suit->tie->settings['order']);
$suit->vars['navigationpath'] = $path;
$suit->vars['search'] = urlencode($suit->tie->settings['search']);
$suit->vars['start'] = urlencode($suit->tie->settings['start']);
?>