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
class Templating
{
    public $default = array
    (
        'delimiter' => '.',
        'equal' => '=',
        'log' => false,
        'quote' => array('"', '\'')
    );

    public function __construct($suit)
    {
        $this->var = new stdClass();

        $this->default['owner'] = &$this->var;

        $this->suit = $suit;

        $this->rules = array
        (
            '[' => array
            (
                'close' => ']',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'bracket'
                    )
                )
            ),
            '[assign]' => array
            (
                'close' => '[/assign]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'assign'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('json', 'var'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'decode' => array('json'),
                        'delimiter' => $this->default['delimiter'],
                        'json' => 'false',
                        'owner' => $this->default['owner'],
                        'var' => ''
                    )
                )
            ),
            '[assign' => array
            (
                'close' => ']',
                'create' => '[assign]',
                'skip' => true
            ),
            '[call' => array
            (
                'close' => '/]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'functions'
                    )
                ),
                'skip' => true,
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'log' => $this->default['log'],
                    'onesided' => true,
                    'quote' => $this->default['quote'],
                    'var' => array()
                )
            ),
            '[comment]' => array
            (
                'close' => '[/comment]',
                'skip' => true
            ),
            '[entities]' => array
            (
                'close' => '[/entities]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'copyvar'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'entities'
                    )
                ),
                'var' => array
                (
                    'entities' => true,
                    'json' => false
                )
            ),
            '[execute]' => array
            (
                'close' => '[/execute]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'execute'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('log'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'condition' => 'false',
                        'decode' => array('log'),
                        'log' => 'true'
                    )
                )
            ),
            '[execute' => array
            (
                'close' => ']',
                'create' => '[execute]',
                'skip' => true
            ),
            '[if]' => array
            (
                'close' => '[/if]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'condition'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('condition', 'not'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'condition' => '',
                        'decode' => array('not'),
                        'delimiter' => $this->default['delimiter'],
                        'not' => 'false',
                        'owner' => $this->default['owner']
                    )
                )
            ),
            '[if' => array
            (
                'close' => ']',
                'create' => '[if]',
                'skip' => true
            ),
            '[local]' => array
            (
                'close' => '[/local]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'copyvar'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'savelocal'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'loadlocal'
                    )
                ),
                'var' => array
                (
                    'owner' => $this->default['owner']
                )
            ),
            '[loop]' => array
            (
                'close' => '[/loop]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'loop'
                    )
                ),
                'var' => array
                (
                    'blacklist' => true,
                    'equal' => $this->default['equal'],
                    'list' => array('delimiter', 'owner'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'delimiter' => $this->default['delimiter'],
                        'implode' => '',
                        'list' => '',
                        'owner' => $this->default['owner']
                    )
                )
            ),
            '[loop' => array
            (
                'close' => ']',
                'create' => '[loop]',
                'skip' => true
            ),
            '[return' => array
            (
                'close' => '/]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'returning'
                    )
                ),
                'skip' => true,
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('layers'),
                    'log' => $this->default['log'],
                    'onesided' => true,
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'decode' => array('layers'),
                        'layers' => 'true'
                    )
                )
            ),
            '[skip]' => array
            (
                'close' => '[/skip]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    )
                ),
                'skip' => true,
                'skipescape' => true
            ),
            '[template]' => array
            (
                'close' => '[/template]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'copyvar'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'templates'
                    )
                ),
                'var' => array()
            ),
            '[transform]' => array
            (
                'close' => '[/transform]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'transform'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'functions'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array()
                )
            ),
            '[transform' => array
            (
                'close' => ']',
                'create' => '[transform]',
                'skip' => true
            ),
            '[trim]' => array
            (
                'close' => '[/trim]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'trim'
                    )
                )
            ),
            '[trim' => array
            (
                'close' => ']',
                'create' => '[trim]',
                'skip' => true
            ),
            '[try]' => array
            (
                'close' => '[/try]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'trying'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('var'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'delimiter' => $this->default['delimiter'],
                        'owner' => $this->default['owner'],
                        'var' => ''
                    )
                )
            ),
            '[try' => array
            (
                'close' => ']',
                'create' => '[try]',
                'skip' => true
            ),
            '[var]' => array
            (
                'close' => '[/var]',
                'class' => $this,
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'variables'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'entities'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('entities', 'json'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'decode' => array('entities', 'json'),
                        'delimiter' => $this->default['delimiter'],
                        'entities' => 'true',
                        'json' => 'false',
                        'owner' => $this->default['owner']
                    )
                )
            ),
            '[var' => array
            (
                'close' => ']',
                'create' => '[var]',
                'skip' => true
            )
        );
        $this->evalrules = array
        (
            '[eval]' => array
            (
                'close' => '[/eval]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'evaluation'
                    )
                )
            )
        );
    }

    public function assign($params)
    {
        //If a variable is provided
        if ($params['var']['var'])
        {
            if ($params['var']['json'])
            {
                $params['string'] = json_decode($params['string'], true);
            }
            $this->setvariable($params['var']['var'], $params['var']['delimiter'], $params['string'], $params['var']['owner']);
        }
        $params['string'] = '';
        return $params;
    }

    public function attribute($params)
    {
        $var = $params['rules'][$params['tree']['rule']]['var'];
        $params['var'] = $var['var'];
        if (array_key_exists('onesided', $var) && $var['onesided'])
        {
            $string = $params['string'];
        }
        elseif (array_key_exists('create', $params['tree']))
        {
            $string = $params['tree']['create'];
        }
        else
        {
            return $params;
        }
        $quote = '';
        $smallest = false;
        $function = 'strpos';
        if ($params['config']['insensitive'])
        {
            $function = 'stripos';
        }
        foreach ($var['quote'] as $value)
        {
            $position = $function($string, $value);
            if ($position !== false && ($smallest === false || $position < $smallest))
            {
                $quote = $value;
                $smallest = $position;
            }
        }
        if ($quote)
        {
            //Define the variables
            $split = explode($quote, $string);
            unset($split[count($split) - 1]);
            foreach ($split as $key => $value)
            {
                //If this is the first iteration of the pair
                if ($key % 2 == 0)
                {
                    $name = trim($value);
                    $syntax = (substr($name, strlen($name) - strlen($var['equal'])) == $var['equal']);
                    $name = substr_replace($name, '', strlen($name) - strlen($var['equal']));
                    //If the syntax is not valid or variable is whitelisted or blacklisted, do not prepare to define the variable
                    if (!$syntax || !$this->listing($name, $var))
                    {
                        $name = '';
                    }
                }
                elseif ($name)
                {
                    //Define the variable
                    $config = $params['config'];
                    $config['log'] = $var['log'];
                    $params['var'][$name] = $this->suit->execute($params['rules'], $value, $config);
                }
            }
        }
        return $params;
    }

    public function bracket($params)
    {
        $params['string'] = $params['tree']['rule'] . $params['string'] . $params['rules'][$params['tree']['rule']]['close'];
        return $params;
    }

    public function condition($params)
    {
        $var = $this->getvariable($params['var']['condition'], $params['var']['delimiter'], $params['var']['owner']);
        //Show the case if necessary
        if (($var && !$params['var']['not']) || (!$var && $params['var']['else']))
        {
            $params = $this->walk($params);
        }
        return $params;
    }

    public function copyvar($params)
    {
        $params['var'] = $params['rules'][$params['tree']['rule']]['var'];
        return $params;
    }

    public function decode($params)
    {
        foreach ($params['var']['decode'] as $value)
        {
            $params['var'][$value] = json_decode($params['var'][$value], true);
        }
        return $params;
    }

    public function entities($params)
    {
        if (!$params['var']['json'] && $params['var']['entities'])
        {
            $params['string'] = htmlentities(strval($params['string']));
        }
        return $params;
    }

    public function evaluation($params)
    {
        $params['string'] = eval($params['string']);
        return $params;
    }

    public function execute($params)
    {
        $config = $params['config'];
        $config['log'] = $params['var']['log'];
        $params['string'] = $this->suit->execute($params['rules'], $params['string'], $config);
        return $params;
    }

    public function functions($params)
    {
        $kwargs = $params['var'];
        unset($kwargs['function']);
        unset($kwargs['owner']);
        //Note whether or not the function is in a class
        if (array_key_exists('owner', $params['var']))
        {
            $params['string'] = $params['var']['owner']->$params['var']['function']($kwargs);
        }
        else
        {
            $params['string'] = $params['var']['function']($kwargs);
        }
        return $params;
    }

    public function getvariable($string, $delimiter, $owner)
    {
        foreach (explode($delimiter, $string) as $value)
        {
            if (is_array($owner))
            {
                $owner = $owner[$value];
            }
            else
            {
                $owner = $owner->$value;
            }
        }
        return $owner;
    }

    public function listing($name, $var)
    {
        return (!(array_key_exists('list', $var) && (((!array_key_exists('blacklist', $var) || !$var['blacklist']) && !in_array($name, $var['list'])) || (array_key_exists('blacklist', $var) && $var['blacklist'] && in_array($name, $var['list'])))));
    }

    public function loadlocal($params)
    {
        foreach ($params['var']['local'] as $key => $value)
        {
            if (is_array($params['var']['owner']))
            {
                $params['var']['owner'][$key] = $value;
            }
            else
            {
                $params['var']['owner']->$key = $value;
            }
        }
        foreach ($params['var']['owner'] as $key => $value)
        {
            if (!array_key_exists($key, $params['var']['local']))
            {
                if (is_array($params['var']['owner']))
                {
                    unset($params['var']['owner'][$key]);
                }
                else
                {
                    unset($params['var']['owner']->$key);
                }
            }
        }
        return $params;
    }

    public function loop($params)
    {
        $var = $this->getvariable($params['var']['list'], $params['var']['delimiter'], $params['var']['owner']);
        $iterations = array();
        $tree = array
        (
            'contents' => $params['tree']['contents']
        );
        foreach ($var as $key => $value)
        {
            if (array_key_exists('key', $params['var']))
            {
                $this->setvariable($params['var']['key'], $params['var']['delimiter'], $key, $params['var']['owner']);
            }
            if (array_key_exists('value', $params['var']))
            {
                $this->setvariable($params['var']['value'], $params['var']['delimiter'], $value, $params['var']['owner']);
            }
            //Walk for this iteration
            $result = $this->walk($params);
            $iterations[] = $result['string'];
        }
        //Implode the iterations
        $params['string'] = implode($params['var']['implode'], $iterations);
        return $params;
    }

    public function returning($params)
    {
        $params['string'] = '';
        if (!$params['var']['layers'])
        {
            return $params;
        }
        if (is_int($params['var']['layers']))
        {
            $params['var']['layers'] -= 1;
        }
        $size = count($params['tree']['parent']['contents']);
        for ($i = 0; $i < $size; $i++)
        {
            if ($i > $params['tree']['key'])
            {
                unset($params['tree']['parent']['contents'][$i]);
            }
        }
        if ($params['var']['layers'] && array_key_exists('parent', $params['tree']['parent']))
        {
            $params['tree']['parent'] = &$params['tree']['parent']['parent'];
            $params = $this->returning($params);
        }
        return $params;
    }

    public function savelocal($params)
    {
        $params['var']['local'] = array();
        foreach ($params['var']['owner'] as $key => $value)
        {
            $params['var']['local'][$key] = $value;
        }
        return $params;
    }

    public function setvariable($string, $split, $assignment, &$owner)
    {
        $split = explode($split, $string);
        foreach ($split as $key => $value)
        {
            if ($key < count($split) - 1)
            {
                if (is_array($owner))
                {
                    $owner = &$owner[$value];
                }
                else
                {
                    $owner = &$owner->$value;
                }
            }
        }
        if (is_array($owner))
        {
            $owner[$split[count($split) - 1]] = $assignment;
        }
        else
        {
            $owner->$split[count($split) - 1] = $assignment;
        }
    }

    public function templates($params)
    {
        //If the variable is not whitelisted or blacklisted and the file exists
        if ($this->listing($params['string'], $params['var']) && is_file($params['string']))
        {
            $params['string'] = file_get_contents(str_replace('../', '', str_replace('..\'', '', $params['string'])));
        }
        else
        {
            $params['string'] = '';
        }
        return $params;
    }

    public function transform($params)
    {
        $params['var']['string'] = $params['string'];
        return $params;
    }

    public function trim($params)
    {
        $rules = array
        (
            '<pre' => array
            (
                'close' => '</pre>',
                'skip' => true
            ),
            '<textarea' => array
            (
                'close' => '</textarea>',
                'skip' => true
            )
        );
        $pos = $this->suit->tokens($rules, $params['string'], $params['config']);
        $tree = $this->suit->parse($rules, $pos, $params['string'], $params['config']);
        $tree = $tree['contents'];
        $params['string'] = '';
        foreach ($tree as $value)
        {
            if (is_array($value))
            {
                $params['string'] .= $value['rule'] . $value['contents'][0] . $rules[$value['rule']]['close'];
            }
            else
            {
                $params['string'] .= preg_replace('/[\s]+$/m', '', $value) . substr($value, strlen(rtrim($value)));
            }
        }
        $params['string'] = ltrim($params['string']);
        return $params;
    }

    public function trying($params)
    {
        if ($params['var']['var'])
        {
            $this->suit->$params['var']['var'] = '';
        }
        try
        {
            $params['string'] = $this->suit->walk($params['rules'], $params['tree'], $params['config']);
        }
        catch (Exception $e)
        {
            //If a variable is provided
            if ($params['var']['var'])
            {
                $this->setvariable($params['var']['var'], $params['var']['delimiter'], $e, $params['var']['owner']);
            }
            $params['string'] = '';
        }
        return $params;
    }

    public function variables($params)
    {
        $params['string'] = $this->getvariable($params['string'], $params['var']['delimiter'], $params['var']['owner']);
        if ($params['var']['json'])
        {
            $params['string'] = json_encode($params['string']);
        }
        return $params;
    }

    public function walk($params)
    {
        $params['string'] = $this->suit->walk($params['rules'], $params['tree'], $params['config']);
        return $params;
    }
}
?>