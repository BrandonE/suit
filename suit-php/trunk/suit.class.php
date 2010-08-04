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

SUIT Framework (Scripting Using Integrated Templates) allows developers to
define their own syntax for transforming templates by using rules.

-----------------------------
Example Usage
-----------------------------

::

    require 'suit.class.php';
    require 'templating.class.php';
    $suit = new SUIT();
    $templating = new Templating($suit);
    $template = file_get_contents('template.tpl');
    // Template contains "Hello, <strong>[var]username[/var]</strong>!"
    echo $suit->execute($templating->rules, $template);
    // Result: Hello, <strong>Brandon</strong>!

Basic usage; see http://www.suitframework.com/docs/ for other uses.

-----------------------------
Caching and Logging
-----------------------------

``cache``
    dict - Saves processing time by storing the results of these functions.

``log``
    dict - Contains information on how the execute function works.

For both ``log`` and ``cache``, the `hash` key contains the actual data. The
others reference this to deal with redundant items.
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
        'contents' => array(),
        'hash' => array()
    );

    public $version = '2.0.1';

    public function close($append, $pop, $rules, $tree)
    {
        /*
        Handle the closing of a rule.

        ``rules``
            dict - The rules used to determine how to add the string.

        ``append``
            str - The string to add.

        ``pop``
            dict - The last item of the tree's contents.

        ``tree``
            list - The contents of the tree.

        Returns: dict - A dict with the following format format:

        `skip`
            str - The skip rule, if opened.

        `tree`
            list - The contents of the tree with the appended data.
        */
        $skip = false;
        $rule = $rules[$pop['rule']];
        // If this rule does not create other rules
        if (!array_key_exists('create', $rule))
        {
            // If the inner string is not empty, add it to the rule.
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
                // Prepare to append the rule this rule creates.
                $append = array
                (
                    'contents' => array(),
                    // Store the contents inside of the original rule.
                    'create' => $append,
                    // Store the entire rule statement.
                    'createrule' => $pop['rule'] . $append . $rule['close'],
                    'rule' => $create
                );
                /*
                If the skip key is true, skip over everything between this open
                string and its close string.
                */
                if (
                    array_key_exists('skip', $rules[$create]) &&
                    $rules[$create]['skip']
                )
                {
                    $skip = $create;
                }
            }
            else
            {
                // Prepare to add the open string.
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
        /*
        Check whether or not this item is a dict and has been closed.

        ``node``
            mixed - The item to check.

        Returns: bool - The condition.
        */
        return (
            !is_array($node) ||
            (
                array_key_exists('closed', $node) && $node['closed']
            )
        );
    }

    public function configitems($config, $items)
    {
        /*
        Get the specified items from the config.

        ``config``
            dict - The dict to grab from.

        ``items``
            list - The items to grab from the dict.

        Returns: dict - The dict with the specified items.
        */
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
        /*
        Fill a dict with the defaults for the missing items.

        ``config``
            dict - The dict to fill.

        Returns: dict - A dict with the following format:

        `escape`
            str - The escape string.

        `insensitive`
            str - Whether or not the searching should be done case
            insensitively.

        `log`
            bool - Whether or not the execute call should be logged.

        `mismatched`
            bool - Whether or not to parse if the closing string does not match
            the opening string.

        `unclosed`
            bool - Whether or not the SUIT should walk through the node if it
            was opened but not closed.
        */
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
        /*
        If the close string doesn't match the open string, should it still
        close?
        */
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

    public function escape(
        $escapestring, $position, $string, $insensitive = true
    )
    {
        /*
        Handle escape strings for this position.

        ``escapestring``
            str - The string to check for behind this position.

        ``position``
            int - The position of the open or close string to check for.

        ``string``
            str - The full string to check in.

        ``insensitive``
            bool - Whether or not the searching should be done case
            insensitively.

        Returns: dict - A dict with the following format:

        `odd`
            bool - Whether or not the count of the escape strings to the left
            of this position is odd, escaping the open or close string.

        `position`
            int - The position adjusted to the change in the string.

        `string`
            str - The string omitting the necessary escape strings.
        */
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
            /*
            Count how many escape characters are directly to the left of this
            position.
            */
            while (
                (
                    $focus = $position - $count - strlen($escapestring)
                ) == abs($focus) &&
                substr(
                    $casestring, $focus, strlen($escapestring)
                ) == $caseescape
            )
            {
                $count += strlen($escapestring);
            }
            // Adjust the count based on the length.
            $count = $count / strlen($escapestring);
        }
        /*
        If the number of escape strings directly to the left of this
        position are odd, the position should be overlooked.
        */
        $odd = $count % 2;
        // If the count is odd, (x + 1) / 2 of them should be removed.
        if ($odd)
        {
            $count++;
        }
        $count = $count / 2;
        // Adjust the position to after the remaining escape strings.
        $position -= strlen($escapestring) * $count;
        // Remove the decided number of escape strings.
        $string = substr_replace(
            $string, '', $position, strlen($escapestring) * $count
        );
        return array
        (
            'odd' => $odd,
            'position' => $position,
            'string' => $string
        );
    }

    public function execute($rules, $string, $config = array())
    {
        /*
        Transform a string using rules. The function calls ``tokens``,
        ``parse``, and ``walk`` all in one convenient call.

        ``rules``
            dict - The rules used to transform the string.

        ``string``
            str - The string to transform.

        ``config``
            dict - Specifics on how the function should work. (Optional. See
            `defaultconfig`)

        Returns: str - The transformed string.
        */
        $config = $this->defaultconfig($config);
        $pos = $this->tokens($rules, $string, $config);
        $tree = $this->parse($rules, $pos, $string, $config);
        if ($config['log'])
        {
            // Append this entry, hashing everything but the contents
            $this->log['contents'][] = $this->loghash(
                array
                (
                    'config' => $config,
                    'contents' => array(),
                    'rules' => $this->ruleitems(
                        $rules, array('close', 'create', 'skip')
                    ),
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
            $pop = array_pop($this->log['contents']);
            // Add the result to the tree
            $pop['walk'] = $string;
            // Hash it
            $pop = $this->loghash($pop, array('walk'));
            $this->log['contents'] = $this->treeappend(
                array($pop), $this->log['contents']
            );
        }
        return $string;
    }

    public function functions($params)
    {
        // Run the specified functions.
        $rule = $params['tree']['rule'];
        foreach ($params['rules'][$rule]['functions'] as $value)
        {
            /*
            Note whether or not the function is in a class.
            */
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

    public function loghash($entry, $items)
    {
        /*
        Hash specific keys for logging.

        ``entry``
            dict - The dict.

        ``items``
            list - The items to hash in the dict.

        Returns: dict - The dict with the specified items hashed.
        */
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
        /*
        Generate the tree from the tokens and string. The tree will show how
        the string has been broken up and how to transform it.

        ``rules``
            dict - The rules used to break up the string.

        ``pos``
            dict - A list of the positions of the various open and close
            strings.

        ``string``
            str - The string to break up.

        ``config``
            dict - Specifics on how the function should work. (Optional. See
            `defaultconfig`)

        Returns: dict -

        ::

            array
            (
                'closed' => true // Shown if this node has been closed.
                'contents' => array
                (
                    'string',
                    array
                    (
                        'closed' => true
                        'contents' => array(...),
                        // The contents of the create rule if applicable.
                        'create' => ' condition="var"',
                         // The whole create rule statement if applicable.
                        'createrule' => '[if condition="var"]',
                        'rule' => '[if]' // The type of rule
                    ),
                    ...
                ) // This node's branches.
            )
        */
        $config = $this->defaultconfig($config);
        /*
        Generate a dict key for a given parameters to save to and load from
        cache. Thus, the cache key will be the same if the parameters are the
        same.
        */
        $cachekey = md5(
            json_encode(
                array
                (
                    $this->ruleitems($rules, array('close', 'create', 'skip')),
                    $pos,
                    $string,
                    $this->configitems(
                        $config, array('escape', 'insensitive', 'mismatched')
                    )
                )
            )
        );
        // If a tree is cached for this case, load it.
        if (array_key_exists($cachekey, $this->cache['parse']))
        {
            return json_decode(
                $this->cache['hash'][$this->cache['parse'][$cachekey]], true
            );
        }
        /*
        Contains a set of the flat rules that have been opened and not
        closed.
        */
        $flat = array();
        // The position after the last string analyzed.
        $last = 0;
        // The skip rule, if opened.
        $skip = false;
        // How many additional skip rules to account for.
        $skipoffset = 0;
        // The original string.
        $temp = $string;
        // The string broken into a tree.
        $tree = array();
        foreach ($pos as $value)
        {
            // Adjust position to changes in length.
            $position = $value['bounds']['start'] + strlen($string) - strlen(
                $temp
            );
            $escapeinfo = $this->escape(
                $config['escape'], $position, $string, $config['insensitive']
            );
            /*
            If no unclosed skip rules have been opened or said rule explicitly
            says to escape
            */
            $escaping = (
                !$skip or
                (
                    array_key_exists('skipescape', $rules[$skip]) &&
                    $rules[$skip]['skipescape']
                )
            );
            $flatopen = (
                $value['type'] == 'flat' &&
                !array_key_exists($value['string'], $flat)
            );
            // If this is an open string
            if ($value['type'] == 'open' || $flatopen)
            {
                $rule = $rules[$value['string']];
                // If no unclosed skip rules have been opened
                if (!$skip)
                {
                    $position = $escapeinfo['position'];
                    $string = $escapeinfo['string'];
                    // If this position should not be overlooked
                    if (!$escapeinfo['odd'])
                    {
                        /*
                        If the inner string is not empty, add it to the tree.
                        */
                        $append = substr($string, $last, $position - $last);
                        // Adjust to after this string.
                        $last = $position + strlen($value['string']);
                        $tree = $this->treeappend(array($append), $tree);
                        // Add the rule to the tree.
                        $append = array
                        (
                            'contents' => array(),
                            'rule' => $value['string']
                        );
                        $tree[] = $append;
                        /*
                        If the skip key is true, skip over everything between
                        this open string and its close string.
                        */
                        if (array_key_exists('skip', $rule) && $rule['skip'])
                        {
                            $skip = $value['string'];
                        }
                        /*
                        If this rule is flat, the next instance of it will be a
                        closing string.
                        */
                        $flat[$value['string']] = NULL;
                    }
                }
                else
                {
                    $skipclose = array($rule['close']);
                    if (array_key_exists('create', $rule))
                    {
                        $skipclose[] = $rules[$rule['create']]['close'];
                    }
                    /*
                    If the close string matches the rule or the rule it creates
                    */
                    if (in_array($rules[$skip]['close'], $skipclose))
                    {
                        // If it explictly says to escape
                        if ($escaping)
                        {
                            $position = $escapeinfo['position'];
                            $string = $escapeinfo['string'];
                        }
                        /*
                        If this position should not be overlooked, account for
                        it.
                        */
                        if (!$escapeinfo['odd'])
                        {
                            $skipoffset++;
                        }
                    }
                }
            }
            /*
            Else, if no unclosed skip rules have been opened or the close
            string for this rule matches it
            */
            elseif (
                $skip === false || $value['string'] == $rules[$skip]['close']
            )
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
                    // If there is an offset, decrement it.
                    if ($skipoffset)
                    {
                        $skipoffset--;
                    }
                    /*
                    Else, if the tree contents are not empty and the last node
                    is not closed.
                    */
                    elseif ($tree && !$this->closed($tree[count($tree) - 1]))
                    {
                        // Stop skipping.
                        $skip = false;
                        $pop = array_pop($tree);
                        /*
                        If this close string matches the last rule's or the
                        config explicitly says to execute a mismatched case
                        */
                        if (
                            $rules[
                                $pop['rule']
                            ]['close'] == $value['string'] ||
                            $config['mismatched']
                        )
                        {
                            // Mark the rule as closed.
                            $pop['closed'] = true;
                            $result = $this->close(
                                substr(
                                    $string,
                                    $last,
                                    $position - $last
                                ),
                                $pop,
                                $rules,
                                $tree
                            );
                            $skip = $result['skip'];
                            $tree = $result['tree'];
                            unset($flat[$value['string']]);
                            // Adjust to after this string.
                            $last = $position + strlen($value['string']);
                        }
                        /*
                        Else, add the opening string and the contents of the
                        rule.
                        */
                        else
                        {
                            $rulestring = $pop['rule'];
                            if (array_key_exists('createrule', $pop))
                            {
                                $rulestring = $pop['createrule'];
                            }
                            $tree = $this->treeappend(
                                array_merge(
                                    array($rulestring), $pop['contents']
                                ),
                                $tree
                            );
                        }
                    }
                }
            }
        }
        $append = substr($string, $last);
        /*
        While the tree contents are not empty and the last node is not closed.
        */
        while (
            $tree && !$this->closed($tree[count($tree) - 1])
        )
        {
            // Add to the last node.
            $pop = array_pop($tree);
            $tree = $this->close($append, $pop, $rules, $tree);
            $tree = $tree['tree'];
            // Make the last node the next thing to append.
            $append = array_pop($tree);
        }
        // Add to the tree if necessary.
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
        // Cache the tree.
        $dumped = json_encode($tree);
        $hashkey = md5($dumped);
        $this->cache['hash'][$hashkey] = $dumped;
        $this->cache['parse'][$cachekey] = $hashkey;
        return $tree;
    }

    public function positionsort($a, $b)
    {
        // Order the positions from smallest to biggest.
        return $a['bounds']['start'] - $b['bounds']['start'];
    }

    public function ruleitems($rules, $items)
    {
        /*
        Get the specified items from the rules.

        ``rules``
            dict - The dict to grab from.

        ``items``
            list - The items to grab from the dict.

        Returns: dict - The dict with the specified items.
        */
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

    public function rulesort($a, $b)
    {
        /*
        Sort by priority, and if it is equal, sort by the size of the string.
        */
        if (
            array_key_exists('priority', $a) &&
            !array_key_exists('priority', $b)
        )
        {
            return -1;
        }
        elseif (
            array_key_exists('priority', $b) &&
            !array_key_exists('priority', $a)
        )
        {
            return 1;
        }
        elseif (
            array_key_exists('priority', $a) &&
            array_key_exists('priority', $b)
        )
        {
            if ($a['priority'] > $b['priority'])
            {
                return -1;
            }
            elseif ($b['priority'] > $a['priority'])
            {
                return 1;
            }
        }
        return strlen($b['string']) - strlen($a['string']);
    }

    public function tokens($rules, $string, $config = array())
    {
        /*
        Generate the tokens from the string. Tokens contain the different open
        and close strings and their positions.

        ``rules``
            dict - The rules containing the strings to search for.

        ``string``
            str - The string to find the strings in.

        ``config``
            dict - Specifics on how the function should work. (Optional. See
            `defaultconfig`)

        Returns: dict - A list of dicts with the following format:

        `bounds`
            dict - A dict with the following format:
                `start`
                    int - Where the string starts.

                `end`
                    int - Where the string ends.

        `string`
            str - The located string.

        `type`
            str - The type, options being open, close, or flat.
        */
        $config = $this->defaultconfig($config);
        /*
        Generate a dict key for a given parameters to save to and load from
        cache. Thus, the cache key will be the same if the parameters are the
        same.
        */
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
        // If positions are cached for this case, load them.
        if (array_key_exists($cachekey, $this->cache['tokens']))
        {
            return json_decode(
                $this->cache['hash'][$this->cache['tokens'][$cachekey]], true
            );
        }
        $pos = array();
        $repeated = array();
        $strings = array();
        $taken = array();
        foreach ($rules as $key => $value)
        {
            // No need adding the open string if no close string provided.
            if (array_key_exists('close', $value))
            {
                $item = array();
                if (array_key_exists('priority', $value))
                {
                    $item['priority'] = $value['priority'];
                }
                /*
                Open strings open a block. Close strings close a block. Flat
                strings are open or close strings depending on context.
                */
                $stringtype = 'flat';
                /*
                If the open string is the same as the close string, it is flat.
                */
                if ($key != $value['close'])
                {
                    $stringtype = 'open';
                    $item['string'] = $value['close'];
                    $item['type'] = 'close';
                    $strings[] = $item;
                }
                $item['string'] = $key;
                $item['type'] = $stringtype;
                $strings[] = $item;
            }
        }
        usort($strings, array('SUIT', 'rulesort'));
        foreach ($strings as $value)
        {
            $tempstring = $value['string'];
            /*
            Only proceed if there is a rule to match against, and it has yet to
            be searched for.
            */
            if ($tempstring && !in_array($tempstring, $repeated))
            {
                $function = 'strpos';
                if ($config['insensitive'])
                {
                    $function = 'stripos';
                }
                $length = strlen($tempstring);
                $position = -1;
                /*
                Find the next position of the string, and continue until there
                are no more matches.
                */
                while (
                    (
                        $position = $function(
                            $string, $tempstring, $position + 1
                        )
                    ) !== false
                )
                {
                    $endposition = $position + $length;
                    $success = true;
                    foreach ($pos as $value2)
                    {
                        $start = $value2['bounds']['start'];
                        $end = $value2['bounds']['end'];
                        $startrange = (
                            $position >= $start && $position < $end
                        );
                        $endrange = (
                            $endposition > $start && $endposition < $end
                        );
                        /*
                        If this instance is in this reserved range, ignore it.
                        */
                        if ($startrange || $endrange)
                        {
                            $success = false;
                            break;
                        }
                    }
                    /*
                    If this string instance is not in any reserved range, then
                    append it to the positions list.
                    */
                    if ($success)
                    {
                        $pos[] = array
                        (
                            'bounds' => array
                            (
                                'start' => $position,
                                'end' => $endposition
                            ),
                            'string' => $tempstring,
                            'type' => $value['type']
                        );
                    }
                }
                // Prevent this rule from being searched for again.
                $repeated[] = $tempstring;
            }
        }
        usort($pos, array('SUIT', 'positionsort'));
        // Cache the positions.
        $dumped = json_encode($pos);
        $hashkey = md5($dumped);
        $this->cache['hash'][$hashkey] = $dumped;
        $this->cache['tokens'][$cachekey] = $hashkey;
        return $pos;
    }

    public function treeappend($append, $tree)
    {
        /*
        Add to the tree contents in the appropriate place if necessary.

        ``append``
            list - The items to add.

        ``tree``
            list - The contents of the tree to add the item on.

        Returns: list - The updated tree contents.
        */
        if ($append)
        {
            /*
            If the tree contents are not empty and the last node is not closed.
            */
            if ($tree && !$this->closed($tree[count($tree) - 1]))
            {
                // Add to the node.
                $pop = array_pop($tree);
                foreach ($append as $value)
                {
                    $pop['contents'][] = $value;
                }
                $tree[] = $pop;
            }
            else
            {
                // Add to the trunk.
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
        /*
        Walk through the tree and generate the string.

        ``rules``
            dict - The rules used to specify how to walk through the tree.

        ``tree``
            dict - The tree to walk through.

        ``config``
            dict - Specifics on how the function should work. (Optional. See
            `defaultconfig`)

        Returns: str - The generated string.
        */
        $config = $this->defaultconfig($config);
        $string = '';
        $size = count($tree['contents']);
        for ($i = 0; $i < $size; $i++)
        {
            $value = $tree['contents'][$i];
            # If this item is a dict
            if (is_array($value))
            {
                /*
                If the tag has been closed or the config explicitly says to
                walk through unclosed nodes, walk through the contents with its
                rule.
                */
                if (
                    (
                        array_key_exists('closed', $value) && $value['closed']
                    ) ||
                    $config['unclosed']
                )
                {
                    // Give the rule functions parameters to work with.
                    $params = array
                    (
                        'config' => $config,
                        'rules' => $rules,
                        'string' => '',
                        'tree' => $value
                    );
                    $params['tree']['key'] = $i;
                    // Allow reference to the parent branch.
                    $params['tree']['parent'] = &$tree;
                    if (
                        array_key_exists('rule', $value) &&
                        array_key_exists('functions', $rules[$value['rule']])
                    )
                    {
                        $params = $this->functions($params);
                    }
                    // Add the resulting string.
                    $string .= strval($params['string']);
                }
                /*
                Else, add the open string and the result of walking through it
                without the rule.
                */
                else
                {
                    $rulestring = $value['rule'];
                    if (array_key_exists('createrule', $value))
                    {
                        $rulestring = $value['createrule'];
                    }
                    $string .= $rulestring . $this->walk(
                        $rules, $value, $config
                    );
                }
            }
            // Else, add the string.
            else
            {
                $string .= $value;
            }
        }
        return $string;
    }
}
?>