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
$nodes = $suit->config['parse']['nodes'];
$path = $suit->tie->path(array('check', 'limit', 'order', 'search', 'start'));
$suit->vars['limit'] = urlencode($suit->tie->settings['limit']);
$suit->vars['order'] = urlencode($suit->tie->settings['order']);
$suit->vars['path'] = $path[1] . $path[3];
$suit->vars['search'] = urlencode($suit->tie->settings['search']);
$suit->vars['start'] = urlencode($suit->tie->settings['start']);
$nodes[] = $suit->parseConditional('if checked', ($suit->tie->settings['check']), 'else checked');
$nodes[] = $suit->parseConditional('if link', ($suit->tie->settings['order'] == 'desc'), 'else link');
$content = $suit->parse($nodes, $content);
?>