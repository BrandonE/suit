<?php
/**
**@This program is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@This program is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class SUIT
{
    public $cache = array
    (
        'escape' => array(),
        'execute' => array
        (
            'parse' => array(),
            'tokens' => array()
        ),
        'explodeunescape' => array()
    );

    public $log = array
    (
        'id' => 0,
        'parallel' => array(),
        'tree' => array()
    );

    public $version = '2.0.0';

    public function cacherules($rules, $keys)
    {
        $cachedrules = array();
        foreach ($rules as $key => $value)
        {
            $cachedkeys = array
            (
                'key' => $key
            );
            foreach ($keys as $value2)
            {
                if (array_key_exists($value2, $value))
                {
                    $cachedkeys[$value2] = $value[$value2];
                }
            }
            $cachedrules[$key] = $cachedkeys;
        }
        return $cachedrules;
    }

    public function close($params, $pop, $mark)
    {
        $append = substr($params['string'], $params['last'], $params['position'] - $params['last']);
        if (!array_key_exists('create', $params['rules'][$pop['rule']]))
        {
            $pop['closed'] = $mark;
            //If the inner string is not empty, add it to the rule
            if ($append)
            {
                $pop['contents'][] = $append;
            }
            //Add the rule to the tree
            if ($this->notclosed($params['tree']))
            {
                $pop2 = array_pop($params['tree']);
                $pop2['contents'][] = $pop;
                $pop = $pop2;
            }
            $params['tree'][] = $pop;
            unset($params['flat'][$params['rule']]);
        }
        else
        {
            $append = array
            (
                'case' => '',
                'contents' => array(),
                'create' => $append,
                'rule' => $params['rules'][$pop['rule']]['create'],
                'parallel' => array()
            );
            $params['tree'][] = $append;
            $params['skipstack'] = $this->skip($params['rules'][$params['rules'][$pop['rule']]['create']], $params['skipstack']);
        }
        $params['last'] = $params['position'] + strlen($params['rule']);
        return $params;
    }

    public function closingstring($params)
    {
        //If a value was not popped or the closing string for this rule matches it
        if ($params['skip'] === false || $params['rule'] == $params['skip']['close'])
        {
            //If it explictly says to escape
            if ($params['escaping'])
            {
                $params['position'] = $params['unescape']['position'];
                $params['string'] = $params['unescape']['string'];
            }
            //If this position should not be overlooked
            if (!$params['unescape']['condition'])
            {
                //If there is an offset, decrement it
                if ($params['skipoffset'])
                {
                    $params['skipoffset']--;
                }
                elseif ($this->notclosed($params['tree']))
                {
                    $pop = array_pop($params['tree']);
                    //If this closing string matches the last rule's or it explicitly says to execute a mismatched case
                    if ($params['rules'][$pop['rule']]['close'] == $params['rule'] || $params['config']['mismatched'])
                    {
                        $params = $this->close($params, $pop, true);
                    }
                    //Else, put the string back
                    else
                    {
                        if ($this->notclosed($params['tree']))
                        {
                            $pop2 = array_pop($params['tree']);
                            $pop2['contents'][] = $pop['rule'];
                            foreach ($pop['contents'] as $value)
                            {
                                $pop2['contents'][] = $value;
                            }
                            $params['tree'][] = $pop2;
                        }
                        else
                        {
                            $params['tree'][] = $pop['rule'];
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
            $params['skipstack'][] = $params['skip'];
        }
        return $params;
    }

    public function escape($strings, $string, $escapestring = '\\', $insensitive = true)
    {
        $cachekey = md5(json_encode(array($string, $strings)));
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
                'string' => $string,
                'strings' => $positionstrings
            );
            $pos = $this->positions($params);
            //On top of the strings to be escaped, the last position in the string should be checked for escape strings
            $pos[strlen($string)] = NULL;
            //Order the positions from smallest to biggest
            ksort($pos);
            //Cache the positions
            $this->cache['escape'][$cachekey] = $pos;
        }
        $temp = $string;
        $key = array_keys($pos);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //Adjust position to changes in length
            $position = $key[$i] + strlen($string) - strlen($temp);
            $count = 0;
            //If the escape string is not empty
            if ($escapestring)
            {
                //Count how many escape characters are directly to the left of this position
                while (abs($start = $position - $count - strlen($escapestring)) == $start && substr($string, $start, strlen($escapestring)) == $escapestring)
                {
                    $count += strlen($escapestring);
                }
                //Determine how many escape strings are directly to the left of this position
                $count = $count / strlen($escapestring);
            }
            //If this is not the final position, add an additional escape string
            $plus = 0;
            if ($i != $size - 1)
            {
                $plus = 1;
            }
            //Replace the escape strings with two escape strings, escaping each of them
            $string = substr_replace($string, str_repeat($escapestring, ($count * 2) + $plus), $position - ($count * strlen($escapestring)), $count * strlen($escapestring));
        }
        return $string;
    }

    public function execute($rules, $string, $config = array())
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
        $cachekey = md5(json_encode(array($string, $this->cacherules($rules, array('close')), $config['insensitive'])));
        //If positions are cached for this case, load them
        if (array_key_exists($cachekey, $this->cache['execute']['tokens']))
        {
            $pos = $this->cache['execute']['tokens'][$cachekey];
        }
        else
        {
            $pos = $this->tokens($rules, $string, $config);
            //Cache the positions
            $this->cache['execute']['tokens'][$cachekey] = $pos;
        }
        $cachekey = md5(json_encode(array($string, $this->cacherules($rules, array('close', 'create', 'skip')), $config['insensitive'], $config['escape'], $config['mismatched'])));
        //If a tree is cached for this case, load it
        if (array_key_exists($cachekey, $this->cache['execute']['parse']))
        {
            $tree = $this->cache['execute']['parse'][$cachekey];
        }
        else
        {
            $tree = array
            (
                'case' => '',
                'contents' => $this->parse($rules, $string, $config, $pos),
                'parallel' => array()
            );
            if (array_key_exists('', $rules))
            {
                $tree['rule'] = '';
            }
            //Cache the tree
            $this->cache['execute']['parse'][$cachekey] = $tree;
        }
        //If the parallel array is not empty, mark that this call is running next to it
        if (!empty($this->log['parallel']))
        {
            $this->log['parallel'][count($this->log['parallel']) - 1][] = $this->log['id'];
        }
        $result = $this->walk($rules, $tree, $config);
        $result['tree']['original'] = $string;
        $this->log['tree'][] = $result['tree'];
        return $result['tree']['case'];
    }

    public function explodeunescape($explode, $string, $escapestring = '\\', $insensitive = true)
    {
        $array = array();
        $cachekey = md5(json_encode(array($string, $explode)));
        //If positions are cached for this case, load them
        if (array_key_exists($cachekey, $this->cache['explodeunescape']))
        {
            $pos = $this->cache['explodeunescape'][$cachekey];
        }
        else
        {
            $pos = array();
            if ($explode)
            {
                $function = 'strpos';
                if ($insensitive)
                {
                    $function = 'stripos';
                }
                $position = -1;
                //Find the next position of the string
                while (($position = $function($string, $explode, $position + 1)) !== false)
                {
                    $pos[] = $position;
                }
                //On top of the explode string to be escaped, the last position in the string should be checked for escape strings
                $pos[] = strlen($string);
                //Cache the positions
                $this->cache['explodeunescape'][$cachekey] = $pos;
            }
        }
        $last = 0;
        $temp = $string;
        foreach ($pos as $value)
        {
            //Adjust position to changes in length
            $value += strlen($string) - strlen($temp);
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
                $array[] = substr($string, $last, $value - $last);
                //Make sure not to include anything we appended in a future value
                $last = $value + strlen($explode);
            }
        }
        return $array;
    }

    public function functions($params, $function)
    {
        foreach ($function as $value)
        {
            //Note whether or not the function is in a class
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
        return $params;
    }

    public function notclosed($tree)
    {
        //If the tree is not empty and the last item is an array and has not been closed
        return (!empty($tree) && is_array($tree[count($tree) - 1]) && (!array_key_exists('closed', $tree[count($tree) - 1]) || !$tree[count($tree) - 1]['closed']));
    }

    public function openingstring($params)
    {
        //If a value was not popped from skipstack
        if ($params['skip'] === false)
        {
            $params['position'] = $params['unescape']['position'];
            $params['string'] = $params['unescape']['string'];
            //If this position should not be overlooked
            if (!$params['unescape']['condition'])
            {
                //If the inner string is not empty, add it to the tree
                $append = substr($params['string'], $params['last'], $params['position'] - $params['last']);
                $params['last'] = $params['position'] + strlen($params['rule']);
                //Add the text to the tree if necessary
                if ($this->notclosed($params['tree']))
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
                //Add the rule to the tree
                $append = array
                (
                    'case' => '',
                    'contents' => array(),
                    'rule' => $params['rule'],
                    'parallel' => array()
                );
                $params['tree'][] = $append;
                $params['skipstack'] = $this->skip($params['rules'][$params['rule']], $params['skipstack']);
                $params['flat'][$params['rule']] = NULL;
            }
        }
        else
        {
            //Put it back
            $params['skipstack'][] = $params['skip'];
            $skipclose = array($params['rules'][$params['rule']]['close']);
            if (array_key_exists('create', $params['rules'][$params['rule']]))
            {
                $skipclose[] = $params['rules'][$params['rules'][$params['rule']]['create']]['close'];
            }
            //If the closing string for this rule matches it
            if (in_array($params['skip']['close'], $skipclose))
            {
                //If it explictly says to escape
                if ($params['escaping'])
                {
                    $params['position'] = $params['unescape']['position'];
                    $params['string'] = $params['unescape']['string'];
                }
                //If this position should not be overlooked
                if (!$params['unescape']['condition'])
                {
                    //Account for it
                    $params['skipstack'][] = $params['skip'];
                    $params['skipoffset']++;
                }
            }
        }
        return $params;
    }

    public function parse($rules, $string, $config, $pos)
    {
        $params = array
        (
            'config' => $config,
            'flat' => array(),
            'last' => 0,
            'rules' => $rules,
            'skipstack' => array(),
            'skipoffset' => 0,
            'string' => $string,
            'temp' => $string,
            'tree' => array()
        );
        $key = array_keys($pos);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //Adjust position to changes in length
            $params['rule'] = $pos[$key[$i]]['rule'];
            $params['position'] = $key[$i] + strlen($params['string']) - strlen($params['temp']);
            $params['unescape'] = array
            (
                'position' => $params['position'],
                'string' => $params['string']
            );
            $count = 0;
            //If the escape string is not empty
            if ($params['config']['escape'])
            {
                //Count how many escape characters are directly to the left of this position
                while (abs($start = $params['unescape']['position'] - $count - strlen($params['config']['escape'])) == $start && substr($params['unescape']['string'], $start, strlen($params['config']['escape'])) == $params['config']['escape'])
                {
                    $count += strlen($params['config']['escape']);
                }
                //Determine how many escape strings are directly to the left of this position
                $count = $count / strlen($params['config']['escape']);
            }
            //If the number of escape strings directly to the left of this position are odd, the position should be overlooked
            $params['unescape']['condition'] = $count % 2;
            //If the condition is true, (x + 1) / 2 of them should be removed
            if ($params['unescape']['condition'])
            {
                $count++;
            }
            //Adjust the position
            $params['unescape']['position'] -= strlen($params['config']['escape']) * ($count / 2);
            //Remove the decided number of escape strings
            $params['unescape']['string'] = substr_replace($params['unescape']['string'], '', $params['unescape']['position'], strlen($params['config']['escape']) * ($count / 2));
            $params['escaping'] = true;
            $params['skip'] = false;
            if (!empty($params['skipstack']))
            {
                $params['escaping'] = false;
                if (array_key_exists('skipescape', $params['skipstack'][count($params['skipstack']) - $params['skipoffset'] - 1]))
                {
                    $params['escaping'] = $params['skipstack'][0]['skipescape'];
                }
                $params['skip'] = array_pop($params['skipstack']);
            }
            //Run the appropriate function for the string
            $function = 'openingstring';
            if ($pos[$key[$i]]['type'] == 'close' || ($pos[$key[$i]]['type'] == 'flat' && array_key_exists($params['rule'], $params['flat'])))
            {
                $function = 'closingstring';
            }
            $params = $this->$function($params);
        }
        $string = substr($params['string'], $params['last']);
        //If the ending string is not empty, add it to the tree
        if ($string)
        {
            if ($this->notclosed($params['tree']))
            {
                $pop = array_pop($params['tree']);
                $params['position'] = strlen($params['string']);
                $params = $this->close($params, $pop, false);
            }
            else
            {
                $params['tree'][] = $string;
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
        if (!$params['key'][$params['i']])
        {
            return $params;
        }
        $function = 'strpos';
        if ($params['insensitive'])
        {
            $function = 'stripos';
        }
        $position = -1;
        //Find the next position of the string
        while (($position = $function($params['string'], $params['key'][$params['i']], $position + 1)) !== false)
        {
            $success = true;
            foreach ($params['taken'] as $value)
            {
                //If this string instance is in this reserved range
                if (($position >= $value['start'] && $position < $value['end']) || ($position + strlen($params['key'][$params['i']]) > $value['start'] && $position + strlen($params['key'][$params['i']]) < $value['end']))
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
                $params['taken'][] = array
                (
                    'start' => $position,
                    'end' => $position + strlen($params['key'][$params['i']])
                );
            }
        }
        return $params;
    }

    public function skip($rule, $skipstack)
    {
        //If the skip key is true, skip over everything between this opening string and its closing string
        if (array_key_exists('skip', $rule) && $rule['skip'])
        {
            $skipstack[] = $rule;
        }
        return $skipstack;
    }

    public function sort($a, $b)
    {
        return strlen($b) - strlen($a);
    }

    public function tokens($rules, $string, $config)
    {
        $strings = array();
        $key = array_keys($rules);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            if (array_key_exists('close', $rules[$key[$i]]) && $key[$i] == $rules[$key[$i]]['close'])
            {
                $strings[$key[$i]] = array
                (
                    'rule' => $key[$i],
                    'type' => 'flat'
                );
            }
            else
            {
                $strings[$key[$i]] = array
                (
                    'rule' => $key[$i],
                    'type' => 'open'
                );
                if (array_key_exists('close', $rules[$key[$i]]))
                {
                    $strings[$rules[$key[$i]]['close']] = array
                    (
                        'rule' => $rules[$key[$i]]['close'],
                        'type' => 'close'
                    );
                }
            }
        }
        //Order the strings by the length, descending
        uksort($strings, array('SUIT', 'sort'));
        $params = array
        (
            'insensitive' => $config['insensitive'],
            'pos' => array(),
            'repeated' => array(),
            'string' => $string,
            'strings' => $strings
        );
        $executetokens = $this->positions($params);
        //Order the positions from smallest to biggest
        ksort($executetokens);
        return $executetokens;
    }

    public function walk($rules, $tree, $config)
    {
        $params = array
        (
            'config' => $config,
            'function' => true,
            'rules' => $rules,
            'returnvar' => NULL,
            'returnedvar' => NULL,
            'returnfunctions' => array(),
            'suit' => $this,
            'tree' => $tree,
            'walk' => true
        );
        $params['tree']['id'] = $this->log['id'];
        $this->log['id']++;
        if (array_key_exists('rule', $params['tree']) && array_key_exists('var', $params['rules'][$params['tree']['rule']]))
        {
            $params['var'] = $params['rules'][$params['tree']['rule']]['var'];
        }
        if (array_key_exists('create', $params['tree']))
        {
            $params['create'] = $params['tree']['create'];
        }
        if (array_key_exists('rule', $params['tree']) && array_key_exists('prewalk', $params['rules'][$params['tree']['rule']]))
        {
            $this->log['parallel'][] = array();
            //Run the functions meant to be executed before walking through the tree
            $params = $this->functions($params, $params['rules'][$params['tree']['rule']]['prewalk']);
            if (!empty($this->log['parallel']))
            {
                $params['tree']['parallel'] = array_merge($params['tree']['parallel'], array_pop($this->log['parallel']));
            }
        }
        foreach ($params['tree']['contents'] as $key => $value)
        {
            if (!$params['walk'])
            {
                break;
            }
            if (is_array($params['tree']['contents'][$key]))
            {
                $params = $this->walkarray($params, $key);
            }
            else
            {
                $params['tree']['case'] .= $params['tree']['contents'][$key];
            }
        }
        if (array_key_exists('rule', $params['tree']) && array_key_exists('postwalk', $params['rules'][$params['tree']['rule']]))
        {
            $params['function'] = true;
            $this->log['parallel'][] = array();
            //Transform the case with the specified functions
            $params = $this->functions($params, $params['rules'][$params['tree']['rule']]['postwalk']);
            if (!empty($this->log['parallel']))
            {
                $params['tree']['parallel'] = array_merge($params['tree']['parallel'], array_pop($this->log['parallel']));
            }
        }
        $params['tree']['case'] = strval($params['tree']['case']);
        return array
        (
            'functions' => $params['returnfunctions'],
            'tree' => $params['tree'],
            'var' => $params['returnvar']
        );
    }

    public function walkarray($params, $key)
    {
        //If the tag has been closed or it explicitly says to execute unopened strings, walk through the contents with its rule
        if ($params['config']['unclosed'] || (array_key_exists('closed', $params['tree']['contents'][$key]) && $params['tree']['contents'][$key]['closed']))
        {
            $result = $this->walk($params['rules'], $params['tree']['contents'][$key], $params['config']);
            $params['tree']['contents'][$key] = $result['tree'];
            $params['tree']['case'] .= $result['tree']['case'];
            //Run the functions that have been returned
            $params['key'] = $key;
            $params['returnedvar'] = $result['var'];
            $this->log['parallel'][] = array();
            $params = $this->functions($params, $result['functions']);
            if (!empty($this->log['parallel']))
            {
                $params['tree']['parallel'] = array_merge($params['tree']['parallel'], array_pop($this->log['parallel']));
            }
            unset($params['key']);
            unset($params['returnedvar']);
        }
        //Else, execute it, ignoring the original opening string, with no rule
        else
        {
            $tree = array
            (
                'case' => '',
                'contents' => $params['tree']['contents'][$key]['contents'],
                'parallel' => array()
            );
            $result = $this->walk($params['rules'], $tree, $params['config']);
            if (array_key_exists('rule', $params['tree']['contents'][$key]))
            {
                $params['tree']['case'] .= $params['tree']['contents'][$key]['rule'];
            }
            $params['tree']['case'] .= $result['tree']['case'];
        }
        return $params;
    }
}
?>