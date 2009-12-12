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
    public function attribute($params)
    {
        $node = $params['nodes'][$params['open']['node']['attribute']];
        //Define the variables
        $split = $params['suit']->explodeunescape($params['var']['quote'], $params['case'], $params['escape']);
        $size = count($split);
        for ($i = 0; $i < $size; $i++)
        {
            //If this is the first iteration of the pair
            if ($i % 2 == 0)
            {
                $split[$i] = trim($split[$i]);
                $last = substr_replace($split[$i], '', strlen($split[$i]) - 1);
                //If the syntax is not valid or the variable is whitelisted or blacklisted, do not prepare to define the variable
                if (substr($split[$i], strlen($split[$i]) - 1) != $params['var']['equal'] || (!(!array_key_exists('list', $params['var']) || ((!array_key_exists('blacklist', $params['var']) || !$params['var']['blacklist']) && in_array($last, $params['var']['list'])) || (array_key_exists('blacklist', $params['var']) && $params['var']['blacklist'] && !in_array($last, $params['var']['list'])))))
                {
                    $last = '';
                }
            }
            elseif ($last)
            {
                //Define the variable
                $split[$i] = $params['suit']->parse($params['nodes'], $split[$i]);
                $node['var'][$last] = $split[$i];
            }
        }
        //Add the new node to the stack
        $stack = array
        (
            'node' => $params['open']['open'] . implode($params['var']['quote'], $split) . $params['var']['quote'] . $params['open']['node']['close'],
            'nodes' => array(),
            'position' => $params['open']['position'],
            'skipnode' => array(),
            'skipignore' => $params['skipignore'],
            'stack' => array()
        );
        $stack['nodes'][$stack['node']] = $node;
        $stack = $params['suit']->helper->stack($stack);
        $params['stack'] = array_merge($params['stack'], $stack['stack']);
        $params['skipnode'] = array_merge($params['skipnode'], $stack['skipnode']);
        $params['skipignore'] = $stack['skipignore'];
        $params['preparsenodes'][$stack['node']] = $node;
        $params['case'] = $stack['node'];
        $params['usetaken'] = false;
        return $params;
    }

    public function comments($params)
    {
        $params['case'] = '';
        return $params;
    }

    public function condition($params)
    {
        //Calculate the left offset created by trimming
        $params['offset'] = ltrim($params['case'], $params['var']['trim']);
        $params['offset'] = strlen($params['offset']) - strlen($params['case']);
        //Trim the case if requested
        $params['case'] = trim($params['case'], $params['var']['trim']);
        //Hide the case if necessary
        if (($params['var']['condition'] && $params['var']['else']) || (!$params['var']['condition'] && !$params['var']['else']))
        {
            $params['case'] = '';
        }
        return $params;
    }

    public function conditionskip($params)
    {
        if (!empty($params['stack']))
        {
            $pop = array_pop($params['stack']);
            //If the case was not hidden, do not skip over everything between this opening string and its closing string
            if (($pop['node']['var']['condition'] && !$pop['node']['var']['else']) || (!$pop['node']['var']['condition'] && $pop['node']['var']['else']))
            {
                array_pop($params['skipnode']);
            }
            $params['stack'][] = $pop;
        }
        return $params;
    }

    public function escape($params)
    {
        //Calculate the left offset created by trimming
        $params['offset'] = ltrim($params['case'], $params['var']);
        $params['offset'] = strlen($params['offset']) - strlen($params['case']);
        //Trim the case if requested
        $params['case'] = trim($params['case'], $params['var']);
        return $params;
    }

    public function getsection($params)
    {
        //Add the case to the sections array
        $params['suit']->section->sections[] = $params['case'];
        $params['case'] = '';
        return $params;
    }

    public function loop($params)
    {
        $realnodes = array();
        $loopvars = array();
        $key = array_keys($params['nodes']);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //If the node should not be ignored
            if (!$params['nodes'][$key[$i]]['ignore'])
            {
                //If the node exists already, merge its loopvars later
                if ($key[$i] == $params['var']['node']['open'])
                {
                    $loopvars = $params['nodes'][$key[$i]]['var']['var'];
                }
                //Else, add it to the array
                else
                {
                    $realnodes[$key[$i]] = $params['nodes'][$key[$i]];
                }
            }
        }
        $iterationvars = array();
        $result = array
        (
            'ignore' => array(),
            'same' => array()
        );
        foreach (unserialize($params['var']['vars']) as $value)
        {
            $var = array
            (
                $params['var']['node']['open'] => array
                (
                    'close' => $params['var']['node']['close'],
                    'function' => array
                    (
                        array
                        (
                            'function' => 'loopvariables',
                            'class' => $this
                        )
                    ),
                    'var' => array
                    (
                        'escape' => $params['escape'],
                        'separator' => $params['var']['node']['separator'],
                        'var' => array_merge($value, $loopvars)
                    ) //This will be used by the function
                )
            );
            $result = $this->looppreparse($var[$params['var']['node']['open']]['var']['var'], $result);
            $iterationvars[] = $var;
        }
        $iterations = array();
        if (!empty($iterationvars))
        {
            if (!empty($result['ignore']))
            {
                $nodes = array
                (
                    $params['var']['node']['open'] => array
                    (
                        'close' => $params['var']['node']['close'],
                        'function' => array
                        (
                            array
                            (
                                'function' => 'loopvariables',
                                'class' => $this
                            )
                        ),
                        'ignore' => true
                    )
                );
                $key = array_keys($result['same']);
                $size = count($key);
                for ($i = 0; $i < $size; $i++)
                {
                    $nodes[$params['var']['node']['open'] . $key[$i]] = array
                    (
                        'close' => $params['var']['node']['close'],
                        'function' => array
                        (
                            array
                            (
                                'function' => 'loopvariable',
                                'class' => $this
                            )
                        ),
                        'var' => $result['same'][$key[$i]] //This will be used by the function
                    );
                }
            }
            else
            {
                $nodes = array
                (
                    $params['var']['node']['open'] => $iterationvars[0][$params['var']['node']['open']]
                );
            }
            $config = array
            (
                'escape' => $params['escape'],
                'preparse' => true
            );
            if (array_key_exists('label', $params['var']))
            {
                $config['label'] = $params['var']['label'];
            }
            //Preparse
            $result = $params['suit']->parse(array_merge($realnodes, $nodes), $params['case'], $config);
            $config = array
            (
                'taken' => $result['taken']
            );
            $size = count($iterationvars);
            for ($i = 0; $i < $size; $i++)
            {
                $config = array
                (
                    'taken' => $result['taken']
                );
                if (array_key_exists('label', $params['var']))
                {
                    $config['label'] = $params['var']['label'] . $i;
                }
                //Parse for this iteration
                $thiscase = $params['suit']->parse(array_merge($realnodes, $result['nodes'], $iterationvars[$i]), $result['return'], $config);
                //Trim the result if requested
                $thiscase = ltrim($thiscase, $params['var']['trim']);
                if ($size == $i + 1)
                {
                    $thiscase = rtrim($thiscase, $params['var']['trim']);
                }
                //Append the result
                $iterations[] = $thiscase;
            }
        }
        //Implode the iterations
        $params['case'] = implode($params['var']['delimiter'], $iterations);
        return $params;
    }

    public function looppreparse($iterationvars, $return)
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

    public function loopvariable($params)
    {
        if (!$params['case'])
        {
            $params['case'] = $params['var'];
        }
        else
        {
            $params['case'] = $params['open']['open'] . $params['case'] . $params['open']['node']['close'];
            $params['usetaken'] = false;
        }
        return $params;
    }

    public function loopvariables($params)
    {
        //Split up the file, paying attention to escape strings
        $split = $params['suit']->explodeunescape($params['var']['separator'], $params['case'], $params['escape']);
        $params['case'] = $params['var']['var'];
        foreach ($split as $value)
        {
            $params['case'] = $params['case'][$value];
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
        $size = count($params['stack']);
        for ($i = 0; $i < $size; $i++)
        {
            if (!array_key_exists('function', $params['stack'][$i]['node']))
            {
                $params['stack'][$i]['node']['function'] = array();
            }
            //Make all of the nodes remove all content in the case that takes place after this return.
            array_splice(
                $params['stack'][$i]['node']['function'],
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
            //Make the last node to be closed remove everything after this return.
            if ($i == 0)
            {
                $params['stack'][$i]['node']['function'][] = array
                (
                    'class' => $this,
                    'function' => 'returninglast'
                );
            }
            $params['skipnode'][] = $params['stack'][$i]['node']['close'];
        }
        //If the stack is empty, remove everything after this return.
        if (empty($params['stack']))
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
        $split = $params['suit']->explodeunescape($params['var']['separator'], $params['case'], $params['escape']);
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

    public function variables($params)
    {
        //Split up the file, paying attention to escape strings
        $split = $params['suit']->explodeunescape($params['var']['separator'], $params['case'], $params['escape']);
        $params['case'] = $params['suit']->vars;
        foreach ($split as $value)
        {
            $params['case'] = $params['case'][$value];
        }
        return $params;
    }
}
?>