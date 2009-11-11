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
    public function condition($params)
    {
        $return = '';
        //Calculate the left offset created by trimming
        $offset = ltrim($params['case'], $params['var']['trim']);
        $params['suit']->extra['offset'] = strlen($offset) - strlen($params['case']);
        //Trim the case if requested
        $params['case'] = trim($params['case'], $params['var']['trim']);
        //If the boolean is true, strip the tags. If not, hide the entire thing
        if ($params['var']['bool'])
        {
            $return = $params['case'];
        }
        return $return;
    }

    public function getsection($params)
    {
        //Add the case to the sections array
        $params['suit']->extra['sections'][] = $params['case'];
        //Replace the tags
        return $params['var']['open'] . $params['case'] . $params['var']['close'];
    }

    public function loop($params)
    {
        $iterations = array();
        $realnodes = array();
        $loopvars = array();
        foreach ($params['nodes'] as $key => $value)
        {
            //If the node should not be ignored
            if (!$value['ignore'])
            {
                //If the node exists already, merge its loopvars later
                if ($key == $params['var']['config']['loopopen'] && is_array($value['var']) && is_array($value['var']['var']))
                {
                    $loopvars = $value['var']['var'];
                }
                //Else, add it to the array
                else
                {
                    $realnodes[$key] = $value;
                }
            }
        }
        $unique = array();
        $result = array
        (
            'ignore' => array(),
            'same' => array()
        );
        foreach ($params['var']['array'] as $value)
        {
            if (!is_array($value['nodes']))
            {
                $value['nodes'] = array();
            }
            $value['nodes'][$params['var']['config']['loopopen']] = array
            (
                'class' => $this,
                'close' => $params['var']['config']['loopclose'],
                'function' => 'loopvars',
                'var' => array
                (
                    'escape' => $params['config']['escape'],
                    'separator' => $params['var']['config']['separator']
                ) //This will be used by the function
            );
            if (is_array($value['vars']))
            {
                $value['nodes'][$params['var']['config']['loopopen']]['var']['var'] = array_merge($value['vars'], $loopvars);
            }
            else
            {
                $value['nodes'][$params['var']['config']['loopopen']]['var']['var'] = $loopvars;
            }
            $result = $params['suit']->nodes->looppreparse($value['nodes'], $result);
            $unique[] = $value;
        }
        $config = array
        (
            'escape' => $params['escape'],
            'preparse' => true
        );
        if (array_key_exists('label', $params['var']['config']))
        {
            $config['label'] = $params['var']['config']['label'];
        }
        $result = $params['suit']->parse(array_merge($realnodes, $result['same'], $result['ignore']), $params['case'], $config);
        foreach ($unique as $key => $value)
        {
            $value['escape'] = $result['escape'];
            $value['taken'] = $result['taken'];
            //Parse for this iteration
            $thiscase = $params['suit']->parse(array_merge($realnodes, $value['nodes']), $result['return'], $value);
            //Trim the result if requested
            $thiscase = ltrim($thiscase, $params['var']['config']['trim']);
            if (count($unique) == $key + 1)
            {
                $thiscase = rtrim($thiscase, $params['var']['config']['trim']);
            }
            //Append the result
            $iterations[] = $thiscase;
        }
        return implode($params['var']['implode'], $iterations);
    }

    public function looppreparse($nodes, $return)
    {
        foreach ($nodes as $key => $value)
        {
            $node = array
            (
                'close' => $value['close'],
                'skip' => $value['skip'],
                'ignore' => true
            );
            //If this node is not already being ignored
            if (!array_key_exists($key, $return['ignore']))
            {
                $different = false;
                $clone = array();
                foreach ($return['same'] as $key2 => $value2)
                {
                    //If this node has the same opening string as the one we are checking but is different overall, remove the checking string and note the difference
                    if ($value != $value2 && $key == $key2)
                    {
                        $different = true;
                    }
                    else
                    {
                        $clone[$key2] = $value2;
                    }
                }
                $return['same'] = $clone;
                //If there is an instance of a node that has the same opening string but is different overall, ignore it
                if ($different)
                {
                    $return['ignore'][$key] = $node;
                }
                //Else, prepare to preparse it
                elseif (!array_key_exists($key, $return['same']))
                {
                    $return['same'][$key] = $value;
                }
            }
            //Else, if the original does not parse in between the opening and closing strings while the current one does, parse in between the opening and closing strings
            elseif ($return['ignore'][$key]['skip'] && (!array_key_exists('ignore', $value) || !$value['ignore']))
            {
                $return['ignore'][$key]['skip'] = false;
            }
        }
        return $return;
    }

    public function loopvars($params)
    {
        //Split up the file, paying attention to escape strings
        $split = $params['suit']->explodeunescape($params['var']['separator'], $params['case'], $params['var']['escape']);
        foreach ($split as $value)
        {
            $params['var']['var'] = $params['var']['var'][$value];
        }
        return $params['var']['var'];
    }
}
?>