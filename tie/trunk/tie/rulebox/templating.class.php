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
                        'function' => 'assign'
                    )
                ),
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
            '[assign' => array
            (
                'close' => ']',
                'create' => '[assign]',
                'skip' => true
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
            '[escape]' => array
            (
                'close' => '[/escape]',
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
                        'function' => 'jsondecode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'escape'
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
                        'decode' => array('strings'),
                        'strings' => '[]',
                    )
                )
            ),
            '[escape' => array
            (
                'close' => ']',
                'create' => '[escape]',
                'skip' => true
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
                        'function' => 'jsondecode'
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
                        'function' => 'jsondecode'
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
                        'function' => 'jsondecode'
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
                    'list' => array('json', 'serialize'),
                    'quote' => array('"', '\''),
                    'var' => array
                    (
                        'decode' => array('json', 'serialize'),
                        'delimiter' => '.',
                        'json' => 'false',
                        'serialize' => 'false',
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
                        'function' => 'jsondecode'
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
                        'function' => 'jsondecode'
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
                    'list' => array('json', 'serialize'),
                    'quote' => array('"', '\''),
                    'var' => array
                    (
                        'decode' => array('json', 'serialize'),
                        'delimiter' => '.',
                        'json' => 'false',
                        'serialize' => 'false'
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
        //If a variable is provided and it not is whitelisted or blacklisted
        if ($params['var']['var'] && $this->listing($params['var']['var'], $params['var']))
        {
            //Split up the file, paying attention to escape strings
            $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['var']['var'], $params['config']['escape'], $params['config']['insensitive']);
            $this->assignvariable($split, $params['tree']['case'], $params['suit']);
        }
        $params['tree']['case'] = '';
        return $params;
    }

    public function assignvariable($split, $assign, &$var)
    {
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
            $split = $params['suit']->explodeunescape($quote, $case, $params['config']['escape'], $params['config']['insensitive']);
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

    public function entities($params)
    {
        $params['tree']['case'] = htmlentities($params['tree']['case']);
        return $params;
    }

    public function escape($params)
    {
        $params['tree']['case'] = $params['suit']->escape($params['var']['strings'], $params['tree']['case'], $params['config']['escape'], $params['config']['insensitive']);
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

    public function jsondecode($params)
    {
        foreach ($params['var']['decode'] as $value)
        {
            $params['var'][$value] = json_decode($params['var'][$value]);
        }
        return $params;
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
        //Split up the file, paying attention to escape strings
        $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['tree']['case'], $params['config']['escape'], $params['config']['insensitive']);
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
        if ($params['var']['serialize'])
        {
            $params['tree']['case'] = serialize($params['tree']['case']);
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

    public function trim($params)
    {
        $rules = array
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
        );
        $params['tree']['case'] = $params['suit']->execute($rules, $params['tree']['case'], $params['config']);
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
            //If a variable is provided and it not is whitelisted or blacklisted
            if ($params['var']['var'] && $this->listing($params['var']['var'], $params['var']))
            {
                //Split up the file, paying attention to escape strings
                $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['var']['var'], $params['config']['escape'], $params['config']['insensitive']);
                $this->assignvariable($split, $e, $params['suit']);
            }
            $params['tree']['case'] = '';
        }
        return $params;
    }

    public function unserialize($params)
    {
        foreach ($params['var']['decode'] as $value)
        {
            $params['var'][$value] = unserialize($params['var'][$value]);
        }
        return $params;
    }

    public function variables($params)
    {
        //If the variable is not whitelisted or blacklisted
        if ($this->listing($params['tree']['case'], $params['var']))
        {
            //Split up the file, paying attention to escape strings
            $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['tree']['case'], $params['config']['escape'], $params['config']['insensitive']);
            $params['tree']['case'] = $params['suit'];
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
            if ($params['var']['serialize'])
            {
                $params['tree']['case'] = serialize($params['tree']['case']);
            }
        }
        else
        {
            $params['tree']['case'] = '';
        }
        return $params;
    }
}
?>