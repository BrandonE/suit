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
class SUIT
{
    public $cache = array
    (
        'escape' => array(),
        'explodeunescape' => array(),
        'parse' => array()
    );

    public $debug = array();

    public $filepath = '';

    public $offset = 0;

    public $version = '1.3.4';

    public function closingstring($params)
    {
        if (!empty($params['skipstack']))
        {
            if (array_key_exists('skipescape', $params['skipstack'][count($params['skipstack']) - $params['skipoffset'] - 1]))
            {
                $escaping = $params['skipstack'][0]['skipescape'];
            }
            else
            {
                $escaping = false;
            }
            $skippop = array_pop($params['skipstack']);
        }
        else
        {
            $escaping = true;
            $skippop = false;
        }
        //If a value was not popped or the closing string for this node matches it
        if ($skippop === false || $params['nodes'][$params['node']]['close'] == $skippop['close'])
        {
            //If it explictly says to escape
            if ($escaping)
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
                elseif (!empty($params['openingstack']))
                {
                    $params['open'] = array_pop($params['openingstack']);
                    $params['case'] = substr($params['return'], $params['open']['position'] + strlen($params['open']['open']), $params['position'] - $params['open']['position'] - strlen($params['open']['open']));
                    //If this closing string matches the last node's or it explicitly says to parse a malformed template
                    if ($params['open']['node']['close'] == $params['nodes'][$params['node']]['close'] || $params['config']['malformed'])
                    {
                        $params = $this->transform($params);
                    }
                    else
                    {
                        $params['last'] = $params['position'] + strlen($params['nodes'][$params['node']]['close']);
                        $params = $this->ranges($params);
                    }
                }
                else
                {
                    if (!$params['config']['malformed'])
                    {
                        $params['preparse']['taken'][] = array($params['position'], $params['position'] + strlen($params['nodes'][$params['node']]['close']));
                    }
                    else
                    {
                        $params['open'] = array
                        (
                            'node' => $params['nodes'][$params['node']],
                            'open' => '',
                            'position' => 0
                        );
                        $params['case'] = substr($params['return'], $params['open']['position'] + strlen($params['open']['open']), $params['position'] - $params['open']['position'] - strlen($params['open']['open']));
                        $params = $this->transform($params);
                    }
                }
            }
            //Else, reserve the range
            else
            {
                $params['preparse']['taken'][] = array($params['position'], $params['position'] + strlen($params['nodes'][$params['node']]['close']));
            }
        }
        //Else, put the popped value back
        else
        {
            $params['skipstack'][] = $skippop;
        }
        return $params;
    }

    public function escape($strings, $return, $escapestring = '\\', $insensitive = true)
    {
        $search = array();
        $replace = array();
        //Prepare to escape every string
        foreach ($strings as $value)
        {
            $search[] = $value;
            $replace[] = $escapestring . $value;
        }
        $cachekey = md5(md5(serialize($return)) . md5(serialize($strings)));
        //If positions are cached for this case, load them
        if (array_key_exists($cachekey, $this->cache['escape']))
        {
            $pos = $this->cache['escape'][$cachekey];
        }
        else
        {
            $positionstrings = array();
            foreach ($strings as $value)
            {
                $positionstrings[$value] = NULL;
            }
            //Order the strings by the length, descending
            uksort($positionstrings, array('SUIT', 'sort'));
            $params = array
            (
                'insensitive' => $insensitive,
                'pos' => array(),
                'repeated' => array(),
                'return' => $return,
                'strings' => $positionstrings,
                'taken' => array()
            );
            $pos = $this->positions($params);
            //On top of the strings to be escaped, the last position in the string should be checked for escape strings
            $pos[strlen($return)] = NULL;
            //Order the positions from smallest to biggest
            ksort($pos);
            //Cache the positions
            $this->cache['escape'][$cachekey] = $pos;
        }
        $offset = 0;
        $key = array_keys($pos);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //Adjust position to changes in length
            $position = $key[$i] + $offset;
            $count = 0;
            //If the escape string is not empty
            if ($escapestring)
            {
                //Count how many escape characters are directly to the left of this position
                while (abs($start = $key[$i] - $count - strlen($escapestring)) == $start && substr($return, $start, strlen($escapestring)) == $escapestring)
                {
                    $count += strlen($escapestring);
                }
                //Determine how many escape strings are directly to the left of this position
                $count = $count / strlen($escapestring);
            }
            //Replace the escape strings with two escape strings, escaping each of them
            $return = substr_replace($return, str_repeat($escapestring, $count * 2), $key[$i] - ($count * strlen($escapestring)), strlen($escapestring) * $count);
            //Adjust the offset
            $offset += $count * strlen($escapestring);
        }
        //Escape every string
        return str_replace($search, $replace, $return);
    }

    public function explodeunescape($explode, $string, $escapestring = '\\', $insensitive = true)
    {
        $return = array();
        $cachekey = md5(md5(serialize($string)) . md5(serialize($explode)));
        //If positions are cached for this case, load them
        if (array_key_exists($cachekey, $this->cache['explodeunescape']))
        {
            $pos = $this->cache['explodeunescape'][$cachekey];
        }
        else
        {
            $pos = array();
            $position = -1;
            //Find the next position of the string
            while (($position = $this->strpos($string, $explode, $position + 1, $insensitive)) !== false)
            {
                $pos[] = $position;
            }
            //On top of the explode string to be escaped, the last position in the string should be checked for escape strings
            $pos[] = strlen($string);
            //Cache the positions
            $this->cache['explodeunescape'][$cachekey] = $pos;
        }
        $offset = 0;
        $last = 0;
        $temp = $string;
        foreach ($pos as $value)
        {
            //Adjust position to changes in length
            $value += $offset;
            $count = 0;
            //If the escape string is not empty
            if ($escapestring)
            {
                //Count how many escape characters are directly to the left of this position
                while (abs($start = $value - $count - strlen($escapestring)) == $start && substr($string, $start, strlen($escapestring)) == $escapestring)
                {
                    $count += strlen($escapestring);
                }
                //Determine how many escape strings are directly to the left of this position
                $count = $count / strlen($escapestring);
            }
            $condition = $count % 2;
            //If the number of escape strings directly to the left of this position are odd, (x + 1) / 2 of them should be removed
            if ($condition)
            {
                $count++;
            }
            //If there are escape strings directly to the left of this position
            if ($count)
            {
                //Remove the decided number of escape strings
                $string = substr_replace($string, '', $value - (($count / 2) * strlen($escapestring)), ($count / 2) * strlen($escapestring));
                //Adjust the value
                $value -= ($count / 2) * strlen($escapestring);
            }
            //If the number of escape strings directly to the left of this position are even
            if (!$condition)
            {
                //This separator is not overlooked, so append the accumulated value to the return array
                $return[] = substr($string, $last, $value - $last);
                //Make sure not to include anything we appended in a future value
                $last = $value + strlen($explode);
            }
            //Adjust the offset
            $offset = strlen($string) - strlen($temp);
        }
        return $return;
    }

    public function openingstring($params)
    {
        if (!empty($params['skipstack']))
        {
            if (array_key_exists('skipescape', $params['skipstack'][count($params['skipstack']) - $params['skipoffset'] - 1]))
            {
                $escaping = $params['skipstack'][0]['skipescape'];
            }
            else
            {
                $escaping = false;
            }
            $skippop = array_pop($params['skipstack']);
        }
        else
        {
            $escaping = true;
            $skippop = false;
        }
        //If a value was not popped from skipstack
        if ($skippop === false)
        {
            $params['position'] = $params['unescape']['position'];
            $params['return'] = $params['unescape']['string'];
            //If this position should not be overlooked
            if (!$params['unescape']['condition'])
            {
                $result = $this->stack($params['nodes'][$params['node']], $params['node'], $params['position']);
                $params['openingstack'] = array_merge($params['openingstack'], $result['openingstack']);
                $params['skipstack'] = array_merge($params['skipstack'], $result['skipstack']);
            }
            //Else, reserve the range
            else
            {
                $params['preparse']['taken'][] = array($params['position'], $params['position'] + strlen($params['node']));
            }
        }
        else
        {
            //Put it back
            $params['skipstack'][] = $skippop;
            $skipclose = array($params['nodes'][$params['node']]['close']);
            if (array_key_exists('attribute', $params['nodes'][$params['node']]))
            {
                $skipclose[] = $params['nodes'][$params['nodes'][$params['node']]['attribute']]['close'];
            }
            //If the closing string for this node matches it
            if (in_array($skippop['close'], $skipclose))
            {
                //If it explictly says to escape
                if ($escaping)
                {
                    $params['position'] = $params['unescape']['position'];
                    $params['return'] = $params['unescape']['string'];
                }
                //If this position should not be overlooked
                if (!$params['unescape']['condition'])
                {
                    //Account for it
                    $params['skipstack'][] = $skippop;
                    $params['skipoffset']++;
                }
            }
        }
        return $params;
    }

    public function parse($nodes, $return, $config = array())
    {
        if (!array_key_exists('escape', $config))
        {
            $config['escape'] = '\\';
        }
        if (!array_key_exists('insensitive', $config))
        {
            $config['insensitive'] = true;
        }
        if (!array_key_exists('malformed', $config))
        {
            $config['malformed'] = false;
        }
        if (!array_key_exists('preparse', $config))
        {
            $config['preparse'] = false;
        }
        if (!array_key_exists('taken', $config))
        {
            $config['taken'] = array();
        }
        $cachekey = md5(md5(serialize($return)) . md5(serialize($nodes)) . md5(serialize($config['taken'])));
        //If positions are cached for this case, load them
        if (array_key_exists($cachekey, $this->cache['parse']))
        {
            $pos = $this->cache['parse'][$cachekey];
        }
        else
        {
            $strings = array();
            $key = array_keys($nodes);
            $size = count($key);
            for ($i = 0; $i < $size; $i++)
            {
                $strings[$key[$i]] = array($key[$i], 0);
                if (array_key_exists('close', $nodes[$key[$i]]))
                {
                    $strings[$nodes[$key[$i]]['close']] = array($key[$i], 1);
                }
            }
            //Order the strings by the length, descending
            uksort($strings, array('SUIT', 'sort'));
            $params = array
            (
                'insensitive' => $config['insensitive'],
                'pos' => array(),
                'repeated' => array(),
                'return' => $return,
                'strings' => $strings,
                'taken' => $config['taken']
            );
            $pos = $this->positions($params);
            //Order the positions from smallest to biggest
            ksort($pos);
            //Cache the positions
            $this->cache['parse'][$cachekey] = $pos;
        }
        $inspection = debug_backtrace();
        $this->debug[] = array
        (
            'file' => $inspection[0]['file'],
            'line' => $inspection[0]['line'],
            'steps' => array
            (
                array
                (
                    'return' => $return
                )
            )
        );
        $params = array
        (
            'config' => $config,
            'last' => 0,
            'nodes' => $nodes,
            'openingstack' => array(),
            'preparse' => array
            (
                'ignored' => array(),
                'taken' => array()
            ),
            'return' => $return,
            'returnoffset' => 0,
            'skipstack' => array(),
            'skipoffset' => 0,
            'temp' => $return
        );
        $key = array_keys($pos);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //Adjust position to changes in length
            $params['position'] = $key[$i] + $params['returnoffset'];
            $params = $this->step($params, $pos[$key[$i]]);
            if (!$params['parse'])
            {
                break;
            }
        }
        $params = $this->remaining($params);
        $this->tree();
        if (!$params['config']['preparse'])
        {
            $return = $params['return'];
        }
        else
        {
            $return = array
            (
                'ignored' => $params['preparse']['ignored'],
                'return' => $params['return'],
                'taken' => $params['preparse']['taken']
            );
        }
        return $return;
    }

    public function positions($params)
    {
        $params['key'] = array_keys($params['strings']);
        $size = count($params['key']);
        for ($params['i'] = 0; $params['i'] < $size; $params['i']++)
        {
            //If the string has not already been used
            if (!in_array($params['key'][$params['i']], $params['repeated']))
            {
                $params = $this->positionsloop($params);
                //Make sure this string is not repeated
                $params['repeated'][] = $params['key'][$params['i']];
            }
        }
        return $params['pos'];
    }

    public function positionsloop($params)
    {
        $position = -1;
        //Find the next position of the string
        while (($position = $this->strpos($params['return'], $params['key'][$params['i']], $position + 1, $params['insensitive'])) !== false)
        {
            $success = true;
            foreach ($params['taken'] as $value)
            {
                //If this string instance is in this reserved range
                if (($position >= $value[0] && $position < $value[1]) || ($position + strlen($params['key'][$params['i']]) > $value[0] && $position + strlen($params['key'][$params['i']]) < $value[1]))
                {
                    $success = false;
                    break;
                }
            }
            //If this string instance is not in any reserved range
            if ($success)
            {
                //Add the position
                $params['pos'][$position] = $params['strings'][$params['key'][$params['i']]];
                //Reserve all positions taken up by this string instance
                $params['taken'][] = array($position, $position + strlen($params['key'][$params['i']]));
            }
        }
        return $params;
    }

    public function ranges($params)
    {
        $key = array_keys($params['preparse']['ignored']);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //If this reserved range is in this case, adjust the range to the removal of the opening string
            if ($params['open']['position'] < $params['preparse']['ignored'][$key[$i]][0] && $params['position'] + strlen($params['nodes'][$params['node']]['close']) > $params['preparse']['ignored'][$key[$i]][1])
            {
                $params['preparse']['ignored'][$key[$i]][0] += $params['offset'];
                $params['preparse']['ignored'][$key[$i]][1] += $params['offset'];
            }
        }
        $key = array_keys($params['preparse']['taken']);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //If this reserved range is in this case
            if ($params['open']['position'] < $params['preparse']['taken'][$key[$i]][0] && $params['position'] + strlen($params['nodes'][$params['node']]['close']) > $params['preparse']['taken'][$key[$i]][1])
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

    public function remaining($params)
    {
        foreach($params['openingstack'] as $value)
        {
            if (!$params['config']['malformed'])
            {
                $params['preparse']['taken'][] = array($value['position'], $value['position'] + strlen($value['open']));
            }
            else
            {
                $params['position'] = strlen($params['return']);
                $params = $this->step($params, array($value['open'], 1));
            }
        }
        return $params;
    }

    public function sort($a, $b)
    {
        return strlen($b) - strlen($a);
    }

    public function stack($node, $opening, $position)
    {
        //Add the opening string to the stack
        $openingstack = array
        (
            array
            (
                'node' => $node,
                'open' => $opening,
                'position' => $position
            )
        );
        $skipstack = array();
        //If the skip key is true, skip over everything between this opening string and its closing string
        if (array_key_exists('skip', $node) && $node['skip'])
        {
            $skipstack[] = $node;
        }
        return array
        (
            'openingstack' => $openingstack,
            'skipstack' => $skipstack
        );
    }

    public function step($params, $value)
    {
        $params['function'] = true;
        $params['node'] = $value[0];
        $params['offset'] = 0;
        $params['parse'] = true;
        $params['taken'] = true;
        $position = $params['position'];
        $string = $params['return'];
        $count = 0;
        //If the escape string is not empty
        if ($params['config']['escape'])
        {
            //Count how many escape characters are directly to the left of this position
            while (abs($start = $position - $count - strlen($params['config']['escape'])) == $start && substr($string, $start, strlen($params['config']['escape'])) == $params['config']['escape'])
            {
                $count += strlen($params['config']['escape']);
            }
            //Determine how many escape strings are directly to the left of this position
            $count = $count / strlen($params['config']['escape']);
        }
        //If the number of escape strings directly to the left of this position are odd, the position should be overlooked
        $condition = $count % 2;
        //If the condition is true, (x + 1) / 2 of them should be removed
        if ($condition)
        {
            $count++;
        }
        //Adjust the position
        $position -= strlen($params['config']['escape']) * ($count / 2);
        //Remove the decided number of escape strings
        $string = substr_replace($string, '', $position, strlen($params['config']['escape']) * ($count / 2));
        $params['unescape'] = array
        (
            'condition' => $condition,
            'position' => $position,
            'string' => $string
        );
        if ($value[1] == 0)
        {
            $params = $this->openingstring($params);
        }
        else
        {
            $pop = array_pop($this->debug);
            $pop['steps'][] = array
            (
                'node' => $params['node'],
                'recurse' => array()
            );
            $this->debug[] = $pop;
            $params = $this->closingstring($params);
            $pop = array_pop($this->debug);
            $pop2 = array_pop($pop['steps']);
            $pop2['ignored'] = $params['preparse']['ignored'];
            $pop2['return'] = $params['return'];
            $pop2['taken'] = $params['preparse']['taken'];
            $pop['steps'][] = $pop2;
            $this->debug[] = $pop;
        }
        //Adjust the offset
        $params['returnoffset'] = strlen($params['return']) - strlen($params['temp']);
        return $params;
    }

    public function strpos($haystack, $needle, $offset, $insensitive)
    {
        //Find the position insensitively or sensitively based on the configuration
        if ($insensitive)
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
        //If functions are provided
        if (array_key_exists('function', $params['open']['node']))
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
            //If either this does not contain a ignored node or the node does not transform the case
            if ($success || (array_key_exists('transform', $params['open']['node']) && !$params['open']['node']['transform']))
            {
                $params['suit'] = &$this;
                $params['var'] = $params['open']['node']['var'];
                foreach ($params['open']['node']['function'] as $value)
                {
                    //Transform the string in between the opening and closing strings. Note whether or not the function is in a class
                    if (array_key_exists('class', $value))
                    {
                        $params = $value['class']->$value['function']($params);
                    }
                    else
                    {
                        $params = $value['function']($params);
                    }
                    if (!$params['function'])
                    {
                        break;
                    }
                }
                $params['case'] = strval($params['case']);
                //Replace everything including and between the opening and closing strings with the transformed string
                $params['return'] = substr_replace($params['return'], $params['case'], $params['open']['position'], $params['position'] + strlen($params['open']['node']['close']) - $params['open']['position']);
                $params['last'] = $params['open']['position'] + strlen($params['case']);
                $params = $this->ranges($params);
            }
        }
        return $params;
    }

    public function tree()
    {
        //Log the function call
        $push = true;
        $pop = array_pop($this->debug);
        if (!empty($this->debug))
        {
            $pop2 = array_pop($this->debug);
            if (!empty($pop2['steps']))
            {
                $pop3 = array_pop($pop2['steps']);
                if (!array_key_exists('return', $pop3))
                {
                    $pop3['recurse'][] = $pop;
                    $push = false;
                }
                $pop2['steps'][] = $pop3;
            }
            $this->debug[] = $pop2;
        }
        if ($push)
        {
            $this->debug[] = $pop;
        }
    }
}
?>