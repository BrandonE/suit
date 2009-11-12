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
            if (empty($params['skipnode']) && !$this->owner->parseunescape(&$params['position'], &$params['return'], $params['escape']) && !empty($params['stack']))
            {
                $params['open'] = array_pop($params['stack']);
                //If this closing string matches the last node's
                if ($params['open'][0]['close'] == $params['nodes'][$params['node']]['close'])
                {
                    $params = $this->transform($params);
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

    public function includeFile($template, $code)
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
            //If this position should not be overlooked
            if (empty($params['skipnode']) && $skippop === false && !$this->owner->parseunescape(&$params['position'], &$params['return'], $params['escape']))
            {
                //Add the opening string to the stack
                $params['stack'][] = array
                (
                    $params['nodes'][$params['node']],
                    $params['node'],
                    $params['position']
                );
            }
            //If we popped a value or the skip key is true, skip over everything between this opening string and its closing string
            if ($skippop !== false || (array_key_exists('skip', $params['nodes'][$params['node']]) && $params['nodes'][$params['node']]['skip']))
            {
                $params['skipnode'][] = $params['nodes'][$params['node']]['close'];
            }
        }
        //If we popped a value, put it back
        if ($skippop !== false)
        {
            $params['skipnode'][] = $skippop;
        }
        return $params;
    }

    public function offset($params)
    {
        foreach ($params['ignored'] as $key => $value)
        {
            //If this reserved range is in this case, adjust the range to the removal of the opening string and trimming
            if ($params['open'][2] < $value[0] && $params['position'] + strlen($params['open'][0]['close']) > $value[1])
            {
                $params['ignored'][$key][0] += $this->owner->extra['offset'] - strlen($params['open'][1]);
                $params['ignored'][$key][1] += $this->owner->extra['offset'] - strlen($params['open'][1]);
            }
        }
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
            if ($params['open'][2] < $value[0] && $params['position'] + strlen($params['open'][0]['close']) > $value[1])
            {
                //If the node does not just strip the opening and closing string, remove the range
                if (!$params['open'][0]['strip'])
                {
                    $success = false;
                }
                //Else, adjust the range to the removal of the opening string and trimming
                else
                {
                    $value[0] += $this->owner->extra['offset'] - strlen($params['open'][1]);
                    $value[1] += $this->owner->extra['offset'] - strlen($params['open'][1]);
                }
            }
            if ($success)
            {
                $clone[] = $value;
            }
        }
        $params['taken'] = $clone;
        //If the node does not just strip the opening and closing string, reserve the transformed case
        if ((!array_key_exists('strip', $params['open'][0]) || !$params['open'][0]['strip']) && $params['open'][2] != $params['open'][2] + strlen($params['string']))
        {
            $params['taken'][] = array($params['open'][2], $params['open'][2] + strlen($params['string']));
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
            $config['escape'] = $this->config['parse']['escape'];
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
            if ($params['open'][2] < $value[0] && $params['position'] + strlen($params['open'][0]['close']) > $value[1])
            {
                $success = false;
                //If this node should be ignored, remove the original reservation
                if (array_key_exists('ignore', $params['open'][0]) && $params['open'][0]['ignore'])
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
        if ((!array_key_exists('ignore', $params['open'][0]) || !$params['open'][0]['ignore']) && ($success || $params['open'][0]['strip']))
        {
            $params['string'] = substr($params['return'], $params['open'][2] + strlen($params['open'][1]), $params['position'] - $params['open'][2] - strlen($params['open'][1]));
            $this->owner->extra['offset'] = 0;
            $signature = array
            (
                'case' => $params['string'],
                'escape' => $params['escape'],
                'nodes' => $params['nodes'],
                'suit' => $this->owner,
                'var' => $params['open'][0]['var']
            );
            //If a function is provided
            if (array_key_exists('function', $params['open'][0]))
            {
                //Transform the string in between the opening and closing strings. Note whether or not the function is in a class. If the function uses params, send them
                if (array_key_exists('class', $params['open'][0]))
                {
                    if (!array_key_exists('params', $params['open'][0]) || $params['open'][0]['params'])
                    {
                        $params['string'] = $params['open'][0]['class']->$params['open'][0]['function']($signature);
                    }
                    else
                    {
                        $params['string'] = $params['open'][0]['class']->$params['open'][0]['function']();
                    }
                }
                else
                {
                        if (!array_key_exists('params', $params['open'][0]) || $params['open'][0]['params'])
                        {
                            $params['string'] = $params['open'][0]['function']($signature);
                        }
                        else
                        {
                            $params['string'] = $params['open'][0]['function']();
                        }
                }
            }
            else
            {
                //Replace the opening and closing strings
                $params['open'][1] . $params['string'] . $params['open'][0]['close'];
            }
            //Replace everything including and between the opening and closing strings with the transformed string
            $params['return'] = substr_replace($params['return'], $params['string'], $params['open'][2], $params['position'] + strlen($params['open'][0]['close']) - $params['open'][2]);
            $params = $this->offset($params);
        }
        //Else if the node should be ignored, reserve the space
        elseif (array_key_exists('ignore', $params['open'][0]) && $params['open'][0]['ignore'])
        {
            $params['ignored'][] = array($params['open'][2], $params['position'] + strlen($params['open'][0]['close']));
        }
        return $params;
    }
}
?>