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

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
if ($suit->tie->config['flag']['debug'])
{
    $parse = array();
    foreach ($suit->debugging['parse'] as $key => $value)
    {
        $ranges = $value['return'];
        if (array_key_exists('preparse', $value))
        {
            $pos = array();
            foreach ($value['preparse']['taken'] as $value2)
            {
                if (!array_key_exists($value2[1], $pos))
                {
                    $pos[$value2[1]] = '';
                }
                $pos[$value2[1]] .= '[/taken]';
            }
            foreach ($value['preparse']['ignored'] as $value2)
            {
                if (!array_key_exists($value2[1], $pos))
                {
                    $pos[$value2[1]] = '';
                }
                $pos[$value2[1]] .= '[/ignored]';
            }
            foreach ($value['preparse']['taken'] as $value2)
            {
                if (!array_key_exists($value2[0], $pos))
                {
                    $pos[$value2[0]] = '';
                }
                $pos[$value2[0]] .= '[taken]';
            }
            foreach ($value['preparse']['ignored'] as $value2)
            {
                if (!array_key_exists($value2[0], $pos))
                {
                    $pos[$value2[0]] = '';
                }
                $pos[$value2[0]] .= '[ignored]';
            }
            ksort($pos);
            $offset = 0;
            foreach ($pos as $key2 => $value2)
            {
                $key2 += $offset;
                $ranges = substr_replace($ranges, $value2, $key2, 0);
                $offset += strlen($value2);
            }
        }
        $parse[] = array
        (
            'before' => htmlspecialchars($value['before']),
            'file' => htmlspecialchars($value['file']),
            'id' => $key,
            'ifranges' => (array_key_exists('preparse', $value)),
            'line' => htmlspecialchars($value['line']),
            'ranges' => htmlspecialchars($ranges),
            'return' => htmlspecialchars($value['return']),
            'title' => htmlspecialchars($value['label'])
        );
    }
    $suit->loop['parse'] = $parse;
    $suit->condition['parse'] = (!empty($parse));
    $suit->escapecall = $suit->debugging['strpos']['escape']['call'];
    $suit->escapecache = $suit->debugging['strpos']['escape']['cache'];
    $suit->explodeunescapecall = $suit->debugging['strpos']['explodeunescape']['call'];
    $suit->explodeunescapecache = $suit->debugging['strpos']['explodeunescape']['cache'];
    $suit->parsecall = $suit->debugging['strpos']['parse']['call'];
    $suit->parsecache = $suit->debugging['strpos']['parse']['cache'];
    $suit->totalcall = $suit->debugging['strpos']['escape']['call'] + $suit->debugging['strpos']['explodeunescape']['call'] + $suit->debugging['strpos']['parse']['call'];
    $suit->totalcache = $suit->debugging['strpos']['escape']['cache'] + $suit->debugging['strpos']['explodeunescape']['cache'] + $suit->debugging['strpos']['parse']['cache'];
}
?>