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
$suit->loop['section'] = array
(
    array
    (
        'title' => $suit->language['dashboard']
    )
);
$suit->path = $suit->tie->path(array('check', 'cmd', 'directory', 'directorytitle', 'list', 'order', 'search', 'section', 'start', 'title'));
$suit->template = $suit->execute($suit->nodes, $suit->template);
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
exit($suit->execute($nodes, $suit->template));
?>