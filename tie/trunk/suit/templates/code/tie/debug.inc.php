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
if ($suit->tie->config['flag']['debug'])
{
    $nodes = $suit->config['parse']['nodes'];
    $templates = array();
    $parse = array();
    foreach ($suit->vars['debug']['gettemplate'] as $key => $value)
    {
        $code = array();
        foreach ($value['code'] as $key2 => $value2)
        {
            $code[] = array
            (
                'vars' => array
                (
                    'id2' => $key2,
                    'code' => htmlspecialchars($value2[0]),
                    'codefile' => htmlspecialchars($value2[2])
                ),
                'nodes' => array_merge
                (
                    $suit->section->condition('if code', ($value2[1]), 'else code'),
                    $suit->section->condition('if codefile', ($value2[2] !== false), 'else codefile')
                )
            );
        }
        $templates[] = array
        (
            'vars' => array
            (
                'content' => htmlspecialchars($value['content'][0]),
                'contentfile' => htmlspecialchars($value['content'][2]),
                'file' => htmlspecialchars($value['file']),
                'id' => $key,
                'line' => htmlspecialchars($value['line']),
                'title' => htmlspecialchars($value['glue'][0])
            ),
            'nodes' => array_merge
            (
                $suit->section->condition('if notfound', (!$value['glue'][1]), 'else notfound'),
                $suit->section->condition('if infiniteloop', ($value['glue'][2]), 'else infiniteloop'),
                $suit->section->condition('if content', ($value['content'][1]), 'else content'),
                $suit->section->loop('loop code', $code)
            )
        );
    }
    foreach ($suit->vars['debug']['parse'] as $key => $value)
    {
        $parse[] = array
        (
            'vars' => array
            (
                'before' => htmlspecialchars($value['before']),
                'file' => htmlspecialchars($value['file']),
                'id' => $key,
                'line' => htmlspecialchars($value['line']),
                'return' => htmlspecialchars($value['return']),
                'preparse' => htmlspecialchars($value['preparse']),
                'title' => htmlspecialchars($value['label'])
            ),
            'nodes' => array_merge
            (
                $suit->section->condition('if errors', ($value['errors']), 'else errors'),
                $suit->section->condition('if preparse', (array_key_exists('preparse', $value)))
            )
        );
    }
    $suit->vars['escapecall'] = $suit->vars['debug']['strpos']['escape']['call'];
    $suit->vars['escapecache'] = $suit->vars['debug']['strpos']['escape']['cache'];
    $suit->vars['explodeunescapecall'] = $suit->vars['debug']['strpos']['explodeunescape']['call'];
    $suit->vars['explodeunescapecache'] = $suit->vars['debug']['strpos']['explodeunescape']['cache'];
    $suit->vars['parsecall'] = $suit->vars['debug']['strpos']['parse']['call'];
    $suit->vars['parsecache'] = $suit->vars['debug']['strpos']['parse']['cache'];
    $suit->vars['totalcall'] = $suit->vars['debug']['strpos']['escape']['call'] + $suit->vars['debug']['strpos']['explodeunescape']['call'] + $suit->vars['debug']['strpos']['parse']['call'];
    $suit->vars['totalcache'] = $suit->vars['debug']['strpos']['escape']['cache'] + $suit->vars['debug']['strpos']['explodeunescape']['cache'] + $suit->vars['debug']['strpos']['parse']['cache'];
    $nodes = array_merge
    (
        $nodes,
        $suit->section->condition('else templates', (empty($templates))),
        $suit->section->condition('else parse', (empty($parse))),
        $suit->section->loop('loop templates', $templates),
        $suit->section->loop('loop parse', $parse)
    );
    $content = $suit->parse($nodes, $content);
}
else
{
    $content = '';
}
?>