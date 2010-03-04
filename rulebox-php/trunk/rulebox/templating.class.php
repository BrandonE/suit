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
                        'class' => $this,
                        'function' => 'bracket'
                    )
                )
            ),
            '[assign]' => array
            (
                'close' => '[/assign]',
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
                    'equal' => '=',
                    'list' => array('json', 'var'),
                    'quote' => array('"', '\''),
                    'var' => array
                    (
                        'decode' => array('json'),
                        'delimiter' => '.',
                        'json' => 'false',
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
                        'function' => 'functions'
                    )
                ),
                'skip' => true,
                'var' => array
                (
                    'equal' => '=',
                    'onesided' => true,
                    'quote' => array('"', '\''),
                    'var' => array()
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
                        'function' => 'code'
                    )
                ),
                'var' => array()
            ),
            '[comment]' => array
            (
                'close' => '[/comment]',
                'postwalk' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'comments'
                    )
                ),
                'skip' => true
            ),
            '[entities]' => array
            (
                'close' => '[/entities]',
                'postwalk' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'entities'
                    )
                )
            ),
            '[execute]' => array
            (
                'close' => '[/execute]',
                'postwalk' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'execute'
                    )
                )
            ),
            '[if]' => array
            (
                'close' => '[/if]',
                'prewalk' => array
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
                    'blacklist' => true,
                    'equal' => '=',
                    'list' => array('decode'),
                    'quote' => array('"', '\''),
                    'var' => array
                    (
                        'condition' => 'false',
                        'decode' => array('condition', 'else'),
                        'else' => 'false'
                    )
                )
            ),
            '[if' => array
            (
                'close' => ']',
                'create' => '[if]',
                'skip' => true
            ),
            '[loop]' => array
            (
                'close' => '[/loop]',
                'prewalk' => array
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
                        'function' => 'loop'
                    )
                ),
                'var' => array
                (
                    'blacklist' => true,
                    'equal' => '=',
                    'list' => array('decode', 'rule'),
                    'quote' => array('"', '\''),
                    'var' => array
                    (
                        'decode' => array('skip', 'vars'),
                        'delimiter' => '',
                        'rule' => '[loopvar]',
                        'vars' => '[]'
                    )
                )
            ),
            '[loop' => array
            (
                'close' => ']',
                'create' => '[loop]',
                'skip' => true
            ),
            '[loopvar]' => array
            (
                'close' => '[/loopvar]',
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
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'loopvariables'
                    )
                ),
                'var' => array
                (
                    'equal' => '=',
                    'list' => array('json'),
                    'quote' => array('"', '\''),
                    'var' => array
                    (
                        'decode' => array('json'),
                        'delimiter' => '.',
                        'json' => 'false',
                        'var' => array()
                    )
                )
            ),
            '[loopvar' => array
            (
                'close' => ']',
                'create' => '[loopvar]',
                'skip' => true
            ),
            '[replace]' => array
            (
                'close' => '[/replace]',
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
                        'function' => 'replace'
                    )
                ),
                'var' => array
                (
                    'equal' => '=',
                    'quote' => array('"', '\''),
                    'var' => array
                    (
                        'replace' => '',
                        'search' => ''
                    )
                )
            ),
            '[replace' => array
            (
                'close' => ']',
                'create' => '[replace]',
                'skip' => true
            ),
            '[return' => array
            (
                'close' => '/]',
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
                    'equal' => '=',
                    'list' => array('layers'),
                    'onesided' => true,
                    'quote' => array('"', '\''),
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
                'postwalk' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'skip'
                    )
                ),
                'skip' => true,
                'skipescape' => true
            ),
            '[template]' => array
            (
                'close' => '[/template]',
                'postwalk' => array
                (
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
                    'equal' => '=',
                    'quote' => array('"', '\''),
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
                'postwalk' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'trim'
                    )
                )
            ),
            '[try]' => array
            (
                'close' => '[/try]',
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
                        'function' => 'trying'
                    )
                ),
                'skip' => true,
                'var' => array
                (
                    'equal' => '=',
                    'list' => array('var'),
                    'quote' => array('"', '\''),
                    'var' => array
                    (
                        'delimiter' => '.',
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
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'variables'
                    )
                ),
                'var' => array
                (
                    'equal' => '=',
                    'list' => array('json'),
                    'quote' => array('"', '\''),
                    'var' => array
                    (
                        'decode' => array('json'),
                        'delimiter' => '.',
                        'json' => 'false'
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
                'postwalk' => array
                (
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
                $params['tree']['case'] = json_decode($params['tree']['case']);
            }
            $this->assignvariable($params['var']['var'], $params['var']['delimiter'], $params['tree']['case'], $params['suit']->var);
        }
        $params['tree']['case'] = '';
        return $params;
    }

    public function assignvariable($string, $split, $assign, &$var)
    {
        $split = explode($split, $string);
        $size = count($split);
        for ($i = 0; $i < $size - 1; $i++)
        {
            if (is_array($var))
            {
                $var = &$var[$split[$i]];
            }
            else
            {
                $var = &$var->$split[$i];
            }
        }
        if (is_array($var))
        {
            $var[$split[$size - 1]] = $assign;
        }
        else
        {
            $var->$split[$size - 1] = $assign;
        }
    }

    public function attribute($params)
    {
        $var = $params['var'];
        $params['var'] = $params['var']['var'];
        if (array_key_exists('onesided', $var) && $var['onesided'])
        {
            $case = $params['tree']['case'];
        }
        elseif (array_key_exists('create', $params))
        {
            $case = $params['create'];
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
            $position = $function($case, $value);
            if ($position !== false && ($smallest === false || $position < $smallest))
            {
                $quote = $value;
                $smallest = $position;
            }
        }
        if ($quote)
        {
            //Define the variables
            $split = explode($quote, $case);
            unset($split[count($split) - 1]);
            $size = count($split);
            for ($i = 0; $i < $size; $i++)
            {
                //If this is the first iteration of the pair
                if ($i % 2 == 0)
                {
                    $name = trim($split[$i]);
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
                    $params['var'][$name] = $params['suit']->execute($params['rules'], $split[$i], $params['config']);
                }
            }
        }
        return $params;
    }

    public function bracket($params)
    {
        $params['tree']['case'] = $params['tree']['rule'] . $params['tree']['case'] . $params['rules'][$params['tree']['rule']]['close'];
        return $params;
    }

    public function code($params)
    {
        //If the code file is not whitelisted or blacklisted and the file exists
        if ($this->listing($params['tree']['case'], $params['var']) && is_file($params['tree']['case']))
        {
            $suit = $params['suit'];
            include str_replace('../', '', str_replace('..\'', '', $params['tree']['case']));
        }
        $params['tree']['case'] = '';
        return $params;
    }

    public function comments($params)
    {
        $params['tree']['case'] = '';
        return $params;
    }

    public function condition($params)
    {
        //Hide the case if necessary
        if (($params['var']['condition'] && $params['var']['else']) || (!$params['var']['condition'] && !$params['var']['else']))
        {
            $params['walk'] = false;
        }
        return $params;
    }

    public function decode($params)
    {
        foreach ($params['var']['decode'] as $value)
        {
            $params['var'][$value] = json_decode($params['var'][$value]);
        }
        return $params;
    }

    public function entities($params)
    {
        $params['tree']['case'] = htmlentities($params['tree']['case']);
        return $params;
    }

    public function evaluation($params)
    {
        $params['tree']['case'] = eval($params['tree']['case']);
        return $params;
    }

    public function execute($params)
    {
        $params['tree']['case'] = $params['suit']->execute($params['rules'], $params['tree']['case'], $params['config']);
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
            $params['tree']['case'] = $params['var']['owner']->$params['var']['function']($kwargs);
        }
        else
        {
            $params['tree']['case'] = $params['var']['function']($kwargs);
        }
        return $params;
    }

    public function listing($name, $var)
    {
        $return = true;
        //If the variable is whitelisted or blacklisted
        if (array_key_exists('list', $var) && (((!array_key_exists('blacklist', $var) || !$var['blacklist']) && !in_array($name, $var['list'])) || (array_key_exists('blacklist', $var) && $var['blacklist'] && in_array($name, $var['list']))))
        {
            $return = false;
        }
        return $return;
    }

    public function loop($params)
    {
        $iterationvars = array();
        if (!is_array($params['var']['vars']))
        {
            $params['tree']['case'] = '';
            return $params;
        }
        foreach ($params['var']['vars'] as $value)
        {
            $var = array
            (
                $params['var']['rule'] => $params['rules'][$params['var']['rule']]
            );
            foreach ($value as $key => $value2)
            {
                $var[$params['var']['rule']]['var']['var']['var'][$key] = $value2;
            }
            $iterationvars[] = $var;
        }
        $iterations = array();
        $tree = array
        (
            'case' => '',
            'contents' => $params['tree']['contents'],
            'parallel' => array()
        );
        foreach ($iterationvars as $value)
        {
            //Parse for this iteration
            $result = $params['suit']->walk(array_merge($params['rules'], $value), $tree, $params['config']);
            $iterations[] = $result['tree']['case'];
        }
        //Implode the iterations
        $params['tree']['case'] = implode($params['var']['delimiter'], $iterations);
        $params['walk'] = false;
        return $params;
    }

    public function loopvariables($params)
    {
        $split = explode($params['var']['delimiter'], $params['tree']['case']);
        $params['tree']['case'] = $params['var']['var'];
        foreach ($split as $value)
        {
            if (is_array($params['tree']['case']))
            {
                $params['tree']['case'] = $params['tree']['case'][$value];
            }
            else
            {
                $params['tree']['case'] = $params['tree']['case']->$value;
            }
        }
        if ($params['var']['json'])
        {
            $params['tree']['case'] = json_encode($params['tree']['case']);
        }
        return $params;
    }

    public function replace($params)
    {
        $params['tree']['case'] = str_replace($params['var']['search'], $params['var']['replace'], $params['tree']['case']);
        return $params;
    }

    public function returning($params)
    {
        if ($params['var']['layers'])
        {
            $params['returnvar'] = array
            (
                'returnfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'returningfunction'
                    )
                ),
                'layers' => $params['var']['layers']
            );
            $params['returnfunctions'] = $params['returnvar']['returnfunctions'];
        }
        $params['tree']['case'] = '';
        return $params;
    }

    public function returningfunction($params)
    {
        if (is_int($params['returnedvar']['layers']))
        {
            $params['returnedvar']['layers']--;
        }
        if ($params['returnedvar']['layers'])
        {
            $params['returnvar'] = $params['returnedvar'];
            $params['returnfunctions'] = $params['returnedvar']['returnfunctions'];
        }
        $params['walk'] = false;
        return $params;
    }

    public function skip($params)
    {
        return $params;
    }

    public function templates($params)
    {
        //If the variable is not whitelisted or blacklisted and the file exists
        if ($this->listing($params['tree']['case'], $params['var']) && is_file($params['tree']['case']))
        {
            $params['tree']['case'] = file_get_contents(str_replace('../', '', str_replace('..\'', '', $params['tree']['case'])));
        }
        else
        {
            $params['tree']['case'] = '';
        }
        return $params;
    }

    public function transform($params)
    {
        $params['var']['string'] = $params['tree']['case'];
        return $params;
    }

    public function trim($params)
    {
        $params['tree']['case'] = $params['suit']->execute(
            array
            (
                '' => array
                (
                    'prewalk' => array
                    (
                        array
                        (
                            'function' => 'trimexecute',
                            'class' => $this
                        )
                    )
                ),
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
            ),
            $params['tree']['case'],
            $params['config']
        );
        $params['tree']['case'] = trim($params['tree']['case']);
        return $params;
    }

    public function trimexecute($params)
    {
        foreach ($params['tree']['contents'] as $key => $value)
        {
            if (is_array($params['tree']['contents'][$key]))
            {
                $params['tree']['case'] .= $params['tree']['contents'][$key]['rule'] . $params['tree']['contents'][$key]['contents'][0] . $params['rules'][$params['tree']['contents'][$key]['rule']]['close'];
            }
            else
            {
                $params['tree']['case'] .= preg_replace('/[\s]+$/m', '', $params['tree']['contents'][$key]) . substr($params['tree']['contents'][$key], strlen(rtrim($params['tree']['contents'][$key])));
            }
        }
        $params['walk'] = false;
        return $params;
    }

    public function trying($params)
    {
        if ($params['var']['var'])
        {
            $params['suit']->$params['var']['var'] = '';
        }
        try
        {
            $result = $params['suit']->execute($params['rules'], $params['tree']['case'], $params['config']);
        }
        catch (Exception $e)
        {
            //If a variable is provided
            if ($params['var']['var'])
            {
                $this->assignvariable($params['var']['var'], $params['var']['delimiter'], $e, $params['suit']);
            }
            $params['tree']['case'] = '';
        }
        return $params;
    }

    public function variables($params)
    {
        $split = explode($params['var']['delimiter'], $params['tree']['case']);
        foreach ($split as $key => $value)
        {
            if ($key == 0)
            {
                $params['tree']['case'] = $params['suit']->var->$value;
            }
            else
            {
                if (is_array($params['tree']['case']))
                {
                    $params['tree']['case'] = $params['tree']['case'][$value];
                }
                else
                {
                    $params['tree']['case'] = $params['tree']['case']->$value;
                }
            }
        }
        if ($params['var']['json'])
        {
            $params['tree']['case'] = json_encode($params['tree']['case']);
        }
        return $params;
    }
}
?>