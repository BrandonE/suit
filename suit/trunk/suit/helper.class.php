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
        //If this should not be skipped over
        if ($skippop === false || ($params['nodes'][$params['node']]['close'] == $skippop && !$this->owner->parseunescape($params['position'], $params['return'], $params['escape'])))
        {
            //If this position should not be overlooked and the stack is not empty
            if (!$this->owner->parseunescape(&$params['position'], &$params['return'], $params['escape']) && !$params['skipoffset'] && !empty($params['stack']))
            {
                $params['open'] = array_pop($params['stack']);
                //If this closing string matches the last node's
                if ($params['open']['node']['close'] == $params['nodes'][$params['node']]['close'])
                {
                    $params = $this->transform($params);
                }
            }
            elseif (!$this->owner->parseunescape($params['position'], $params['return'], $params['escape']) && !empty($params['stack']))
            {
                $params['skipoffset']--;
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
        //If this should not be skipped over
        if ($skippop === false || ($params['nodes'][$params['node']]['close'] == $skippop && !$this->owner->parseunescape($params['position'], $params['return'], $params['escape'])))
        {
            $condition = $this->owner->parseunescape(&$params['position'], &$params['return'], $params['escape']);
            //If a value was not popped from skipnode and this position should not be overlooked
            if ($skippop === false && !$condition)
            {
                //Add the opening string to the stack
                $clone = array();
                foreach ($params['nodes'][$params['node']] as $key => $value)
                {
                    $clone[$key] = $value;
                }
                if (array_key_exists('function', $clone))
                {
                    $clone['function'] = array();
                    foreach ($params['nodes'][$params['node']]['function'] as $value)
                    {
                        $clone['function'][] = $value;
                    }
                }
                $params['stack'][] = array
                (
                    'node' => $params['nodes'][$params['node']],
                    'open' => $params['node'],
                    'position' => $params['position']
                );
            }
            //If a value was popped from skipnode or the skip key is true and this position should not be overlooked, skip over everything between this opening string and its closing string
            if ($skippop !== false || (array_key_exists('skip', $params['nodes'][$params['node']]) && $params['nodes'][$params['node']]['skip'] && !$condition))
            {
                $params['skipnode'][] = $params['nodes'][$params['node']]['close'];
            }
            //If a value was popped from skipnode, nothing should be parsed until this is closed.
            if ($skippop !== false)
            {
                $params['skipoffset']++;
            }
        }
        //If we popped a value, put it back
        if ($skippop !== false)
        {
            $params['skipnode'][] = $skippop;
        }
        return $params;
    }

    public function parsecache($nodes, $return, $config)
    {
        $values = array();
        foreach ($nodes as $key => $value)
        {
            $array = array($key);
            if (array_key_exists('close', $value))
            {
                $array[] = $value['close'];
            }
            $values[] = $array;
        }
        return md5(md5(serialize($return)) . md5(serialize($values)) . md5(serialize($config['taken'])));
    }

    public function parseconfig($config)
    {
        if (!array_key_exists('escape', $config))
        {
            $config['escape'] = $this->owner->config['parse']['escape'];
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
        $params = array
        (
            'pos' => array(),
            'repeated' => array(),
            'taken' => $taken
        );
        foreach ($nodes as $key => $value)
        {
            //If the close string exists, then there might be some instances to parse
            if (isset($value['close']))
            {
                $node = array($key, $value['close']);
                $params = array
                (
                    'function' => 'parse',
                    'key' => $key,
                    'pos' => $params['pos'],
                    'repeated' => $params['repeated'],
                    'return' => $return,
                    'strings' => $node,
                    'taken' => $params['taken']
                );
                $params = $this->positions($params);
            }
        }
        return $params['pos'];
    }

    public function positions($params)
    {
        foreach ($params['strings'] as $params['nodekey'] => $params['nodevalue'])
        {
            //If the string has not already been used
            if (!in_array($params['nodevalue'], $params['repeated']))
            {
                $params = $this->positionsloop($params);
                //Make sure this string is not repeated
                $params['repeated'][] = $params['nodevalue'];
            }
        }
        return $params;
    }

    public function positionsloop($params)
    {
        $position = -1;
        //Find the next position of the string
        while (($position = $this->strpos($params['return'], $params['nodevalue'], $position + 1, $params['function'])) !== false)
        {
            $success = true;
            foreach ($params['taken'] as $value)
            {
                //If this string instance is in this reserved range
                if (($position > $value[0] && $position < $value[1]) || ($position + strlen($params['nodevalue']) > $value[0] && $position + strlen($params['nodevalue']) < $value[1]))
                {
                    $success = false;
                    break;
                }
            }
            //If this string instance is not in any reserved range
            if ($success)
            {
                //Add the position
                $params['pos'][$position] = array($params['key'], $params['nodekey']);
                //Reserve all positions taken up by this string instance
                $params['taken'][] = array($position, $position + strlen($params['nodevalue']));
            }
        }
        return $params;
    }

    public function preparse($params)
    {
        $clone = array();
        foreach ($params['ignored'] as $value)
        {
            //If this reserved range is in this case, adjust the range to the removal of the opening string and trimming
            if ($params['open']['position'] < $value[0] && $params['position'] + strlen($params['open']['node']['close']) > $value[1])
            {
                $value[0] += $params['offset'] - strlen($params['open']['open']);
                $value[1] += $params['offset'] - strlen($params['open']['open']);
            }
            $clone[] = $value;
        }
        $params['ignored'] = $clone;
        //Only continue if we are preparsing
        if (!$params['preparse'])
        {
            return $params;
        }
        $clone = array();
        foreach ($params['taken'] as $value)
        {
            $success = true;
            //If this reserved range is in this case
            if ($params['open']['position'] < $value[0] && $params['position'] + strlen($params['open']['node']['close']) > $value[1])
            {
                //If the node does not just strip the opening and closing string, remove the range
                if (!$params['open']['node']['strip'])
                {
                    $success = false;
                }
                //Else, adjust the range to the removal of the opening string and trimming
                else
                {
                    $value[0] += $params['offset'] - strlen($params['open']['open']);
                    $value[1] += $params['offset'] - strlen($params['open']['open']);
                }
            }
            if ($success)
            {
                $clone[] = $value;
            }
        }
        $params['taken'] = $clone;
        //If the node does not just strip the opening and closing string and the case is not empty, reserve the transformed case
        if ((!array_key_exists('strip', $params['open']['node']) || !$params['open']['node']['strip']) && $params['case'])
        {
            $params['taken'][] = array($params['open']['position'], $params['last']);
        }
        return $params;
    }

    public function strpos($haystack, $needle, $offset = 0, $function = NULL)
    {
        if (!is_string($needle))
        {
            $debug = debug_backtrace();
            print $debug[0]['line'];
            print $debug[0]['file'];
        }
        //If a function name was provided, increment the number of times that the function called strpos by 1
        if (isset($function))
        {
            $this->owner->debug['strpos'][$function]['call']++;
        }
        //Find the position insensitively or sensitively based on the configuration
        if ($this->owner->config['flag']['insensitive'])
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
        $clone = array();
        foreach ($params['ignored'] as $value)
        {
            $thissuccess = true;
            //If this ignored node is in this case
            if ($params['open']['position'] < $value[0] && $params['position'] + strlen($params['open']['node']['close']) > $value[1])
            {
                $success = false;
                //If this node should be ignored, remove the original reservation
                if (array_key_exists('ignore', $params['open']['node']) && $params['open']['node']['ignore'])
                {
                    $thissuccess = false;
                }
            }
            if ($thissuccess)
            {
                $clone[] = $value;
            }
        }
        $params['ignored'] = $clone;
        //If the node should not be ignored, and either this does not contain a ignored node or the node strips the opening and closing string, parse
        if ((!array_key_exists('ignore', $params['open']['node']) || !$params['open']['node']['ignore']) && ($success || $params['open']['node']['strip']))
        {
            $params['case'] = substr($params['return'], $params['open']['position'] + strlen($params['open']['open']), $params['position'] - $params['open']['position'] - strlen($params['open']['open']));
            $params['suit'] = $this->owner;
            $params['var'] = $params['open']['node']['var'];
            $params['offset'] = 0;
            //If functions are provided
            if (array_key_exists('function', $params['open']['node']))
            {
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
                $params['offset'] = len($params['open']['open']);
            }
            //Replace everything including and between the opening and closing strings with the transformed string
            $params['return'] = substr_replace($params['return'], $params['case'], $params['open']['position'], $params['position'] + strlen($params['open']['node']['close']) - $params['open']['position']);
            $params['last'] = $params['open']['position'] + strlen($params['case']);
            $params = $this->preparse($params);
        }
        //Else if the node should be ignored, reserve the space
        elseif (array_key_exists('ignore', $params['open']['node']) && $params['open']['node']['ignore'])
        {
            $params['ignored'][] = array($params['open']['position'], $params['position'] + strlen($params['open']['node']['close']));
        }
        return $params;
    }
}
?>