<?php
/**
**@This program is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@This program is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class Section
{
    public $sections = array();

    public function __construct($owner)
    {
        $this->owner = $owner;
    }

    /**
    http://www.suitframework.com/docs/get
    **/
    public function get($string, $content, $config = array())
    {
        if (!array_key_exists('open', $config))
        {
            $config['open'] = $this->owner->config['parse']['section']['open'];
        }
        if (!array_key_exists('close', $config))
        {
            $config['close'] = $this->owner->config['parse']['section']['close'];
        }
        if (!array_key_exists('end', $config))
        {
            $config['end'] = $this->owner->config['parse']['section']['end'];
        }
        if (!array_key_exists('escape', $config))
        {
            $config['escape'] = $this->owner->config['parse']['section']['escape'];
        }
        $nodes = array
        (
            $config['open'] . $string . $config['close'] => array
            (
                'close' => $config['open'] . $config['end'] . $string . $config['close'],
                'function' => array
                (
                    array
                    (
                        'function' => 'getsection',
                        'class' => $this->owner->nodes
                    )
                )
            )
        );
        $this->sections = array();
        //Unescape when applicable, and populate sections with the inside of each section
        $content = $this->owner->parse($nodes, $content, $config);
        return $this->sections;
    }
}
?>