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
class Helper
{
    public function __construct($owner)
    {
        $this->owner = $owner;
    }

    public function closingstring($params)
    {
        if (!empty($params['skipnode']))
        {
            $skippop = array_pop($params['skipnode']);
        }
        else
        {
            $skippop = false;
        }
        //If a value was not popped or the closing string for this node matches it
        if ($skippop === false || $params['nodes'][$params['node']]['close'] == $skippop)
        {
            //If a value was not popped or it explictly says to escape
            if ($skippop === false || (array_key_exists('skipescape', $params['nodes'][$params['node']]) && $params['nodes'][$params['node']]['skipescape']))
            {
                $params['position'] = $params['unescape']['position'];
                $params['return'] = $params['unescape']['string'];
            }
            //If this position should not be overlooked
            if (!$params['unescape']['condition'])
            {
                //If there is an offset, decrement it
                if ($params['skipoffset'])
                {
                    $params['skipoffset']--;
                }
                //If the stack is not empty
                elseif (!empty($params['stack']))
                {
                    $params['open'] = array_pop($params['stack']);
                    //If this closing string matches the last node's
                    if ($params['open']['node']['close'] == $params['nodes'][$params['node']]['close'])
                    {
                        $params = $this->transform($params);
                    }
                }
            }
        }
        //Else, put the popped value back
        else
        {
            $params['skipnode'][] = $skippop;
        }
        return $params;
    }

    public function includefile($template, $code)
    {
        $suit = $this->owner;
        //Include the code file without the possibility of affecting the gettemplate function
        include $code;
        return $template;
    }

    public function openingstring($params)
    {
        if (!empty($params['skipnode']))
        {
            $skippop = array_pop($params['skipnode']);
        }
        else
        {
            $skippop = false;
        }
        //If a value was not popped from skipnode
        if ($skippop === false)
        {
            $params['position'] = $params['unescape']['position'];
            $params['return'] = $params['unescape']['string'];
            //If this position should not be overlooked
            if (!$params['unescape']['condition'])
            {
                $params = $this->stack($params);
            }
        }
        else
        {
            //Put it back
            $params['skipnode'][] = $skippop;
            $skipclose = array($params['nodes'][$params['node']]['close']);
            if (array_key_exists('attribute', $params['nodes'][$params['node']]))
            {
                $skipclose[] = $params['nodes'][$params['nodes'][$params['node']]['attribute']]['close'];
            }
            //If the closing string for this node matches it
            if (in_array($skippop, $skipclose))
            {
                //If it explictly says to escape
                if (array_key_exists('skipescape', $params['nodes'][$params['node']]) && $params['nodes'][$params['node']]['skipescape'])
                {
                    $params['position'] = $params['unescape']['position'];
                    $params['return'] = $params['unescape']['string'];
                }
                //If this position should not be overlooked
                if (!$params['unescape']['condition'])
                {
                    //Account for it
                    $params['skipnode'][] = $skippop;
                    $params['skipoffset']++;
                }
            }
        }
        return $params;
    }

