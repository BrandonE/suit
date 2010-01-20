<?php
/**
**@This file is part of SUIT.
**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class Nodes
{
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
                        'class' => $this,
                        'function' => 'bracket'
                    )
                )
            ),
            '[assign]' => array
            (
                'close' => '[/assign]',
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
                        'delimiter' => '=>',
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
                'stringfunctions' => array
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
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'comments'
                    )
                ),
                'skip' => true
            ),
            '[escape]' => array
            (
                'close' => '[/escape]',
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'escape'
                    )
                ),
                'skip' => true,
                'skipescape' => true,
                'var' => "\r.\n.\t ."
            ),
            '[execute]' => array
            (
                'close' => '[/execute]',
                'stringfunctions' => array
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
                'treefunctions' => array
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
                'treefunctions' => array
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
                    'list' => array('decode', 'node'),
                    'quote' => array('"', '\''),
                    'var' => array
                    (
                        'decode' => array('skip', 'vars'),
                        'delimiter' => '',
                        'node' => '[loopvar]',
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
                        'delimiter' => '=>',
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
            '[template]' => array
            (
                'close' => '[/template]',
                'stringfunctions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'templates'
                    )
                )
            ),
            '[trim]' => array
            (
                'close' => '[/trim]',
                'stringfunctions' => array
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
                        'delimiter' => '=>',
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
                        'delimiter' => '=>',
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
        $this->evalnodes = array
        (
            '[eval]' => array
            (
                'close' => '[/eval]',
                'stringfunctions' => array
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
            $this->assignvariable($split, $params['case'], $params['suit']);
        }
        $params['case'] = '';
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
            $case = $params['case'];
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
        foreach ($var['quote'] as $value)
        {
            $position = $params['suit']->strpos($case, $value, 0, $params['config']['insensitive']);
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
                    //If the syntax is valid
                    if (substr($name, strlen($name) - strlen($var['equal'])) == $var['equal'])
                    {
                        $name = substr_replace($name, '', strlen($name) - strlen($var['equal']));
                        //If the variable is whitelisted or blacklisted, do not prepare to define the variable
                        if (!$this->listing($name, $var))
                        {
                            $name = '';
                        }
                    }
                    else
                    {
                        $name = '';
                    }
                }
                elseif ($name)
                {
                    //Define the variable
                    $params['var'][$name] = $params['suit']->execute($params['nodes'], $split[$i], $params['config']);
                }
            }
        }
        return $params;
    }

    public function bracket($params)
    {
        $params['case'] = $params['node'] . $params['case'] . $params['nodes'][$params['node']]['close'];
        return $params;
    }

    public function code($params)
    {
        //If the code file is not whitelisted or blacklisted and the file exists
        if ($this->listing($params['case'], $params['var']) && is_file($params['case']))
        {
            $suit = $params['suit'];
            include str_replace('../', '', str_replace('..\'', '', $params['case']));
        }
        $params['case'] = '';
        return $params;
    }

    public function comments($params)
    {
        $params['case'] = '';
        return $params;
    }

    public function condition($params)
    {
        //Hide the case if necessary
        if (($params['var']['condition'] && $params['var']['else']) || (!$params['var']['condition'] && !$params['var']['else']))
        {
            $params['tree'] = array
            (
                'contents' => array
                (
                    ''
                )
            );
        }
        return $params;
    }

    public function escape($params)
    {
        return $params;
    }

    public function evaluation($params)
    {
        $params['case'] = eval($params['case']);
        return $params;
    }

    public function execute($params)
    {
        $params['case'] = $params['suit']->execute($params['nodes'], $params['case'], $params['config']);
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
            $params['case'] = '';
            return $params;
        }
        foreach ($params['var']['vars'] as $value)
        {
            $var = array
            (
                $params['var']['node'] => $params['nodes'][$params['var']['node']]
            );
            foreach ($value as $key => $value2)
            {
                $var[$params['var']['node']]['var']['var']['var'][$key] = $value2;
            }
            $iterationvars[] = $var;
        }
        $iterations = array();
        $tree = array
        (
            'contents' => $params['tree']['contents']
        );
        foreach ($iterationvars as $value)
        {
            //Parse for this iteration
            $result = $params['suit']->walk(array_merge($params['nodes'], $value), $tree, $params['config']);
            $iterations[] = $result['contents'];
        }
        //Implode the iterations
        $params['tree'] = array
        (
            'contents' => array
            (
                implode($params['var']['delimiter'], $iterations)
            )
        );
        return $params;
    }

    public function loopvariables($params)
    {
        //Split up the file, paying attention to escape strings
        $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['case'], $params['config']['escape'], $params['config']['insensitive']);
        $params['case'] = $params['var']['var'];
        foreach ($split as $value)
        {
            if (is_array($params['case']))
            {
                $params['case'] = $params['case'][$value];
            }
            else
            {
                $params['case'] = $params['case']->$value;
            }
        }
        if ($params['var']['json'])
        {
            $params['case'] = json_encode($params['case']);
        }
        if ($params['var']['serialize'])
        {
            $params['case'] = serialize($params['case']);
        }
        return $params;
    }

    public function replace($params)
    {
        $params['case'] = str_replace($params['var']['search'], $params['var']['replace'], $params['case']);
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
        $params['case'] = '';
        return $params;
    }

    public function returningfunction($params)
    {
        array_splice($params['tree']['contents'], $params['key'] + 1);
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

    public function templates($params)
    {
        //If the variable is not whitelisted or blacklisted and the file exists
        if ($this->listing($params['case'], $params['var']) && is_file($params['case']))
        {
            $params['case'] = file_get_contents(str_replace('../', '', str_replace('..\'', '', $params['case'])));
        }
        else
        {
            $params['case'] = '';
        }
        return $params;
    }

    public function trim($params)
    {
        $nodes = array
        (
            '' => array
            (
                'treefunctions' => array
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
                'stringfunctions' => array
                (
                    array
                    (
                        'function' => 'trimarea',
                        'class' => $this
                    )
                ),
                'skip' => true
            ),
            '<textarea' => array
            (
                'close' => '</textarea>',
                'stringfunctions' => array
                (
                    array
                    (
                        'function' => 'trimarea',
                        'class' => $this
                    )
                ),
                'skip' => true
            )
        );
        $params['case'] = $params['suit']->execute($nodes, $params['case'], $params['config']);
        $params['case'] = ltrim($params['case']);
        return $params;
    }

    public function trimarea($params)
    {
        $params['case'] = $params['node'] . $params['case'] . $params['nodes'][$params['node']]['close'];
        return $params;
    }

    public function trimexecute($params)
    {
        foreach ($params['tree']['contents'] as $key => $value)
        {
            if (is_array($params['tree']['contents'][$key]))
            {
                $result = $params['suit']->walkarray($params['nodes'], $params['tree'], $params['config'], $params, $key);
                $params = $result['params'];
                $params['tree'] = $result['tree'];
            }
            else
            {
                $params['tree']['contents'][$key] = preg_replace('/[\s]+$/m', '', $params['tree']['contents'][$key]);
            }
        }
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
            $result = $params['suit']->execute($params['nodes'], $params['case'], $params['config']);
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
            $params['case'] = '';
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
        if ($this->listing($params['case'], $params['var']))
        {
            //Split up the file, paying attention to escape strings
            $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['case'], $params['config']['escape'], $params['config']['insensitive']);
            $params['case'] = $params['suit'];
            foreach ($split as $value)
            {
                if (is_array($params['case']))
                {
                    $params['case'] = $params['case'][$value];
                }
                else
                {
                    $params['case'] = $params['case']->$value;
                }
            }
            if ($params['var']['json'])
            {
                $params['case'] = json_encode($params['case']);
            }
            if ($params['var']['serialize'])
            {
                $params['case'] = serialize($params['case']);
            }
        }
        else
        {
            $params['case'] = '';
        }
        return $params;
    }
}
?>