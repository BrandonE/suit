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
        'hash' => array(),
        'parse' => array(),
        'tokens' => array()
    );

    public $log = array
    (
        'hash' => array(),
        'entries' => array()
    );

    public $version = '2.0.0';

    public function close($append, $pop, $rules, $tree, $skipstack)
    {
        if (!array_key_exists('create', $rules[$pop['rule']]))
        {
            //If the inner string is not empty, add it to the rule
            if ($append)
            {
                $pop['contents'][] = $append;
            }
            //Add the rule to the tree
            if ($this->notclosed($tree))
            {
                $pop2 = array_pop($tree);
                $pop2['contents'][] = $pop;
                $pop = $pop2;
            }
            $tree[] = $pop;
        }
        else
        {
            $create = $rules[$pop['rule']]['create'];
            $append = array
            (
                'contents' => array(),
                'create' => $append,
                'createrule' => $pop['rule'] . $append . $rules[$pop['rule']]['close'],
                'rule' => $create
            );
            $tree[] = $append;
            //If the skip key is true, skip over everything between this opening string and its closing string
            if (array_key_exists('skip', $rules[$create]) && $rules[$create]['skip'])
            {
                $skipstack[] = $rules[$create];
            }
        }
        return array
        (
            'skipstack' => $skipstack,
            'tree' => $tree
        );
    }

    public function configitems($config, $items)
    {
        $newconfig = array();
        foreach ($items as $value)
        {
            if (array_key_exists($value, $config))
            {
                $newconfig[$value] = $config[$value];
            }
        }
        return $newconfig;
    }

    public function defaultconfig($config)
    {
        if (!array_key_exists('escape', $config))
        {
            $config['escape'] = '\\';
        }
        if (!array_key_exists('insensitive', $config))
        {
            $config['insensitive'] = true;
        }
        if (!array_key_exists('log', $config))
        {
            $config['log'] = true;
        }
        if (!array_key_exists('mismatched', $config))
        {
            $config['mismatched'] = false;
        }
        if (!array_key_exists('unclosed', $config))
        {
            $config['unclosed'] = false;
        }
        return $config;
    }

    public function escape($escapestring, $position, $string)
    {
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
        //If the number of escape strings directly to the left of this position are odd, the position should be overlooked
        $condition = $count % 2;
        //If the condition is true, (x + 1) / 2 of them should be removed
        if ($condition)
        {
            $count++;
        }
        //Adjust the position
        $position -= strlen($escapestring) * ($count / 2);
        //Remove the decided number of escape strings
        $string = substr_replace($string, '', $position, strlen($escapestring) * ($count / 2));
        return array
        (
            'condition' => $condition,
            'position' => $position,
            'string' => $string
        );
    }

    public function execute($rules, $string, $config = array())
    {
        $config = $this->defaultconfig($config);
        $pos = $this->tokens($rules, $string, $config);
        $tree = $this->parse($rules, $pos, $string, $config);
        if ($config['log'])
        {
            $this->log['entries'][] = $this->loghash(
                array
                (
                    'config' => $config,
                    'entries' => array(),
                    'rules' => $this->ruleitems($rules, array('close', 'create', 'skip')),
                    'parse' => $tree,
                    'string' => $string,
                    'tokens' => $pos
                ),
                array('config', 'parse', 'rules', 'string', 'tokens')
            );
        }
        $string = $this->walk($rules, $tree, $config);
        if ($config['log'])
        {
            $pop = array_pop($this->log['entries']);
            $pop['walk'] = $string;
            $pop = $this->loghash($pop, array('walk'));
            $length = count($this->log['entries']);
            if ($length)
            {
                $this->log['entries'][$length - 1]['entries'][] = $pop;
            }
            else
            {
                $this->log['entries'][] = $pop;
            }
        }
        return $string;
    }

    public function lengthsort($a, $b)
    {
        return strlen($b['rule']) - strlen($a['rule']);
    }

    public function loghash($entry, $items)
    {
        $newlog = array();
        foreach ($entry as $key => $value)
        {
            if (in_array($key, $items))
            {
                $dumped = json_encode($value);
                $hashkey = md5($dumped);
                $this->log['hash'][$hashkey] = $dumped;
                $value = $hashkey;
            }
            $newlog[$key] = $value;
        }
        return $newlog;
    }

    public function notclosed($tree)
    {
        //If the tree is not empty and the last item is an array and has not been closed
        return (
            !empty($tree) && is_array($tree[count($tree) - 1]) &&
            (
                !array_key_exists('closed', $tree[count($tree) - 1]) || !$tree[count($tree) - 1]['closed']
            )
        );
    }

    public function parse($rules, $pos, $string, $config = array())
    {
        $config = $this->defaultconfig($config);
        $cachekey = md5(
            json_encode(
                array
                (
                    $string,
                    $this->ruleitems($rules, array('close', 'create', 'skip')),
                    $this->configitems($config, array('escape', 'insensitive', 'mismatched'))
                )
            )
        );
        //If a tree is cached for this case, load it
        if (array_key_exists($cachekey, $this->cache['parse']))
        {
            $tree = json_decode($this->cache['hash'][$this->cache['parse'][$cachekey]], true);
        }
        $flat = array();
        $last = 0;
        $skipoffset = 0;
        $skipstack = array();
        $temp = $string;
        $tree = array();
        foreach ($pos as $value)
        {
            //Adjust position to changes in length
            $position = $value['token']['start'] + strlen($string) - strlen($temp);
            $unescape = $this->escape($config['escape'], $position, $string);
            $escaping = true;
            $skip = false;
            if (!empty($skipstack))
            {
                $escaping = false;
                if (array_key_exists('skipescape', $skipstack[count($skipstack) - $skipoffset - 1]))
                {
                    $escaping = $skipstack[0]['skipescape'];
                }
                $skip = array_pop($skipstack);
            }
            //If this is an opening string
            if (
                $value['type'] == 'open' ||
                (
                    $value['type'] == 'flat' && !array_key_exists($value['rule'], $flat)
                )
            )
            {
                //If a value was not popped from skipstack
                if ($skip === false)
                {
                    $position = $unescape['position'];
                    $string = $unescape['string'];
                    //If this position should not be overlooked
                    if (!$unescape['condition'])
                    {
                        //If the inner string is not empty, add it to the tree
                        $append = substr($string, $last, $position - $last);
                        $last = $position + strlen($value['rule']);
                        //Add the text to the tree if necessary
                        if ($this->notclosed($tree))
                        {
                            $pop = array_pop($tree);
                            if ($append)
                            {
                                $pop['contents'][] = $append;
                            }
                            $tree[] = $pop;
                        }
                        else
                        {
                            if ($append)
                            {
                                $tree[] = $append;
                            }
                        }
                        //Add the rule to the tree
                        $append = array
                        (
                            'contents' => array(),
                            'rule' => $value['rule']
                        );
                        $tree[] = $append;
                        //If the skip key is true, skip over everything between this opening string and its closing string
                        if (array_key_exists('skip', $rules[$value['rule']]) && $rules[$value['rule']]['skip'])
                        {
                            $skipstack[] = $rules[$value['rule']];
                        }
                        $flat[$value['rule']] = NULL;
                    }
                }
                else
                {
                    //Put it back
                    $skipstack[] = $skip;
                    $skipclose = array($rules[$value['rule']]['close']);
                    if (array_key_exists('create', $rules[$value['rule']]))
                    {
                        $skipclose[] = $rules[$rules[$value['rule']]['create']]['close'];
                    }
                    //If the closing string for this rule matches it
                    if (in_array($skip['close'], $skipclose))
                    {
                        //If it explictly says to escape
                        if ($escaping)
                        {
                            $position = $unescape['position'];
                            $string = $unescape['string'];
                        }
                        //If this position should not be overlooked
                        if (!$unescape['condition'])
                        {
                            //Account for it
                            $skipstack[] = $skip;
                            $skipoffset++;
                        }
                    }
                }
            }
            else
            {
                //If a value was not popped or the closing string for this rule matches it
                if ($skip === false || $value['rule'] == $skip['close'])
                {
                    //If it explictly says to escape
                    if ($escaping)
                    {
                        $position = $unescape['position'];
                        $string = $unescape['string'];
                    }
                    //If this position should not be overlooked
                    if (!$unescape['condition'])
                    {
                        //If there is an offset, decrement it
                        if ($skipoffset)
                        {
                            $skipoffset--;
                        }
                        elseif ($this->notclosed($tree))
                        {
                            $pop = array_pop($tree);
                            //If this closing string matches the last rule's or it explicitly says to execute a mismatched case
                            if ($rules[$pop['rule']]['close'] == $value['rule'] || $config['mismatched'])
                            {
                                $pop['closed'] = true;
                                $result = $this->close(
                                    substr(
                                        $string,
                                        $last,
                                        $position - $last
                                    ),
                                    $pop,
                                    $rules,
                                    $tree,
                                    $skipstack
                                );
                                $skipstack = $result['skipstack'];
                                $tree = $result['tree'];
                                unset($flat[$value['rule']]);
                                $last = $position + strlen($value['rule']);
                            }
                            //Else, put the string back
                            else
                            {
                                if ($this->notclosed($tree))
                                {
                                    $pop2 = array_pop($tree);
                                    $pop2['contents'][] = $pop['rule'];
                                    foreach ($pop['contents'] as $value)
                                    {
                                        $pop2['contents'][] = $value;
                                    }
                                    $tree[] = $pop2;
                                }
                                else
                                {
                                    $tree[] = $pop['rule'];
                                    foreach ($pop['contents'] as $value)
                                    {
                                        $tree[] = $value;
                                    }
                                }
                            }
                        }
                    }
                }
                //Else, put the popped value back
                else
                {
                    $skipstack[] = $skip;
                }
            }
        }
        $string = substr($string, $last);
        //If the ending string is not empty, add it to the tree
        if ($string)
        {
            if ($this->notclosed($tree))
            {
                $pop = array_pop($tree);
                $tree = $this->close($string, $pop, $rules, $tree, $skipstack);
                $tree = $tree['tree'];
            }
            else
            {
                $tree[] = $string;
            }
        }
        $tree = array
        (
            'contents' => $tree
        );
        if (array_key_exists('', $rules))
        {
            $tree['rule'] = '';
        }
        //Cache the tree
        $dumped = json_encode($tree);
        $hashkey = md5($dumped);
        $this->cache['hash'][$hashkey] = $dumped;
        $this->cache['parse'][$cachekey] = $hashkey;
        return $tree;
    }

    public function positionsort($a, $b)
    {
        return $a['token']['start'] - $b['token']['start'];
    }

    public function ruleitems($rules, $items)
    {
        $newrules = array();
        foreach ($rules as $key => $value)
        {
            $newrules[$key] = array();
            foreach ($items as $value2)
            {
                if (array_key_exists($value2, $value))
                {
                    $newrules[$key][$value2] = $value[$value2];
                }
            }
        }
        return $newrules;
    }

    public function tokens($rules, $string, $config = array())
    {
        $config = $this->defaultconfig($config);
        $cachekey = md5(
            json_encode(
                array
                (
                    $string,
                    $this->ruleitems($rules, array('close')),
                    $this->configitems($config, array('insensitive'))
                )
            )
        );
        //If positions are cached for this case, load them
        if (array_key_exists($cachekey, $this->cache['tokens']))
        {
            return json_decode($this->cache['hash'][$this->cache['tokens'][$cachekey]], true);
        }
        $pos = array();
        $strings = array();
        $taken = array();
        foreach ($rules as $key => $value)
        {
            if (array_key_exists('close', $value))
            {
                $stringtype = 'flat';
                if ($key != $value['close'])
                {
                    $stringtype = 'open';
                    $strings[] = array
                    (
                        'rule' => $value['close'],
                        'type' => 'close'
                    );
                }
                $strings[] = array
                (
                    'rule' => $key,
                    'type' => $stringtype
                );
            }
        }
        //Order the strings by the length, descending
        usort($strings, array('SUIT', 'lengthsort'));
        foreach ($strings as $value)
        {
            if ($value['rule'])
            {
                $function = 'strpos';
                if ($config['insensitive'])
                {
                    $function = 'stripos';
                }
                $position = -1;
                //Find the next position of the string
                while (($position = $function($string, $value['rule'], $position + 1)) !== false)
                {
                    $success = true;
                    foreach ($pos as $value2)
                    {
                        $token = $value2['token'];
                        //If this string instance is in this reserved range
                        if (
                            (
                                $position >= $token['start'] && $position < $token['end']
                            ) ||
                            (
                                $position + strlen($value['rule']) > $token['start'] && $position + strlen($value['rule']) < $token['end']
                            )
                        )
                        {
                            $success = false;
                            break;
                        }
                    }
                    //If this string instance is not in any reserved range
                    if ($success)
                    {
                        //Add the position
                        $pos[] = array
                        (
                            'rule' => $value['rule'],
                            'token' => array
                            (
                                'start' => $position,
                                'end' => $position + strlen($value['rule'])
                            ),
                            'type' => $value['type']
                        );
                    }
                }
            }
        }
        //Order the positions from smallest to biggest
        usort($pos, array('SUIT', 'positionsort'));
        //Cache the positions
        $dumped = json_encode($pos);
        $hashkey = md5($dumped);
        $this->cache['hash'][$hashkey] = $dumped;
        $this->cache['tokens'][$cachekey] = $hashkey;
        return $pos;
    }

    public function walk($rules, $tree, $config = array())
    {
        $config = $this->defaultconfig($config);
        $string = '';
        $size = count($tree['contents']);
        for ($i = 0; $i < $size; $i++)
        {
            if (is_array($tree['contents'][$i]))
            {
                //If the tag has been closed or it explicitly says to execute unopened strings, walk through the contents with its rule
                if (
                    $config['unclosed'] ||
                    (
                        array_key_exists('closed', $tree['contents'][$i]) && $tree['contents'][$i]['closed']
                    )
                )
                {
                    $params = array
                    (
                        'config' => $config,
                        'rules' => $rules,
                        'string' => '',
                        'tree' => $tree['contents'][$i]
                    );
                    $params['tree']['key'] = $i;
                    $params['tree']['parent'] = &$tree;
                    if (array_key_exists('rule', $tree['contents'][$i]) && array_key_exists('functions', $rules[$tree['contents'][$i]['rule']]))
                    {
                        //Run the functions meant to be executed before walking through the tree
                        foreach ($rules[$tree['contents'][$i]['rule']]['functions'] as $value2)
                        {
                            //Note whether or not the function is in a class
                            if (array_key_exists('class', $value2))
                            {
                                $params = $value2['class']->$value2['function']($params);
                            }
                            else
                            {
                                $params = $value2['function']($params);
                            }
                        }
                    }
                    $string .= strval($params['string']);
                }
                //Else, execute it, ignoring the original opening string, with no rule
                else
                {
                    if (array_key_exists('rule', $tree['contents'][$i]))
                    {
                        $string .= $tree['contents'][$i]['rule'];
                    }
                    $string .= $this->walk($rules, $tree['contents'][$i], $config);
                }
            }
            else
            {
                $string .= $tree['contents'][$i];
            }
        }
        return $string;
    }
}
?>