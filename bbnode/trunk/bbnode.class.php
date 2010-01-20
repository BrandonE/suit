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
                'close' => ']',
                'stringfunctions' => array
                (
                    array
                    (
                        'function' => 'bracket',
                        'class' => $this
                    )
                )
            ),
            '[align]' => array
            (
                'close' => '[/align]',
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'style'
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
                'create' => '[align]'
            ),
            '[b]' => array
            (
                'close' => '[/b]',
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'style'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'create' => '[color]'
            ),
            '[email]' => array
            (
                'close' => '[/email]',
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'create' => '[email]'
            ),
            '[font]' => array
            (
                'close' => '[/font]',
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'style'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'create' => '[font]'
            ),
            '[i]' => array
            (
                'close' => '[/i]',
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'listitems'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'create' => '[list]'
            ),
            '[s]' => array
            (
                'close' => '[/s]',
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'style'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'size'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'create' => '[size]'
            ),
            '[quote]' => array
            (
                'close' => '[/quote]',
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'create' => '[quote]'
            ),
            '[u]' => array
            (
                'close' => '[/u]',
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'template'
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
                'create' => '[url]'
            ),
        );
    }

    public function attribute($params)
    {
        if (array_key_exists('create', $params))
        {
            $params['var']['equal'] = $params['create'];
        }
        return $params;
    }

    public function bracket($params)
    {
        $params['case'] = $params['node'] . $params['case'] . $params['nodes'][$params['node']]['close'];
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
            $params['var']['template'] = $params['node'] . $params['case'] . $params['nodes'][$params['node']]['close'];
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
        $params['case'] = $params['suit']->execute($params['suit']->nodes, $params['var']['template']);
        return $params;
    }
}
?>