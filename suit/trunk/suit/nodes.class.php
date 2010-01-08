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
    public function assign($params)
    {
        //If a variable is provided and it not is whitelisted or blacklisted
        if ($params['var']['var'] && $this->listing($params['var']['var'], $params['var']))
        {
            //Split up the file, paying attention to escape strings
            $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['var']['var'], $params['config']['escape'], $params['config']['insensitive']);
            $this->assignvariable($split, $params['case'], $params['suit']->vars);
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
        if (array_key_exists('onesided', $params['var']) && $params['var']['onesided'])
        {
            $node = array
            (
                'var' => $params['open']['node']['var']['var']
            );
        }
        else
        {
            $node = $params['nodes'][$params['open']['node']['attribute']];
        }
        $result = $this->attributedefine($params, $node);
        $params['case'] = $params['open']['open'] . $params['case'] . $params['open']['node']['close'];
        $params['taken'] = false;
        if (!$result['ignored'])
        {
            if (array_key_exists('onesided', $params['var']) && $params['var']['onesided'])
            {
                $params['var'] = $result['node']['var'];
                $params['taken'] = true;
            }
            else
            {
                //Add the new node to the stack
                $stack = $params['suit']->stack($result['node'], $params['case'], $params['open']['position']);
                $params['stack'] = array_merge($params['stack'], $stack['stack']);
                $params['skipnode'] = array_merge($params['skipnode'], $stack['skipnode']);
                $params['preparse']['nodes'][$params['case']] = $result['node'];
            }
        }
        else
        {
            //Reserve the space
            $params['preparse']['ignored'][] = array($params['open']['position'], $params['position'] + strlen($params['open']['node']['close']));
            if (!array_key_exists('onesided', $params['var']) || !$params['var']['onesided'])
            {
                //Prepare for the closing string
                $node = array
                (
                    'close' => $params['nodes'][$params['open']['node']['attribute']]['close']
                );
                if (array_key_exists('skip', $params['nodes'][$params['open']['node']['attribute']]))
                {
                    $node['skip'] = $params['nodes'][$params['open']['node']['attribute']]['skip'];
                }
                $stack = $params['suit']->stack($node, $params['open']['node']['attribute'], $params['open']['position']);
                $params['stack'] = array_merge($params['stack'], $stack['stack']);
                $params['skipnode'] = array_merge($params['skipnode'], $stack['skipnode']);
            }
            else
            {
                $params['function'] = false;
            }
        }
        return $params;
    }

    public function attributedefine($params, $node)
    {
        //Define the variables
        $split = $params['suit']->explodeunescape($params['var']['quote'], $params['case'], $params['config']['escape'], $params['config']['insensitive']);
        unset($split[count($split) - 1]);
        $ignored = false;
        $size = count($split);
        for ($i = 0; $i < $size; $i++)
        {
            //If this is the first iteration of the pair
            if ($i % 2 == 0)
            {
                $name = trim($split[$i]);
                //If the syntax is valid
                if (substr($name, strlen($name) - strlen($params['var']['equal'])) == $params['var']['equal'])
                {
                    $name = substr_replace($name, '', strlen($name) - strlen($params['var']['equal']));
                    //If the variable is whitelisted or blacklisted, do not prepare to define the variable
                    if (!$this->listing($name, $params['var']))
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
                $config = array
                (
                    'escape' => $params['config']['escape'],
                    'preparse' => true
                );
                //Define the variable
                $result = $params['suit']->parse($params['nodes'], $split[$i], $config);
                if (empty($result['ignored']))
                {
                    if (strtolower($result['return']) == 'false')
                    {
                        $result['return'] = '';
                    }
                    $node['var'][$name] = $result['return'];
                }
                else
                {
                    $ignored = true;
                    break;
                }
            }
        }
        return array
        (
            'ignored' => $ignored,
            'node' => $node
        );
    }

    public function comments($params)
    {
        $params['case'] = '';
        return $params;
    }

    public function condition($params)
    {
        $params['offset'] = -strlen($params['open']['open']);
        //Hide the case if necessary
        if (($params['var']['condition'] && $params['var']['else']) || (!$params['var']['condition'] && !$params['var']['else']))
        {
            $params['case'] = '';
        }
        return $params;
    }

    public function conditionstack($params)
    {
        if (!empty($params['stack']))
        {
            $pop = array_pop($params['stack']);
            if (array_key_exists('var', $pop['node']) && array_key_exists('condition', $pop['node']['var']) && array_key_exists('else', $pop['node']['var']))
            {
                $pop['node']['var']['condition'] = strval($pop['node']['var']['condition']);
                if ($pop['node']['var']['condition'] == '0' || strtolower($pop['node']['var']['condition']) == 'null' || strtolower($pop['node']['var']['condition']) == 'array()')
                {
                    $pop['node']['var']['condition'] = '';
                }
                //If the case should not be hidden, do not skip over everything between this opening string and its closing string
                if (($pop['node']['var']['condition'] && !$pop['node']['var']['else']) || (!$pop['node']['var']['condition'] && $pop['node']['var']['else']) && array_key_exists('skip', $pop['node']) && $pop['node']['skip'])
                {
                    $pop['node']['skip'] = false;
                    array_pop($params['skipnode']);
                }
                $params['preparse']['nodes'][$params['case']] = $pop['node'];
            }
            //Else, if the node was ignored, do not skip over everything between this opening string and its closing string
            elseif ($pop['node']['close'] == $params['nodes'][$params['open']['node']['attribute']]['close'] && array_key_exists('skip', $pop['node']) && $pop['node']['skip'])
            {
                $pop['node']['skip'] = false;
                array_pop($params['skipnode']);
            }
            $params['stack'][] = $pop;
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
        $result = array
        (
            'ignore' => $params['nodes'][$params['var']['node']]['var']['ignore'],
            'same' => array()
        );
        if (!is_array($params['var'][$params['var']['unserialize']]))
        {
            return $params;
        }
        foreach ($params['var'][$params['var']['unserialize']] as $value)
        {
            $var = array
            (
                $params['var']['node'] => $params['nodes'][$params['var']['node']]
            );
            foreach ($value as $key => $value2)
            {
                if (!array_key_exists($key, $var[$params['var']['node']]['var']['var']))
                {
                    $var[$params['var']['node']]['var']['var'][$key] = $value2;
                }
            }
            $result = $this->looppreparse($var[$params['var']['node']]['var']['var'], count($iterationvars), $result);
            $iterationvars[] = $var;
        }
        $iterations = array();
        if (!empty($iterationvars))
        {
            $nodes = array
            (
                $params['var']['node'] => $iterationvars[0][$params['var']['node']]
            );
            $nodes[$params['var']['node']]['var']['ignore'] = $result['ignore'];
            $config = array
            (
                'escape' => $params['config']['escape'],
                'insensitive' => $params['config']['insensitive'],
                'preparse' => true
            );
            if (array_key_exists('label', $params['var']))
            {
                $config['label'] = $params['var']['label'];
            }
            //Preparse
            $result = $params['suit']->parse(array_merge($params['nodes'], $nodes), $params['case'], $config);
            $size = count($iterationvars);
            for ($i = 0; $i < $size; $i++)
            {
                $config = array
                (
                    'escape' => $params['config']['escape'],
                    'insensitive' => $params['config']['insensitive'],
                    'preparse' => true,
                    'taken' => $result['taken']
                );
                if (array_key_exists('label', $params['var']))
                {
                    $config['label'] = $params['var']['label'] . strval($i);
                }
                //Parse for this iteration
                $result2 = $params['suit']->parse(array_merge($params['nodes'], $result['nodes'], $iterationvars[$i]), $result['return'], $config);
                if (!$result2['ignored'])
                {
                    $iterations[] = $result2['return'];
                }
                else
                {
                    $params['case'] = $params['open']['open'] . $params['case'] . $params['open']['node']['close'];
                    $params['taken'] = false;
                    //Reserve the space
                    $params['preparse']['ignored'][] = array($params['open']['position'], $params['position'] + strlen($params['open']['node']['close']));
                    return $params;
                }
            }
        }
        //Implode the iterations
        $params['case'] = implode($params['var']['delimiter'], $iterations);
        return $params;
    }

    public function looppreparse($iterationvars, $iteration, $return)
    {
        $key = array_keys($iterationvars);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //If this node is not already being ignored
            if (!array_key_exists($key[$i], $return['ignore']))
            {
                $different = false;
                $key2 = array_keys($return['same']);
                $size2 = count($key2);
                for ($j = 0; $j < $size2; $j++)
                {
                    //If this node has the same opening string as the one we are checking but is different overall, remove the checking string and note the difference
                    if ($iterationvars[$key[$i]] != $return['same'][$key2[$j]] && $key[$i] == $key2[$j])
                    {
                        $different = true;
                        unset($return['same'][$key2[$j]]);
                    }
                }
                //If this is a new value, and this is not the first iteration, remove the checking string and note the difference
                if (!array_key_exists($key[$i], $return['same']) && $iteration > 0)
                {
                    $different = true;
                }
                //If there is an instance of a node that has the same opening string but is different overall, ignore it
                if ($different)
                {
                    $return['ignore'][$key[$i]] = $iterationvars[$key[$i]];
                }
                //Else, prepare to preparse it
                elseif (!array_key_exists($key[$i], $return['same']))
                {
                    $return['same'][$key[$i]] = $iterationvars[$key[$i]];
                }
            }
        }
        return $return;
    }

    public function loopstack($params)
    {
        if ($params['stack'])
        {
            $pop = array_pop($params['stack']);
            //If specified, do not skip over everything between this opening string and its closing string
            if (array_key_exists('var', $pop['node']) && array_key_exists('skip', $pop['node']['var']) && !$pop['node']['var']['skip'] && array_key_exists('skip', $pop['node']) && $pop['node']['skip'])
            {
                $pop['node']['skip'] = false;
                array_pop($params['skipnode']);
            }
            $params['preparse']['nodes'][$params['case']] = $pop['node'];
            $params['stack'][] = $pop;
        }
        return $params;
    }

    public function loopvariables($params)
    {
        //Split up the file, paying attention to escape strings
        $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['case'], $params['config']['escape'], $params['config']['insensitive']);
        //If the case should not be ignored
        if (!array_key_exists($split[0], $params['var']['ignore']))
        {
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
            if ($params['var']['bool'])
            {
                $params['case'] = boolval($params['case']);
            }
            if ($params['var']['serialize'])
            {
                $params['case'] = serialize($params['case']);
            }
        }
        else
        {
            //Reserve the space
            $params['preparse']['ignored'][] = array($params['open']['position'], $params['position'] + strlen($params['open']['node']['close']));
            $params['case'] = $params['open']['open'] . $params['case'] . $params['open']['node']['close'];
            $params['taken'] = false;
        }
        return $params;
    }

    public function parse($params)
    {
        $config = array
        (
            'escape' => $params['config']['escape'],
            'insensitive' => $params['config']['insensitive']
        );
        if (in_array('label', $params['var']))
        {
            $config['label'] = $params['var']['label'];
        }
        $params['case'] = $params['suit']->parse($params['nodes'], $params['case'], $config);
        return $params;
    }

    public function replace($params)
    {
        $params['case'] = str_replace($params['var']['search'], $params['var']['replace'], $params['case']);
        return $params;
    }

    public function returning($params)
    {
        $params['case'] = '';
        $stack = array_reverse($params['stack']);
        $skipnode = array();
        $size = count($stack);
        for ($i = 0; $i < $size; $i++)
        {
            //If the stack count has not been modified or it specifies this many stacks
            if (!$params['var']['stack'] || intval($params['var']['stack']) > $i)
            {
                if (!array_key_exists('function', $params['stack'][count($stack) - 1 - $i]['node']))
                {
                    $params['stack'][count($stack) - 1 - $i]['node']['function'] = array();
                }
                //Make all of the nodes remove all content in the case that takes place after this return.
                array_splice(
                    $params['stack'][count($stack) - 1 - $i]['node']['function'],
                    0,
                    0,
                    array
                    (
                        array
                        (
                            'class' => $this,
                            'function' => 'returningfirst'
                        )
                    )
                );
                //Make the last node to be closed remove everything after this return
                if ($i == count($stack) - 1)
                {
                    $params['stack'][0]['node']['function'][] = array
                    (
                        'class' => $this,
                        'function' => 'returninglast'
                    );
                }
                $skipnode[] = $params['stack'][count($stack) - 1 - $i]['node']['close'];
            }
            else
            {
                break;
            }
        }
        $params['skipnode'] = array_merge($params['skipnode'], array_reverse($skipnode));
        //If the stack is empty, and if the stack count has not been modified or it specifies at least one stack
        if (empty($params['stack']) && (!$params['var']['stack'] || intval($params['var']['stack']) > 0))
        {
            $params['last'] = $params['open']['position'];
            $params = $this->returninglast($params);
        }
        return $params;
    }

    public function returningfirst($params)
    {
        $params['case'] = substr_replace($params['case'], '', $params['last'] - $params['open']['position'] - strlen($params['open']['open']));
        return $params;
    }

    public function returninglast($params)
    {
        $params['return'] = substr_replace($params['return'], '', $params['last']);
        $params['parse'] = false;
        return $params;
    }

    public function templates($params)
    {
        //Split up the file, paying attention to escape strings
        $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['case'], $params['config']['escape'], $params['config']['insensitive']);
        $code = array();
        $size = count($split);
        for ($i = 0; $i < $size; $i++)
        {
            //If this is the template file, get the file's contents
            if ($i == 0)
            {
                $filepath = $params['var']['files']['templates'] . '/' . str_replace(array('../', '..\\'), '', $split[$i]) . '.' . $params['var']['filetypes']['templates'];
                //If the template file exists
                if (is_file($filepath))
                {
                    $template = file_get_contents($filepath);
                }
            }
            //Else, prepare to include the file
            else
            {
                $code[] = $params['var']['files']['code'] . '/' . str_replace(array('../', '..\\'), '', $split[$i]) . '.' . $params['var']['filetypes']['code'];
            }
        }
        if (array_key_exists('label', $params['var']))
        {
            $params['case'] = $params['suit']->gettemplate($template, $code, $params['var']['label']);
        }
        else
        {
            $params['case'] = $params['suit']->gettemplate($template, $code);
        }
        return $params;
    }

    public function trim($params)
    {
        $nodes = array
        (
            '<pre' => array
            (
                'close' => '</pre>',
                'function' => array
                (
                    array
                    (
                        'function' => 'trimbefore',
                        'class' => $this
                    )
                ),
                'skip' => true
            ),
            '<textarea' => array
            (
                'close' => '</textarea>',
                'function' => array
                (
                    array
                    (
                        'function' => 'trimbefore',
                        'class' => $this
                    )
                ),
                'skip' => true
            )
        );
        $params['suit']->vars['last'] = 0;
        $params['case'] = $params['suit']->parse($nodes, $params['case']);
        $copy = substr($params['case'], $params['suit']->vars['last']);
        if (!$params['suit']->vars['last'])
        {
            $copy = ltrim($copy);
        }
        $replaced = preg_replace('/[\s]+$/m', '', $copy);
        $params['case'] = substr_replace($params['case'], $replaced, $params['suit']->vars['last']);
        return $params;
    }

    public function trimbefore($params)
    {
        $original = substr($params['return'], $params['last'], $params['open']['position'] - $params['last']);
        $copy = $original;
        if (!$params['suit']->vars['last'])
        {
            $copy = ltrim($copy);
        }
        $replaced = preg_replace('/[\s]+$/m', '', $copy);
        $params['return'] = substr_replace($params['return'], $replaced, $params['last'], $params['open']['position'] - $params['last']);
        $params['open']['position'] += strlen($replaced) - strlen($original);
        $params['position'] += strlen($replaced) - strlen($original);
        $params['case'] = $params['open']['open'] . $params['case'] . $params['open']['node']['close'];
        $params['taken'] = false;
        $params['suit']->vars['last'] = $params['open']['position'] + strlen($params['case']);
        return $params;
    }

    public function trying($params)
    {
        if ($params['var']['var'])
        {
            $params['suit']->vars[$params['var']['var']] = '';
        }
        try
        {
            $config = array
            (
                'escape' => $params['config']['escape'],
                'preparse' => true
            );
            $result = $params['suit']->parse($params['nodes'], $params['case'], $config);
            if (empty($result['ignored']))
            {
                $params['case'] = $result['return'];
            }
            //Else, ignore this case
            else
            {
                $params['case'] = $params['open']['open'] . $params['case'] . $params['open']['node']['close'];
                $params['taken'] = false;
                //Reserve the space
                $params['preparse']['ignored'][] = array($params['open']['position'], $params['position'] + strlen($params['open']['node']['close']));
            }
        }
        catch (Exception $e)
        {
            //If a variable is provided and it not is whitelisted or blacklisted
            if ($params['var']['var'] && $this->listing($params['var']['var'], $params['var']))
            {
                //Split up the file, paying attention to escape strings
                $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['var']['var'], $params['config']['escape'], $params['config']['insensitive']);
                $this->assignvariable($split, $e, $params['suit']->vars);
            }
            $params['case'] = '';
        }
        return $params;
    }

    public function unserialize($params)
    {
        $params['var'][$params['var']['unserialize']] = unserialize($params['var'][$params['var']['unserialize']]);
        return $params;
    }

    public function variables($params)
    {
        //Split up the file, paying attention to escape strings
        $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['case'], $params['config']['escape'], $params['config']['insensitive']);
        $params['case'] = $params['suit']->vars;
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
        if ($params['var']['bool'])
        {
            if ($params['case'])
            {
                $params['case'] = 'true';
            }
            else
            {
                $params['case'] = 'false';
            }
        }
        if ($params['var']['serialize'])
        {
            $params['case'] = serialize($params['case']);
        }
        return $params;
    }
}
?>