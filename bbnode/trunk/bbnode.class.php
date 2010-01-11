<?php
/**
**@This file is part of BBNode.
**@BBNode is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@BBNode is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with BBNode.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class BBNode
{
    public $version = '0.0.0';

    public function __construct()
    {
        $this->nodes = array
        (
            '[' => array
            (
                'close' => ']'
            ),
            '[align]' => array
            (
                'close' => '[/align]',
                'function' => array
                (
                    array
                    (
                        'function' => 'style',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '',
                    'label' => 'align',
                    'template' => ''
                )
            ),
            '[align=' => array
            (
                'close' => ']',
                'function' => array
                (
                    array
                    (
                        'function' => 'attribute',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'stack',
                        'class' => $this
                    )
                ),
                'attribute' => '[align]'
            ),
            '[b]' => array
            (
                'close' => '[/b]',
                'function' => array
                (
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '',
                    'label' => 'b',
                    'template' => ''
                )
            ),
            '[code]' => array
            (
                'close' => '[/code]',
                'function' => array
                (
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'skip' => true,
                'var' => array
                (
                    'equal' => '',
                    'label' => 'code',
                    'template' => ''
                )
            ),
            '[color]' => array
            (
                'close' => '[/color]',
                'function' => array
                (
                    array
                    (
                        'function' => 'style',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '',
                    'label' => 'color',
                    'template' => ''
                )
            ),
            '[color=' => array
            (
                'close' => ']',
                'function' => array
                (
                    array
                    (
                        'function' => 'attribute',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'stack',
                        'class' => $this
                    )
                ),
                'attribute' => '[color]'
            ),
            '[email]' => array
            (
                'close' => '[/email]',
                'function' => array
                (
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '',
                    'label' => 'email',
                    'template' => ''
                )
            ),
            '[email=' => array
            (
                'close' => ']',
                'function' => array
                (
                    array
                    (
                        'function' => 'attribute',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'stack',
                        'class' => $this
                    )
                ),
                'attribute' => '[email]'
            ),
            '[font]' => array
            (
                'close' => '[/font]',
                'function' => array
                (
                    array
                    (
                        'function' => 'style',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => 'serif',
                    'label' => 'font',
                    'template' => ''
                )
            ),
            '[font=' => array
            (
                'close' => ']',
                'function' => array
                (
                    array
                    (
                        'function' => 'attribute',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'stack',
                        'class' => $this
                    )
                ),
                'attribute' => '[font]'
            ),
            '[i]' => array
            (
                'close' => '[/i]',
                'function' => array
                (
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '',
                    'label' => 'i',
                    'template' => ''
                )
            ),
            '[img]' => array
            (
                'close' => '[/img]',
                'function' => array
                (
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '',
                    'label' => 'img',
                    'template' => ''
                )
            ),
            '[list]' => array
            (
                'close' => '[/list]',
                'function' => array
                (
                    array
                    (
                        'function' => 'listitems',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'close' => '</li>',
                    'delimiter' => '[*]',
                    'equal' => '',
                    'label' => 'list',
                    'open' => '<li>',
                    'template' => ''
                )
            ),
            '[list=' => array
            (
                'close' => ']',
                'function' => array
                (
                    array
                    (
                        'function' => 'attribute',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'stack',
                        'class' => $this
                    )
                ),
                'attribute' => '[list]'
            ),
            '[s]' => array
            (
                'close' => '[/s]',
                'function' => array
                (
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '',
                    'label' => 's',
                    'template' => ''
                )
            ),
            '[size]' => array
            (
                'close' => '[/size]',
                'function' => array
                (
                    array
                    (
                        'function' => 'style',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'size',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '3',
                    'label' => 'size',
                    'template' => ''
                )
            ),
            '[size=' => array
            (
                'close' => ']',
                'function' => array
                (
                    array
                    (
                        'function' => 'attribute',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'stack',
                        'class' => $this
                    )
                ),
                'attribute' => '[size]'
            ),
            '[quote]' => array
            (
                'close' => '[/quote]',
                'function' => array
                (
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '',
                    'label' => 'quote',
                    'template' => ''
                )
            ),
            '[quote=' => array
            (
                'close' => ']',
                'function' => array
                (
                    array
                    (
                        'function' => 'attribute',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'stack',
                        'class' => $this
                    )
                ),
                'attribute' => '[quote]'
            ),
            '[u]' => array
            (
                'close' => '[/u]',
                'function' => array
                (
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '',
                    'label' => 'u',
                    'template' => ''
                )
            ),
            '[url]' => array
            (
                'close' => '[/url]',
                'function' => array
                (
                    array
                    (
                        'function' => 'template',
                        'class' => $this
                    )
                ),
                'var' => array
                (
                    'equal' => '',
                    'label' => 'url',
                    'template' => ''
                )
            ),
            '[url=' => array
            (
                'close' => ']',
                'function' => array
                (
                    array
                    (
                        'function' => 'attribute',
                        'class' => $this
                    ),
                    array
                    (
                        'function' => 'stack',
                        'class' => $this
                    )
                ),
                'attribute' => '[url]'
            ),
        );
    }

    public function attribute($params)
    {
        $params['var']['node'] = $params['nodes'][$params['open']['node']['attribute']];
        $params['var']['node']['var']['equal'] = $params['case'];
        return $params;
    }

    public function listitems($params)
    {
        if (!$params['var']['equal'] || in_array($params['var']['equal'], array('1', 'a', 'A', 'i', 'I')))
        {
            $params['case'] = str_replace('<br />', '', $params['case']);
            $params['case'] = explode($params['var']['delimiter'], $params['case']);
            $size = count($params['case']);
            for ($i = 0; $i < $size; $i++)
            {
                if ($i != 0)
                {
                    $params['case'][$i] = $params['var']['open'] . $params['case'][$i] . $params['var']['close'];
                }
            }
            $params['case'] = implode('', $params['case']);
        }
        else
        {
            $params['var']['template'] = $params['open']['open'] . $params['case']. $params['open']['node']['close'];
        }
        return $params;
    }

    public function size($params)
    {
        $params['var']['equal'] = intval($params['var']['equal']) + 7;
        if ($params['var']['equal'] > 30)
        {
            $params['var']['equal'] = 30;
        }
        return $params;
    }

    public function stack($params)
    {
        $params['case'] = $params['open']['open'] . $params['case'] . $params['open']['node']['close'];
        $params['taken'] = false;
        //Add the new node to the stack
        $newstack = $params['suit']->stack($params['var']['node'], $params['case'], $params['open']['position']);
        $params['openingstack'] = array_merge($params['openingstack'], $newstack['openingstack']);
        $params['skipstack'] = array_merge($params['skipstack'], $newstack['skipstack']);
        $params['preparse']['nodes'][$params['case']] = $params['var']['node'];
        return $params;
    }

    public function style($params)
    {
        $explode = explode(';', $params['var']['equal'], 2);
        $params['var']['equal'] = $explode[0];
        $params['var']['equal'] = str_replace('"', '', str_replace('\'', '', $params['var']['equal']));
        return $params;
    }

    public function template($params)
    {
        $params['suit']->case = $params['case'];
        $params['suit']->equal = $params['var']['equal'];
        $params['case'] = $params['suit']->parse($params['suit']->nodes, $params['var']['template']);
        return $params;
    }
}
?>