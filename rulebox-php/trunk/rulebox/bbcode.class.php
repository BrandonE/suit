<?php
/**
**@This file is part of Rulebox.
**@Rulebox is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@Rulebox is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with Rulebox.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class BBCode
{
    public function __construct()
    {
        $this->rules = array
        (
            '[' => array
            (
                'close' => ']',
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'linebreaks'
                    ),
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
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
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
                'postwalk' => array
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
        $params['tree']['case'] = $params['tree']['rule'] . $params['tree']['case'] . $params['rules'][$params['tree']['rule']]['close'];
        return $params;
    }

    public function linebreaks($params)
    {
        $params['tree']['case'] = str_replace('<br />', '', $params['tree']['case']);
        return $params;
    }

    public function listitems($params)
    {
        if (!$params['var']['equal'] || in_array($params['var']['equal'], array('1', 'a', 'A', 'i', 'I')))
        {
            $params['tree']['case'] = str_replace('<br />', '', $params['tree']['case']);
            $params['tree']['case'] = explode($params['var']['delimiter'], $params['tree']['case']);
            $size = count($params['tree']['case']);
            for ($i = 0; $i < $size; $i++)
            {
                if ($i != 0)
                {
                    $params['tree']['case'][$i] = $params['var']['open'] . $params['tree']['case'][$i] . $params['var']['close'];
                }
            }
            $params['tree']['case'] = implode('', $params['tree']['case']);
        }
        else
        {
            $params['var']['template'] = $params['tree']['rule'] . $params['tree']['case'] . $params['rules'][$params['tree']['rule']]['close'];
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
        $params['suit']->var->case = $params['tree']['case'];
        $params['suit']->var->equal = $params['var']['equal'];
        $params['tree']['case'] = $params['suit']->execute($params['suit']->rules, $params['var']['template']);
        return $params;
    }
}
?>