    public function parsecache($nodes, $return, $config)
    {
        $values = array();
        $key = array_keys($nodes);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            $array = array($key[$i]);
            if (array_key_exists('close', $nodes[$key[$i]]))
            {
                $array[] = $nodes[$key[$i]]['close'];
            }
            $values[] = $array;
        }
        return md5(md5(serialize($return)) . md5(serialize($values)) . md5(serialize($config['taken'])));
    }

    public function parseconfig($config)
    {
        if (!array_key_exists('escape', $config))
        {
            $config['escape'] = $this->owner->escapestring;
        }
        if (!array_key_exists('preparse', $config))
        {
            $config['preparse'] = false;
        }
        if (!array_key_exists('taken', $config))
        {
            $config['taken'] = array();
        }
        return $config;
    }

    public function parsepositions($nodes, $return, $taken)
    {
        $strings = array();
        $key = array_keys($nodes);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //If the close string exists, then there might be some instances to parse
            if (array_key_exists('close', $nodes[$key[$i]]))
            {
                $strings[$key[$i]] = array($key[$i], 0);
                $strings[$nodes[$key[$i]]['close']] = array($key[$i], 1);
            }
        }
        //Order the strings by the length, descending
        uksort($strings, array('Helper', 'sort'));
        $params = array
        (
            'function' => 'parse',
            'pos' => array(),
            'repeated' => array(),
            'return' => $return,
            'strings' => $strings,
            'taken' => $taken
        );
        return $this->positions($params);
    }

    public function parseunescape($position, $escape, $string)
    {
        $count = 0;
        //If the escape string is not empty
        if ($escape)
        {
            //Count how many escape characters are directly to the left of this position
            while (abs($start = $position - $count - strlen($escape)) == $start && substr($string, $start, strlen($escape)) == $escape)
            {
                $count += strlen($escape);
            }
            //Determine how many escape strings are directly to the left of this position
            $count = $count / strlen($escape);
        }
        //If the number of escape strings directly to the left of this position are odd, the position should be overlooked
        $condition = $count % 2;
        //If the condition is true, (x + 1) / 2 of them should be removed
        if ($condition)
        {
            $count++;
        }
        //Adjust the position
        $position -= strlen($escape) * ($count / 2);
        //Remove the decided number of escape strings
        $string = substr_replace($string, '', $position, strlen($escape) * ($count / 2));
        return array
        (
            'condition' => $condition,
            'position' => $position,
            'string' => $string
        );
    }

    public function positions($params)
    {
        foreach ($params['strings'] as $params['key'] => $params['value'])
        {
            //If the string has not already been used
            if (!in_array($params['key'], $params['repeated']))
            {
                $params = $this->positionsloop($params);
                //Make sure this string is not repeated
                $params['repeated'][] = $params['key'];
            }
        }
        return $params['pos'];
    }

    public function positionsloop($params)
    {
        $position = -1;
        //Find the next position of the string
        while (($position = $this->strpos($params['return'], $params['key'], $position + 1, $params['function'])) !== false)
        {
            $success = true;
            foreach ($params['taken'] as $value)
            {
                //If this string instance is in this reserved range
                if (($position > $value[0] && $position < $value[1]) || ($position + strlen($params['key']) > $value[0] && $position + strlen($params['key']) < $value[1]))
                {
                    $success = false;
                    break;
                }
            }
            //If this string instance is not in any reserved range
            if ($success)
            {
                //Add the position
                $params['pos'][$position] = $params['value'];
                //Reserve all positions taken up by this string instance
                $params['taken'][] = array($position, $position + strlen($params['key']));
            }
        }
        return $params;
    }

    public function preparse($params)
    {
        $key = array_keys($params['preparse']['ignored']);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //If this reserved range is in this case, adjust the range to the removal of the opening string
            if ($params['open']['position'] < $params['preparse']['ignored'][$key[$i]][0] && $params['position'] + strlen($params['open']['node']['close']) > $params['preparse']['ignored'][$key[$i]][1])
            {
                $params['preparse']['ignored'][$key[$i]][0] += $params['offset'];
                $params['preparse']['ignored'][$key[$i]][1] += $params['offset'];
            }
        }
        //Only continue if we are preparsing
        if (!$params['config']['preparse'])
        {
            return $params;
        }
        $key = array_keys($params['preparse']['taken']);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //If this reserved range is in this case
            if ($params['open']['position'] < $params['preparse']['taken'][$key[$i]][0] && $params['position'] + strlen($params['open']['node']['close']) > $params['preparse']['taken'][$key[$i]][1])
            {
                //If the node does not transform the case, adjust the range to the removal of the opening string
                if (array_key_exists('transform', $params['open']['node']) && !$params['open']['node']['transform'])
                {
                    $params['preparse']['taken'][$key[$i]][0] += $params['offset'];
                    $params['preparse']['taken'][$key[$i]][1] += $params['offset'];
                }
                //Else, if this case should be taken, remove the range
                elseif ($params['taken'])
                {
                    unset($params['preparse']['taken'][$key[$i]]);
                }
            }
        }
        //If the node transforms the case, this case should be taken, and the case is not empty, reserve the transformed case
        if ((!array_key_exists('transform', $params['open']['node']) || $params['open']['node']['transform']) && $params['taken'] && $params['case'])
        {
            $params['preparse']['taken'][] = array($params['open']['position'], $params['last']);
        }
        return $params;
    }

    public function sort($a, $b)
    {
        return strlen($b) - strlen($a);
    }

    public function stack($params)
    {
        //Add the opening string to the stack
        $params['stack'][] = array
        (
            'node' => $params['nodes'][$params['node']],
            'open' => $params['node'],
            'position' => $params['position']
        );
        //If the skip key is true, skip over everything between this opening string and its closing string
        if (array_key_exists('skip', $params['nodes'][$params['node']]) && $params['nodes'][$params['node']]['skip'])
        {
            $params['skipnode'][] = $params['nodes'][$params['node']]['close'];
        }
        return $params;
    }

    public function strpos($haystack, $needle, $offset = 0, $function = NULL)
    {
        //If a function name was provided, increment the number of times that the function called strpos by 1
        if (isset($function))
        {
            $this->owner->debug['strpos'][$function]['call']++;
        }
        //Find the position insensitively or sensitively based on the configuration
        if ($this->owner->insensitive)
        {
            return stripos($haystack, $needle, $offset);
        }
        else
        {
            return strpos($haystack, $needle, $offset);
        }
    }

    public function transform($params)
    {
        $success = true;
        $key = array_keys($params['preparse']['ignored']);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //If this ignored node is in this case
            if ($params['open']['position'] < $params['preparse']['ignored'][$key[$i]][0] && $params['position'] + strlen($params['open']['node']['close']) > $params['preparse']['ignored'][$key[$i]][1])
            {
                $success = false;
                break;
            }
        }
        //If either this does not contain a ignored node or the node does not transform the case, parse
        if ($success || (array_key_exists('transform', $params['open']['node']) && !$params['open']['node']['transform']))
        {
            $params['case'] = substr($params['return'], $params['open']['position'] + strlen($params['open']['open']), $params['position'] - $params['open']['position'] - strlen($params['open']['open']));
            //If functions are provided
            if (array_key_exists('function', $params['open']['node']))
            {
                $params['suit'] = $this->owner;
                $params['var'] = $params['open']['node']['var'];
                foreach ($params['open']['node']['function'] as $value)
                {
                    //Transform the string in between the opening and closing strings. Note whether or not the function is in a class.
                    if (array_key_exists('class', $value))
                    {
                        $params = $value['class']->$value['function']($params);
                    }
                    else
                    {
                        $params = $value['function']($params);
                    }
                }
            }
            else
            {
                //Replace the opening and closing strings
                $params['case'] = $params['open']['open'] . $params['case'] . $params['open']['node']['close'];
            }
            $params['case'] = strval($params['case']);
            //Replace everything including and between the opening and closing strings with the transformed string
            $params['return'] = substr_replace($params['return'], $params['case'], $params['open']['position'], $params['position'] + strlen($params['open']['node']['close']) - $params['open']['position']);
            $params['last'] = $params['open']['position'] + strlen($params['case']);
            $params = $this->preparse($params);
        }
        //If the node should be ignored, or this contains a ignored node and the node transforms the case
        if ($params['ignore'] || (!$success && (!array_key_exists('transform', $params['open']['node']) || $params['open']['node']['transform'])))
        {
            //If the node should be ignored, reserve the space
            if ($params['ignore'])
            {
                $params['preparse']['ignored'][] = array($params['open']['position'], $params['position'] + strlen($params['open']['node']['close']));
            }
            //If this is an attribute node
            if (array_key_exists('attribute', $params['open']['node']))
            {
                //Put the popped value back
                $params['stack'][] = $params['open'];
                //If the node is a skipping node and it transforms the case, skip
                if (array_key_exists('skip', $params['nodes'][$params['open']['node']['attribute']]) && $params['nodes'][$params['open']['node']['attribute']]['skip'] && (!array_key_exists('transform', $params['nodes'][$params['open']['node']['attribute']]) || $params['nodes'][$params['open']['node']['attribute']]['transform']))
                {
                    $newstack = array
                    (
                        'node' => $params['open']['open'],
                        'nodes' => $params['nodes'],
                        'position' => $params['open']['position'],
                        'skipnode' => array(),
                        'stack' => array()
                    );
                    $newstack = $this->stack($newstack);
                    $params['skipnode'] = array_merge($params['skipnode'], $newstack['skipnode']);
                }
            }
        }
        return $params;
    }
}
?>