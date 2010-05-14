<?php
/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits

A set of rules used to transform BBCode into HTML.

-----------------------------
Example Usage
-----------------------------

::

    require 'suit.class.php';
    require 'templating.class.php';
    require 'bbcode.class.php';
    $suit = new SUIT();
    $templating = new Templating($suit);
    $bbcode = new BBCode($suit, $templating);
    $rules = $bbcode->rules;
    // Load the BBCode templates
    foreach ($rules as $key => $value)
    {
        if (array_key_exists('var', $value) && array_key_exists('label', $value['var']))
        {
            $rules[$key]['var']['template'] = file_get_contents('bbcode/' . $value['var']['label'] . '.tpl');
        }
    }
    code = nl2br(htmlentities('[b]Test[/b]'));
    $config = array
    (
        'escape' => ''
    );
    echo $suit->execute($rules, $code, $config);
    // Result: assuming it loaded the default templates, "<strong>Test</strong>"

Basic usage; see http://www.suitframework.com/docs/ for how to use other rules.
*/

class BBCode
{
    public function __construct($suit, $templating)
    {
        $this->suit = $suit;

        $this->rules = array
        (
            '[' => $templating->rules['['],
            '[align]' => array
            (
                'close' => '[/align]',
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
                    ),
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
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
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
                    'label' => 'b',
                    'template' => ''
                )
            ),
            '[code]' => array
            (
                'close' => '[/code]',
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
                    ),
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
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
                    ),
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
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
                    ),
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
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
                    ),
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
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
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
                    'label' => 'i',
                    'template' => ''
                )
            ),
            '[img]' => array
            (
                'close' => '[/img]',
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
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
                    'label' => 'img',
                    'template' => ''
                )
            ),
            '[list]' => array
            (
                'close' => '[/list]',
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
                    ),
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
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
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
                    'label' => 's',
                    'template' => ''
                )
            ),
            '[size]' => array
            (
                'close' => '[/size]',
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
                    ),
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
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
                    ),
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
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
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
                    'label' => 'u',
                    'template' => ''
                )
            ),
            '[url]' => array
            (
                'close' => '[/url]',
                'functions' => array
                (
                    array
                    (
                        'class' => $templating,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $templating,
                        'function' => 'copyvar'
                    ),
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

        $this->templating = $templating;
    }

    public function attribute($params)
    {
        if (array_key_exists('create', $params['tree']))
        {
            $params['var']['equal'] = $params['tree']['create'];
        }
        return $params;
    }

    public function bracket($params)
    {
        $params['string'] = $params['tree']['rule'] . $params['string'] . $params['rules'][$params['tree']['rule']]['close'];
        return $params;
    }

    public function linebreaks($params)
    {
        $params['string'] = str_replace('<br />', '', $params['string']);
        return $params;
    }

    public function listitems($params)
    {
        if (!$params['var']['equal'] || in_array($params['var']['equal'], array('1', 'a', 'A', 'i', 'I')))
        {
            $params['string'] = str_replace('<br />', '', $params['string']);
            $params['string'] = explode($params['var']['delimiter'], $params['string']);
            $split = array();
            foreach ($params['string'] as $key => $value)
            {
                if ($key != 0)
                {
                    $value = $params['var']['open'] . $value . $params['var']['close'];
                }
                $split[] = $value;
            }
            $params['string'] = implode('', $split);
        }
        else
        {
            $params['var']['template'] = $params['tree']['rule'] . $params['string'] . $params['rules'][$params['tree']['rule']]['close'];
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
        $this->templating->equal = $params['var']['equal'];
        $this->templating->string = $params['string'];
        $params['string'] = $this->suit->execute($this->templating->rules, $params['var']['template']);
        return $params;
    }
}
?>