<?php
/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits

SUIT Framework (Scripting Using Integrated Templates) allows developers to define their own syntax for transforming templates by using rules.

-----------------------------
Example Usage
-----------------------------

require 'suit.class.php';
require 'templating.class.php';
$suit = new SUIT();
$templating = new Templating($suit);
$template = file_get_contents('template.tpl');
// Template contains "Hello, <strong>[var]username[/var]</strong>!"
print $suit->execute($templating->rules, $template);
#Result: Hello, Brandon!

-----------------------------
Caching and Logging
-----------------------------

Throughout SUIT, two dicts are used by the cache and tokens functions.

cache
    Saves processing time by storing the results of these functions.
log
    Contains information on how the execute function works.

For both log and cache, the hash key contains the actual data. The others reference this to deal with redundant items.
*/
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

    public function close($append, $pop, $rules, $tree, $skip)
    {
        // Handle a closed tag
        $rule = $rules[$pop['rule']];
        // If this rule does not create other rules
        if (!array_key_exists('create', $rule))
        {
            // If the inner string is not empty, add it to the rule
            if ($append)
            {
                $pop['contents'][] = $append;
            }
            $tree = $this->treeappend(array($pop), $tree);
        }
        else
        {
            // If this node is closed
            if ($this->closed($pop))
            {
                $create = $rule['create'];
                // Prepare to append the rule this rule creates
                $append = array
                (
                    'contents' => array(),
                    // Store the contents inside of the original rule
                    'create' => $append,
                    // Store the entire rule
                    'createrule' => $pop['rule'] . $append . $rule['close'],
                    'rule' => $create
                );
                // If the skip key is true, skip over everything between this open string and its close string
                if (array_key_exists('skip', $rules[$create]) && $rules[$create]['skip'])
                {
                    $skip = $create;
                }
            }
            else
            {
                // Prepare to add the open string
                $append = $pop['rule'] . $append;
            }
            $tree[] = $append;
        }
        return array
        (
            'skip' => $skip,
            'tree' => $tree
        );
    }

    public function closed($node)
    {
        // Check whether or not this item is an array and has been closed
        return (
            !is_array($node) ||
            (
                array_key_exists('closed', $node) && $node['closed']
            )
        );
    }

    public function configitems($config, $items)
    {
        // Get the specified items from the config
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
        // Return a default config if required keys are not present for a given dict
        if (!array_key_exists('escape', $config))
        {
            $config['escape'] = '\\';
        }
        if (!array_key_exists('insensitive', $config))
        {
            $config['insensitive'] = true;
        }
        // Do you want to log this entry?
        if (!array_key_exists('log', $config))
        {
            $config['log'] = true;
        }
        // If the close string doesn't match the open string, should it still close?
        if (!array_key_exists('mismatched', $config))
        {
            $config['mismatched'] = false;
        }
        // If a tag was opened but not closed, should it still walk?
        if (!array_key_exists('unclosed', $config))
        {
            $config['unclosed'] = false;
        }
        return $config;
    }

    public function escape($escapestring, $position, $string, $insensitive = true)
    {
        // Handle escape strings for this position
        $count = 0;
        $caseescape = $escapestring;
        $casestring = $string;
        if ($insensitive)
        {
            $caseescape = strtolower($caseescape);
            $casestring = strtolower($casestring);
        }
        // If the escape string is not empty
        if ($escapestring)
        {
            // Count how many escape characters are directly to the left of this position
            while (($focus = $position - $count - strlen($escapestring)) == abs($focus) && substr($casestring, $focus, strlen($escapestring)) == $caseescape)
            {
                $count += strlen($escapestring);
            }
            // Adjust the count based on the length
            $count = $count / strlen($escapestring);
        }
        // If the number of escape strings directly to the left of this position are odd, the position should be overlooked
        $odd = $count % 2;
        // If the count is odd, (x + 1) / 2 of them should be removed
        if ($odd)
        {
            $count++;
        }
        $count = $count / 2;
        // Adjust the position to after the remaining escape strings
        $position -= strlen($escapestring) * $count;
        // Remove the decided number of escape strings
        $string = substr_replace($string, '', $position, strlen($escapestring) * $count);
        return array
        (
            'odd' => $odd,
            'position' => $position,
            'string' => $string
        );
    }

    public function execute($rules, $string, $config = array())
    {
        // Translate string using rules
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
        // Order the strings by the length, in descending order, so that bigger strings are given priority over smaller strings
        return strlen($b['rule']) - strlen($a['rule']);
    }

    public function loghash($entry, $items)
    {
        // Hash specific keys for logging
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

    public function parse($rules, $pos, $string, $config = array())
    {
        // Generate the tree for execute
        $config = $this->defaultconfig($config);
        //Generate a dict key for a given parameters to save to and load from cache. Thus, the cache key will be the same if the parameters are the same
        $cachekey = md5(
            json_encode(
                array
                (
                    $this->ruleitems($rules, array('close', 'create', 'skip')),
                    $pos,
                    $string,
                    $this->configitems($config, array('escape', 'insensitive', 'mismatched'))
                )
            )
        );
        // If a tree is cached for this case, load it
        if (array_key_exists($cachekey, $this->cache['parse']))
        {
            $tree = json_decode($this->cache['hash'][$this->cache['parse'][$cachekey]], true);
        }
        // Contains a set of the flat rules that have been opened and not closed
        $flat = array();
        // The position after the last string analyzed
        $last = 0;
        // The skip rule, if opened
        $skip = false;
        // How many additional skip rules to account for
        $skipoffset = 0;
        // The original string
        $temp = $string;
        // The string broken into a tree
        $tree = array();
        foreach ($pos as $value)
        {
            // Adjust position to changes in length
            $position = $value['token']['start'] + strlen($string) - strlen($temp);
            $escapeinfo = $this->escape($config['escape'], $position, $string, $config['insensitive']);
            // If no unclosed skip rules have been opened or said rule explicitly says to escape
            $escaping = (
                !$skip or
                (
                    array_key_exists('skipescape', $rules[$skip]) && $rules[$skip]['skipescape']
                )
            );
            $flatopen = ($value['type'] == 'flat' && !array_key_exists($value['rule'], $flat));
            // If this is an open string
            if ($value['type'] == 'open' || $flatopen)
            {
                $rule = $rules[$value['rule']];
                // If no unclosed skip rules have been opened
                if (!$skip)
                {
                    $position = $escapeinfo['position'];
                    $string = $escapeinfo['string'];
                    // If this position should not be overlooked
                    if (!$escapeinfo['odd'])
                    {
                        // If the inner string is not empty, add it to the tree
                        $append = substr($string, $last, $position - $last);
                        // Adjust to after this string
                        $last = $position + strlen($value['rule']);
                        $tree = $this->treeappend(array($append), $tree);
                        // Add the rule to the tree
                        $append = array
                        (
                            'contents' => array(),
                            'rule' => $value['rule']
                        );
                        $tree[] = $append;
                        // If the skip key is true, skip over everything between this open string and its close string
                        if (array_key_exists('skip', $rule) && $rule['skip'])
                        {
                            $skip = $value['rule'];
                        }
                        // If this rule is flat, the next instance of it will be a closing string
                        $flat[$value['rule']] = NULL;
                    }
                }
                else
                {
                    $skipclose = array($rule['close']);
                    if (array_key_exists('create', $rule))
                    {
                        $skipclose[] = $rules[$rule['create']]['close'];
                    }
                    // If the close string matches the rule or the rule it creates
                    if (in_array($rules[$skip]['close'], $skipclose))
                    {
                        // If it explictly says to escape
                        if ($escaping)
                        {
                            $position = $escapeinfo['position'];
                            $string = $escapeinfo['string'];
                        }
                        // If this position should not be overlooked, account for it
                        if (!$escapeinfo['odd'])
                        {
                            $skipoffset++;
                        }
                    }
                }
            }
            // Else, if no unclosed skip rules have been opened or the close string for this rule matches it
            elseif ($skip === false || $value['rule'] == $rules[$skip]['close'])
            {
                // If it explictly says to escape
                if ($escaping)
                {
                    $position = $escapeinfo['position'];
                    $string = $escapeinfo['string'];
                }
                // If this position should not be overlooked
                if (!$escapeinfo['odd'])
                {
                    // If there is an offset, decrement it
                    if ($skipoffset)
                    {
                        $skipoffset--;
                    }
                    // Else, if the tree is not empty and the last node is not closed
                    elseif ($tree && !$this->closed($tree[count($tree) - 1]))
                    {
                        // Stop skipping
                        $skip = false;
                        $pop = array_pop($tree);
                        // If this close string matches the last rule's or the config explicitly says to execute a mismatched case
                        if ($rules[$pop['rule']]['close'] == $value['rule'] || $config['mismatched'])
                        {
                            // Mark the rule as closed
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
                                $skip
                            );
                            $skip = $result['skip'];
                            $tree = $result['tree'];
                            unset($flat[$value['rule']]);
                            // Adjust to after this string
                            $last = $position + strlen($value['rule']);
                        }
                        // Else, add the opening string and the contents of the rule
                        else
                        {
                            $tree = $this->treeappend(array_merge(array($pop['rule']), $pop['contents']), $tree);
                        }
                    }
                }
            }
        }
        $append = substr($string, $last);
        // While the tree is not empty and the last node is not closed
        while (
            $tree && !$this->closed($tree[$key])
        )
        {
            // Add to the last node
            $pop = array_pop($tree);
            $tree = $this->close($append, $pop, $rules, $tree, $skip);
            $tree = $tree['tree'];
            // Make the last node the next thing to append
            $append = array_pop($tree);
        }
        // Add to the tree if necessary
        if ($append)
        {
            $tree[] = $append;
        }
        $tree = array
        (
            'contents' => $tree,
            'closed' => true
        );
        if (array_key_exists('', $rules))
        {
            $tree['rule'] = '';
        }
        // Cache the tree
        $dumped = json_encode($tree);
        $hashkey = md5($dumped);
        $this->cache['hash'][$hashkey] = $dumped;
        $this->cache['parse'][$cachekey] = $hashkey;
        return $tree;
    }

    public function positionsort($a, $b)
    {
        // Order the positions from smallest to biggest
        return $a['token']['start'] - $b['token']['start'];
    }

    public function ruleitems($rules, $items)
    {
        // Get the specified items from the rules
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
        // Generate the tokens for execute
        $config = $this->defaultconfig($config);
        //Generate a dict key for a given parameters to save to and load from cache. Thus, the cache key will be the same if the parameters are the same
        $cachekey = md5(
            json_encode(
                array
                (
                    $this->ruleitems($rules, array('close')),
                    $string,
                    $this->configitems($config, array('insensitive'))
                )
            )
        );
        // If positions are cached for this case, load them
        if (array_key_exists($cachekey, $this->cache['tokens']))
        {
            return json_decode($this->cache['hash'][$this->cache['tokens'][$cachekey]], true);
        }
        $pos = array();
        $strings = array();
        $taken = array();
        foreach ($rules as $key => $value)
        {
            // No need adding the open string if no close string provided
            if (array_key_exists('close', $value))
            {
                // Open strings open a block. Close strings close a block. Flat strings are open or close strings depending on context
                $stringtype = 'flat';
                // If the open string is the same as the close string, it is flat
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
        usort($strings, array('SUIT', 'lengthsort'));
        foreach ($strings as $value)
        {
            // Only proceed if there is a rule to match against
            if ($value['rule'])
            {
                $function = 'strpos';
                if ($config['insensitive'])
                {
                    $function = 'stripos';
                }
                $length = strlen($value['rule']);
                $position = -1;
                // Find the next position of the string, and continue until there are no more matches
                while (($position = $function($string, $value['rule'], $position + 1)) !== false)
                {
                    $end = $position + $length;
                    $success = true;
                    foreach ($pos as $value2)
                    {
                        $token = $value2['token'];
                        $startrange = ($position >= $token['start'] && $position < $token['end']);
                        $endrange = ($end > $token['start'] && $end < $token['end']);
                        // If this instance is in this reserved range, ignore it
                        if ($startrange || $endrange)
                        {
                            $success = false;
                            break;
                        }
                    }
                    // If this string instance is not in any reserved range, then append it to the positions list
                    if ($success)
                    {
                        $pos[] = array
                        (
                            'rule' => $value['rule'],
                            'token' => array
                            (
                                'start' => $position,
                                'end' => $end
                            ),
                            'type' => $value['type']
                        );
                    }
                }
            }
        }
        usort($pos, array('SUIT', 'positionsort'));
        // Cache the positions
        $dumped = json_encode($pos);
        $hashkey = md5($dumped);
        $this->cache['hash'][$hashkey] = $dumped;
        $this->cache['tokens'][$cachekey] = $hashkey;
        return $pos;
    }

    public function treeappend($append, $tree)
    {
        // Add to the tree in the appropriate place if necessary
        if ($append)
        {
            // If the tree is not empty and the last node is not closed
            if ($tree && !$this->closed($tree[count($tree) - 1]))
            {
                // Add to the node
                $pop = array_pop($tree);
                foreach ($append as $value)
                {
                    $pop['contents'][] = $value;
                }
                $tree[] = $pop;
            }
            else
            {
                // Add to the trunk
                foreach ($append as $value)
                {
                    $tree[] = $value;
                }
            }
        }
        return $tree;
    }

    public function walk($rules, $tree, $config = array())
    {
        // Walk through the tree and generate the string
        $config = $this->defaultconfig($config);
        $string = '';
        $size = count($tree['contents']);
        for ($i = 0; $i < $size; $i++)
        {
            $value = $tree['contents'][$i];
            # If this item is a dict
            if (is_array($value))
            {
                // If the tag has been closed or the config explicitly says to walk through unclosed nodes, walk through the contents with its rule
                if (
                    (
                        array_key_exists('closed', $value) && $value['closed']
                    ) ||
                    $config['unclosed']
                )
                {
                    // Give the rule functions parameters to work with
                    $params = array
                    (
                        'config' => $config,
                        'rules' => $rules,
                        'string' => '',
                        'tree' => $value
                    );
                    $params['tree']['key'] = $i;
                    // Allow reference to the parent branch
                    $params['tree']['parent'] = &$tree;
                    if (array_key_exists('rule', $value) && array_key_exists('functions', $rules[$value['rule']]))
                    {
                        // Run the specified functions
                        foreach ($rules[$value['rule']]['functions'] as $value2)
                        {
                            // Note whether or not the function is in a class
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
                    // Add the resulting string
                    $string .= strval($params['string']);
                }
                // Else, add the open string and the result of walking through it
                else
                {
                    $string .= $value['rule'] . $this->walk($rules, $value, $config);
                }
            }
            // Else, add the string
            else
            {
                $string .= $value;
            }
        }
        return $string;
    }
}
?>