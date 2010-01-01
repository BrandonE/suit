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
    $templates = array();
    $parse = array();
    foreach ($suit->vars['debug']['gettemplate'] as $key => $value)
    {
        $code = array();
        foreach ($value['code'] as $key2 => $value2)
        {
            $code[] = array
            (
                'id2' => $key2,
                'ifcode' => ($value2[1]),
                'ifcodefile' => ($value2[2] !== false),
                'codefile' => htmlspecialchars($value2[2]),
                'codename' => htmlspecialchars($value2[0])
            );
        }
        $templates[] = array
        (
            'code' => $code,
            'file' => htmlspecialchars($value['file']),
            'id' => $key,
            'line' => htmlspecialchars($value['line']),
            'template' => htmlspecialchars($value['template']),
            'title' => htmlspecialchars($value['label'])
        );
    }
    foreach ($suit->vars['debug']['parse'] as $key => $value)
    {
        $preparse = $value['return'];
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
                $preparse = substr_replace($preparse, $value2, $key2, 0);
                $offset += strlen($value2);
            }
        }
        $parse[] = array
        (
            'before' => htmlspecialchars($value['before']),
            'file' => htmlspecialchars($value['file']),
            'id' => $key,
            'ifpreparse' => (array_key_exists('preparse', $value)),
            'line' => htmlspecialchars($value['line']),
            'return' => htmlspecialchars($value['return']),
            'preparse' => htmlspecialchars($preparse),
            'title' => htmlspecialchars($value['label'])
        );
    }
    $suit->vars['loop']['templates'] = $templates;
    $suit->vars['loop']['parse'] = $parse;
    $suit->vars['condition']['templates'] = (!empty($templates));
    $suit->vars['condition']['parse'] = (!empty($parse));
    $suit->vars['escapecall'] = $suit->vars['debug']['strpos']['escape']['call'];
    $suit->vars['escapecache'] = $suit->vars['debug']['strpos']['escape']['cache'];
    $suit->vars['explodeunescapecall'] = $suit->vars['debug']['strpos']['explodeunescape']['call'];
    $suit->vars['explodeunescapecache'] = $suit->vars['debug']['strpos']['explodeunescape']['cache'];
    $suit->vars['parsecall'] = $suit->vars['debug']['strpos']['parse']['call'];
    $suit->vars['parsecache'] = $suit->vars['debug']['strpos']['parse']['cache'];
    $suit->vars['totalcall'] = $suit->vars['debug']['strpos']['escape']['call'] + $suit->vars['debug']['strpos']['explodeunescape']['call'] + $suit->vars['debug']['strpos']['parse']['call'];
    $suit->vars['totalcache'] = $suit->vars['debug']['strpos']['escape']['cache'] + $suit->vars['debug']['strpos']['explodeunescape']['cache'] + $suit->vars['debug']['strpos']['parse']['cache'];
}
else
{
    $template = '';
}
?>