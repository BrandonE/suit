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

    public function __construct()
    {
        $this->var = new stdClass();
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
            $create = $params['rules'][$pop['rule']]['create'];
            $append = array
            (
                'contents' => array(),
                'create' => $append,
                'createrule' => $pop['rule'] . $append . $params['rules'][$pop['rule']]['close'],
                'rule' => $create
            );
            $params['tree'][] = $append;
            //If the skip key is true, skip over everything between this opening string and its closing string
            if (array_key_exists('skip', $params['rules'][$create]) && $params['rules'][$create]['skip'])
            {
                $params['skipstack'][] = $params['rules'][$create];
            }
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
        $result = $this->walk($rules, $tree, $config);
        if ($config['log'])
        {
            $pop = array_pop($this->log['entries']);
            $pop['walk'] = $result;
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
        return $result;
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
                    'contents' => array(),
                    'rule' => $params['rule']
                );
                $params['tree'][] = $append;
                //If the skip key is true, skip over everything between this opening string and its closing string
                if (array_key_exists('skip', $params['rules'][$params['rule']]) && $params['rules'][$params['rule']]['skip'])
                {
                    $params['skipstack'][] = $params['rules'][$params['rule']];
                }
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

    public function parse($rules, $pos, $string, $config = array())
    {
        $config = $this->defaultconfig($config);
        $cachekey = md5(json_encode(array($string, $this->ruleitems($rules, array('close', 'create', 'skip')), $this->configitems($config, array('escape', 'insensitive', 'mismatched')))));
        //If a tree is cached for this case, load it
        if (array_key_exists($cachekey, $this->cache['parse']))
        {
            $tree = json_decode($this->cache['hash'][$this->cache['parse'][$cachekey]], true);
        }
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
        foreach ($pos as $value)
        {
            //Adjust position to changes in length
            $params['rule'] = $value[1]['rule'];
            $params['position'] = $value[0] + strlen($params['string']) - strlen($params['temp']);
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
            if ($value[1]['type'] == 'close' || ($value[1]['type'] == 'flat' && array_key_exists($params['rule'], $params['flat'])))
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
        $params['tree'] = array
        (
            'contents' => $params['tree']
        );
        if (array_key_exists('', $rules))
        {
            $params['tree']['rule'] = '';
        }
        //Cache the tree
        $dumped = json_encode($params['tree']);
        $hashkey = md5($dumped);
        $this->cache['hash'][$hashkey] = $dumped;
        $this->cache['parse'][$cachekey] = $hashkey;
        return $params['tree'];
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

    public function sort($a, $b)
    {
        return strlen($b['rule']) - strlen($a['rule']);
    }

    public function tokens($rules, $string, $config = array())
    {
        $config = $this->defaultconfig($config);
        $cachekey = md5(json_encode(array($string, $this->ruleitems($rules, array('close')), $this->configitems($config, array('insensitive')))));
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
        usort($strings, array('SUIT', 'sort'));
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
                    foreach ($taken as $value2)
                    {
                        //If this string instance is in this reserved range
                        if (($position >= $value2['start'] && $position < $value2['end']) || ($position + strlen($value['rule']) > $value2['start'] && $position + strlen($value['rule']) < $value2['end']))
                        {
                            $success = false;
                            break;
                        }
                    }
                    //If this string instance is not in any reserved range
                    if ($success)
                    {
                        //Add the position
                        $pos[$position] = $value;
                        //Reserve all positions taken up by this string instance
                        $taken[] = array
                        (
                            'start' => $position,
                            'end' => $position + strlen($value['rule'])
                        );
                    }
                }
            }
        }
        //Order the positions from smallest to biggest
        ksort($pos);
        $list = array();
        foreach ($pos as $key => $value)
        {
            $list[] = array($key, $value);
        }
        //Cache the positions
        $dumped = json_encode($list);
        $hashkey = md5($dumped);
        $this->cache['hash'][$hashkey] = $dumped;
        $this->cache['tokens'][$cachekey] = $hashkey;
        return $list;
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
                if ($config['unclosed'] || (array_key_exists('closed', $tree['contents'][$i]) && $tree['contents'][$i]['closed']))
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
                    if (array_key_exists('rule', $tree['contents'][$i]) && array_key_exists('functions', $params['rules'][$tree['contents'][$i]['rule']]))
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
                    $result = $this->walk($rules, $tree, $config);
                    if (array_key_exists('rule', $tree['contents'][$i]))
                    {
                        $string .= $tree['contents'][$i]['rule'];
                    }
                    $string .= $result['string'];
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