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
        'execute' => array
        (
            'parse' => array(),
            'tokens' => array()
        )
    );

    public $log = array();

    public $version = '1.3.4';

    public function close($params, $pop, $mark)
    {
        $string = substr($params['return'], $params['last'], $params['position'] - $params['last']);
        if (!array_key_exists('create', $params['nodes'][$pop['node']]))
        {
            $pop['closed'] = $mark;
            //If the inner string is not empty, add it to the node
            if ($string)
            {
                $pop['contents'][] = $string;
            }
            //Add the node to the tree if necessary
            if ($this->closed($params['tree']))
            {
                $pop2 = array_pop($params['tree']);
                $pop2['contents'][] = $pop;
                $pop = $pop2;
            }
            $params['tree'][] = $pop;
        }
        else
        {
            $append = array
            (
                'create' => $string,
                'node' => $params['nodes'][$pop['node']]['create'],
                'contents' => array()
            );
            $params['tree'][] = $append;
            $params['skipstack'] = $this->skip($params['nodes'][$params['nodes'][$pop['node']]['create']], $params['skipstack']);
        }
        $params['last'] = $params['position'] + strlen($params['string']);
        return $params;
    }

    public function closed($tree)
    {
        //If the tree is not empty and the last item is an array and has not been closed
        return (!empty($tree) && is_array($tree[count($tree) - 1]) && (!array_key_exists('closed', $tree[count($tree) - 1]) || !$tree[count($tree) - 1]['closed']));
    }

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
        if ($skippop === false || $params['string'] == $skippop['close'])
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
                elseif ($this->closed($params['tree']))
                {
                    $pop = array_pop($params['tree']);
                    //If this closing string matches the last node's or it explicitly says to parse a mismatched case
                    if ($params['nodes'][$pop['node']]['close'] == $params['string'] || $params['config']['mismatched'])
                    {
                        $params = $this->close($params, $pop, true);
                    }
                    //Else, put the string back
                    else
                    {
                        if ($this->closed($params['tree']))
                        {
                            $pop2 = array_pop($params['tree']);
                            $pop2['contents'][] = $pop['node'];
                            foreach ($pop['contents'] as $value)
                            {
                                $pop2['contents'][] = $value;
                            }
                            $params['tree'][] = $pop2;
                        }
                        else
                        {
                            $params['tree'][] = $pop['node'];
                            foreach ($pop['contents'] as $value)
                            {
                                $params['tree'][] = $value;
                            }
                        }
                    }
                }
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
                'strings' => $positionstrings
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

    public function functions($params, $function)
    {
        foreach ($function as $value)
        {
            if (!$params['function'])
            {
                break;
            }
            //Transform the string in between the opening and closing strings. Note whether or not the function is in a class
            if (array_key_exists('class', $value))
            {
                $params = $value['class']->$value['function']($params);
            }
            else
            {
                $params = $value['function']($params);
            }
        }
        return $params;
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
                //Add the string in between the last symbol and this to the tree
                $append = substr($params['return'], $params['last'], $params['position'] - $params['last']);
                $params['last'] = $params['position'] + strlen($params['string']);
                //Add the text to the tree if necessary
                if ($this->closed($params['tree']))
                {
                    $pop = array_pop($params['tree']);
                    if ($append)
                    {
                        $pop['contents'][] = $append;
                    }
                    $params['tree'][] = $pop;
                }
                else
                {
                    if ($append)
                    {
                        $params['tree'][] = $append;
                    }
                }
                $append = array
                (
                    'node' => $params['string'],
                    'contents' => array()
                );
                $params['tree'][] = $append;
                $params['skipstack'] = $this->skip($params['nodes'][$params['string']], $params['skipstack']);
            }
        }
        else
        {
            //Put it back
            $params['skipstack'][] = $skippop;
            $skipclose = array($params['nodes'][$params['string']]['close']);
            if (array_key_exists('create', $params['nodes'][$params['string']]))
            {
                $skipclose[] = $params['nodes'][$params['nodes'][$params['string']]['create']]['close'];
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

    public function execute($nodes, $return, $config = array())
    {
        if (!array_key_exists('escape', $config))
        {
            $config['escape'] = '\\';
        }
        if (!array_key_exists('insensitive', $config))
        {
            $config['insensitive'] = true;
        }
        if (!array_key_exists('mismatched', $config))
        {
            $config['mismatched'] = false;
        }
        if (!array_key_exists('unclosed', $config))
        {
            $config['unclosed'] = false;
        }
        $cachekey = md5(md5(serialize($return)) . md5(serialize($nodes)) . md5(serialize($config['insensitive'])));
        //If positions are cached for this case, load them
        if (array_key_exists($cachekey, $this->cache['execute']['tokens']))
        {
            $tokens = $this->cache['execute']['tokens'][$cachekey];
        }
        else
        {
            $tokens = $this->tokens($nodes, $return, $config);
            //Cache the tokens
            $this->cache['execute']['tokens'][$cachekey] = $tokens;
        }
        $cachekey = md5(md5(serialize($return)) . md5(serialize($nodes)) . md5(serialize($config['insensitive'])) . md5(serialize($config['escape'])) . md5(serialize($config['mismatched'])));
        //If a tree is cached for this case, load it
        if (array_key_exists($cachekey, $this->cache['execute']['parse']))
        {
            $parse = $this->cache['execute']['parse'][$cachekey];
        }
        else
        {
            $parse = $this->parse($nodes, $return, $config, $tokens);
            //Cache the tree
            $this->cache['execute']['parse'][$cachekey] = $parse;
        }
        $tree = array
        (
            'contents' => $parse
        );
        $return = $this->walk($nodes, $tree, $config);
        return $return;
    }

    public function parse($nodes, $return, $config, $tokens)
    {
        $params = array
        (
            'config' => $config,
            'last' => 0,
            'nodes' => $nodes,
            'return' => $return,
            'returnoffset' => 0,
            'skipstack' => array(),
            'skipoffset' => 0,
            'temp' => $return,
            'tree' => array()
        );
        $key = array_keys($tokens);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //Adjust position to changes in length
            $params['position'] = $key[$i] + $params['returnoffset'];
            $params['string'] = $tokens[$key[$i]][0];
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
            $function = 'closingstring';
            if ($tokens[$key[$i]][1] == 0)
            {
                $function = 'openingstring';
            }
            $params = $this->$function($params);
            //Adjust the offset
            $params['returnoffset'] = strlen($params['return']) - strlen($params['temp']);
        }
        $string = substr($params['return'], $params['last']);
        //If the ending string is not empty, add it to the tree
        if ($string)
        {
            if ($this->closed($params['tree']))
            {
                $pop = array_pop($params['tree']);
                $params['position'] = strlen($params['return']);
                $params = $this->close($params, $pop, false);
            }
            else
            {
                $params['tree'][] = substr($params['return'], $params['last']);
            }
        }
        return $params['tree'];
    }

    public function positions($params)
    {
        $params['taken'] = array();
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

    public function skip($node, $skipstack)
    {
        //If the skip key is true, skip over everything between this opening string and its closing string
        if (array_key_exists('skip', $node) && $node['skip'])
        {
            $skipstack[] = $node;
        }
        return $skipstack;
    }

    public function sort($a, $b)
    {
        return strlen($b) - strlen($a);
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

    public function tokens($nodes, $return, $config)
    {
        $strings = array();
        $key = array_keys($nodes);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            $strings[$key[$i]] = array($key[$i], 0);
            if (array_key_exists('close', $nodes[$key[$i]]))
            {
                $strings[$nodes[$key[$i]]['close']] = array($nodes[$key[$i]]['close'], 1);
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
            'strings' => $strings
        );
        $tokens = $this->positions($params);
        //Order the positions from smallest to biggest
        ksort($tokens);
        return $tokens;
    }

    public function walk($nodes, $tree, $config)
    {
        $params = array
        (
            'config' => $config,
            'function' => true,
            'node' => $tree['node'],
            'nodes' => $nodes,
            'suit' => $this,
            'tree' => $tree,
            'var' => $nodes[$tree['node']]['var']
        );
        if (array_key_exists('create', $tree))
        {
            $params['create'] = $tree['create'];
        }
        if (array_key_exists('node', $tree) && array_key_exists('treefunctions', $nodes[$tree['node']]))
        {
            $params = $this->functions($params, $nodes[$tree['node']]['treefunctions']);
            $tree = $params['tree'];
        }
        foreach ($tree['contents'] as $key => $value)
        {
            if (is_array($value))
            {
                //If the tag has been closed or it explicitly says to parse unopened strings, parse the recursions with its node
                if ($config['unclosed'] || (array_key_exists('closed', $value) && $value['closed']))
                {
                    $tree['contents'][$key] = $this->walk($nodes, $value, $config);
                }
                //Else, parse it, ignoring the original opening string, with no node
                else
                {
                    $value = array
                    (
                        'contents' => $value['contents']
                    );
                    $tree['contents'][$key] = $value['node'] . $this->walk($nodes, $value, $config);
                }
            }
            if (array_key_exists('node', $tree) && array_key_exists('stringfunctions', $nodes[$tree['node']]))
            {
                $params['case'] = $tree['contents'][$key];
                $params['function'] = true;
                $params = $this->functions($params, $nodes[$tree['node']]['stringfunctions']);
                $tree['contents'][$key] = $params['case'];
            }
        }
        return implode('', $tree['contents']);
    }
}
?>