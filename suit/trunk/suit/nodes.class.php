<?php
/**
**@This program is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@This program is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class Nodes
{
    public function assign($params)
    {
        if ($params['var']['var'])
        {
            $params['suit']->vars[$params['var']['var']] = $params['case'];
        }
        $params['case'] = '';
        return $params;
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
        if (!$result['ignore'])
        {
            if (array_key_exists('onesided', $params['var']) && $params['var']['onesided'])
            {
                $params['var'] = $result['node']['var'];
            }
            else
            {
                //Add the new node to the stack
                $stack = array
                (
                    'node' => $params['case'],
                    'nodes' => array(),
                    'position' => $params['open']['position'],
                    'skipnode' => array(),
                    'stack' => array()
                );
                $stack['nodes'][$stack['node']] = $result['node'];
                $stack = $params['suit']->helper->stack($stack);
                $params['stack'] = array_merge($params['stack'], $stack['stack']);
                $params['skipnode'] = array_merge($params['skipnode'], $stack['skipnode']);
                $params['preparse']['nodes'][$stack['node']] = $result['node'];
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
                $stack = array
                (
                    'node' => $params['open']['node']['attribute'],
                    'nodes' => array(),
                    'position' => $params['open']['position'],
                    'skipnode' => array(),
                    'stack' => array()
                );
                $stack['nodes'][$params['open']['node']['attribute']] = $node;
                $stack = $params['suit']->helper->stack($stack);
                $params['stack'] = array_merge($params['stack'], $stack['stack']);
            }
        }
        return $params;
    }

    public function attributedefine($params, $node)
    {
        //Define the variables
        $split = $params['suit']->explodeunescape($params['var']['quote'], $params['case'], $params['config']['escape']);
        unset($split[count($split) - 1]);
        $ignore = false;
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
                    if (array_key_exists('list', $params['var']) && (((!array_key_exists('blacklist', $params['var']) || !$params['var']['blacklist']) && !in_array($name, $params['var']['list'])) || (array_key_exists('blacklist', $params['var']) && $params['var']['blacklist'] && in_array($name, $params['var']['list']))))
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
                    $node['var'][$name] = $result['return'];
                }
                else
                {
                    $ignore = true;
                    break;
                }
            }
        }
        return array
        (
            'ignore' => $ignore,
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
                if ($pop['node']['var']['condition'] == '0' || strtolower($pop['node']['var']['condition']) == 'false' || strtolower($pop['node']['var']['condition']) == 'empty')
                {
                    $pop['node']['var']['condition'] = '';
                }
                //If the case was not hidden, do not skip over everything between this opening string and its closing string
                if (($pop['node']['var']['condition'] && !$pop['node']['var']['else']) || (!$pop['node']['var']['condition'] && $pop['node']['var']['else']))
                {
                    array_pop($params['skipnode']);
                }
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

    public function loop($params)
    {
        $iterationvars = array();
        $result = array
        (
            'ignore' => array(),
            'same' => array()
        );
        if (!is_array($params['var'][$params['var']['unserialize']]))
        {
            throw new Exception();
        }
        foreach ($params['var'][$params['var']['unserialize']] as $value)
        {
            $var = array
            (
                $params['var']['node'] => $params['nodes'][$params['var']['node']]
            );
            foreach ($value as $key => $value2)
            {
                if (is_array($var[$params['var']['node']]['var']['var']))
                {
                    $var[$params['var']['node']]['var']['var'][$key] = $value2;
                }
                else
                {
                    $var[$params['var']['node']]['var']['var']->$key = $value2;
                }
            }
            $result = $this->looppreparse($var[$params['var']['node']]['var']['var'], $result);
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
                    'taken' => $result['taken']
                );
                if (array_key_exists('label', $params['var']))
                {
                    $config['label'] = $params['var']['label'] . strval($i);
                }
                //Parse for this iteration
                $iterations[] = $params['suit']->parse(array_merge($params['nodes'], $result['nodes'], $iterationvars[$i]), $result['return'], $config);
            }
        }
        //Implode the iterations
        $params['case'] = implode($params['var']['delimiter'], $iterations);
        return $params;
    }

    public function looppreparse($iterationvars, $return)
    {
        foreach ($iterationvars as $key => $value)
        {
            //If this node is not already being ignored
            if (!array_key_exists($key, $return['ignore']))
            {
                $different = false;
                $key2 = array_keys($return['same']);
                $size2 = count($key2);
                for ($j = 0; $j < $size2; $j++)
                {
                    //If this node has the same opening string as the one we are checking but is different overall, remove the checking string and note the difference
                    if ($value != $return['same'][$key2[$j]] && $key == $key2[$j])
                    {
                        $different = true;
                        unset($return['same'][$key2[$j]]);
                    }
                }
                //If there is an instance of a node that has the same opening string but is different overall, ignore it
                if ($different)
                {
                    $return['ignore'][$key] = $value;
                }
                //Else, prepare to preparse it
                elseif (!array_key_exists($key, $return['same']))
                {
                    $return['same'][$key] = $value;
                }
            }
        }
        return $return;
    }

    public function loopvariables($params)
    {
        if (!array_key_exists($params['case'], $params['var']['ignore']))
        {
            //Split up the file, paying attention to escape strings
            $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['case'], $params['config']['escape']);
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
        $params['break'] = true;
        return $params;
    }

    public function templates($params)
    {
        //Split up the file, paying attention to escape strings
        $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['case'], $params['config']['escape']);
        $code = array();
        $size = count($split);
        for ($i = 0; $i < $size; $i++)
        {
            //If this is the template file, get the file's content
            if ($i == 0)
            {
                $template = file_get_contents($params['var']['files']['templates'] . '/' . $split[$i] . '.' . $params['var']['filetypes']['templates']);
            }
            //Else, prepare to include the file
            else
            {
                $code[] = str_replace(array('../', '..\\'), '', $params['var']['files']['code'] . '/' . $split[$i] . '.' . $params['var']['filetypes']['code']);
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
        $params['case'] = preg_replace('/[\s]+$/m', '', ltrim($params['case']));
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
                $params['ignore'] = true;
                $params['taken'] = false;
            }
        }
        catch (Exception $e)
        {
            if ($params['var']['var'])
            {
                $params['suit']->vars[$params['var']['var']] = $e;
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
        $split = $params['suit']->explodeunescape($params['var']['delimiter'], $params['case'], $params['config']['escape']);
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
        if ($params['var']['serialize'])
        {
            $params['case'] = serialize($params['case']);
        }
        return $params;
    }
}
?>