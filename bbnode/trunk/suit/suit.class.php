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
require 'helper.class.php';

class SUIT
{
    public $cache = array
    (
        'escape' => array(),
        'explodeunescape' => array(),
        'parse' => array()
    );

    public $debug = array
    (
        'gettemplate' => array(),
        'parse' => array(),
        'strpos' => array
        (
            'escape' => array
            (
                'cache' => 0,
                'call' => 0
            ),
            'explodeunescape' => array
            (
                'cache' => 0,
                'call' => 0
            ),
            'parse' => array
            (
                'cache' => 0,
                'call' => 0
            )
        )
    );

    public $filepath = '';

    public $offset = 0;

    public $vars = array();

    public $version = '1.3.4';

    /**
    http://www.suitframework.com/docs/SUIT+Construct
    **/
    public function __construct()
    {
        $this->helper = new Helper($this);
    }

    /**
    http://www.suitframework.com/docs/escape
    **/
    public function escape($strings, $return, $escape = '\\', $insensitive = true)
    {
        $search = array();
        $replace = array();
        //Prepare to escape every string
        foreach ($strings as $value)
        {
            $search[] = $value;
            $replace[] = $escape . $value;
        }
        $cache = md5(md5(serialize($return)) . md5(serialize($strings)));
        //If positions are cached for this case, load them
        if (array_key_exists($cache, $this->cache['escape']))
        {
            $pos = $this->cache['escape'][$cache];
            $this->debug['strpos']['escape']['cache']++;
        }
        else
        {
            $positionstrings = array();
            foreach ($strings as $value)
            {
                $positionstrings[$value] = NULL;
            }
            //Order the strings by the length, descending
            uksort($positionstrings, array('Helper', 'sort'));
            $params = array
            (
                'function' => 'escape',
                'insensitive' => $insensitive,
                'pos' => array(),
                'repeated' => array(),
                'return' => $return,
                'strings' => $positionstrings,
                'taken' => array()
            );
            $pos = $this->helper->positions($params);
            //On top of the strings to be escaped, the last position in the string should be checked for escape strings
            $pos[strlen($return)] = NULL;
            //Order the positions from smallest to biggest
            ksort($pos);
            //Cache the positions
            $this->cache['escape'][$cache] = $pos;
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
            if ($escape)
            {
                //Count how many escape characters are directly to the left of this position
                while (abs($start = $key[$i] - $count - strlen($escape)) == $start && substr($return, $start, strlen($escape)) == $escape)
                {
                    $count += strlen($escape);
                }
                //Determine how many escape strings are directly to the left of this position
                $count = $count / strlen($escape);
            }
            //Replace the escape strings with two escape strings, escaping each of them
            $return = substr_replace($return, str_repeat($escape, $count * 2), $key[$i] - ($count * strlen($escape)), strlen($escape) * $count);
            //Adjust the offset
            $offset += $count * strlen($escape);
        }
        //Escape every string
        return str_replace($search, $replace, $return);
    }

    /**
    http://www.suitframework.com/docs/explodeunescape
    **/
    public function explodeunescape($explode, $string, $escape = '\\', $insensitive = true)
    {
        $return = array();
        $cache = md5(md5(serialize($string)) . md5(serialize($explode)));
        //If positions are cached for this case, load them
        if (array_key_exists($cache, $this->cache['explodeunescape']))
        {
            $pos = $this->cache['explodeunescape'][$cache];
            $this->debug['strpos']['explodeunescape']['cache']++;
        }
        else
        {
            $pos = array();
            $position = -1;
            //Find the next position of the string
            while (($position = $this->helper->strpos($string, $explode, $position + 1, $insensitive, 'explodeunescape')) !== false)
            {
                $pos[] = $position;
            }
            //On top of the explode string to be escaped, the last position in the string should be checked for escape strings
            $pos[] = strlen($string);
            //Cache the positions
            $this->cache['explodeunescape'][$cache] = $pos;
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
            if ($escape)
            {
                //Count how many escape characters are directly to the left of this position
                while (abs($start = $value - $count - strlen($escape)) == $start && substr($string, $start, strlen($escape)) == $escape)
                {
                    $count += strlen($escape);
                }
                //Determine how many escape strings are directly to the left of this position
                $count = $count / strlen($escape);
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
                $string = substr_replace($string, '', $value - (($count / 2) * strlen($escape)), ($count / 2) * strlen($escape));
                //Adjust the value
                $value -= ($count / 2) * strlen($escape);
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

    /**
    http://www.suitframework.com/docs/gettemplate
    **/
    public function gettemplate($return, $code = array(), $label = NULL)
    {
        //Restrict user to the provided directory
        $debug = debug_backtrace();
        $debug = array
        (
            'code' => array(),
            'file' => $debug[0]['file'],
            'label' => $label,
            'line' => $debug[0]['line'],
            'template' => $return
        );
        if (!empty($code))
        {
            foreach ($code as $value)
            {
                //If the code file exists
                if (is_file($value))
                {
                    $debug['code'][] = array($value, true, false);
                    end($debug['code']);
                    $last = key($debug['code']);
                    //Include the code file and set the return value to the modified template
                    $return = $this->helper->includefile($return, $value);
                    $debug['code'][$last][2] = $return;
                }
                else
                {
                    $debug['code'][] = array($value, false, $return);
                }
            }
        }
        //If a label was provided, log this function
        if (isset($label))
        {
            $this->debug['gettemplate'][] = $debug;
        }
        return $return;
    }

    /**
    http://www.suitframework.com/docs/parse
    **/
    public function parse($nodes, $return, $config = array())
    {
        $debug = debug_backtrace();
        $debug = array
        (
            'before' => $return,
            'file' => $debug[0]['file'],
            'line' => $debug[0]['line'],
            'return' => ''
        );
        if (array_key_exists('label', $config))
        {
            $debug['label'] = $config['label'];
        }
        $config = $this->helper->parseconfig($config);
        $cache = $this->helper->parsecache($nodes, $return, $config);
        //If positions are cached for this case, load them
        if (array_key_exists($cache, $this->cache['parse']))
        {
            $pos = $this->cache['parse'][$cache];
            $this->debug['strpos']['parse']['cache']++;
        }
        else
        {
            $pos = $this->helper->parsepositions($nodes, $return, $config['taken'], $config['insensitive']);
            //Order the positions from smallest to biggest
            ksort($pos);
            //Cache the positions
            $this->cache['parse'][$cache] = $pos;
        }
        $preparse = array
        (
            'ignored' => array(),
            'taken' => array()
        );
        $params = array
        (
            'config' => $config,
            'last' => 0,
            'preparse' => array
            (
                'ignored' => array(),
                'nodes' => array(),
                'taken' => array()
            ),
            'skipnode' => array(),
            'skipoffset' => 0,
            'stack' => array()
        );
        $offset = 0;
        $temp = $return;
        $key = array_keys($pos);
        $size = count($key);
        for ($i = 0; $i < $size; $i++)
        {
            //Adjust position to changes in length
            $position = $key[$i] + $offset;
            $params['function'] = true;
            $params['node'] = $pos[$key[$i]][0];
            $params['nodes'] = $nodes;
            $params['offset'] = 0;
            $params['parse'] = true;
            $params['position'] = $position;
            $params['return'] = $return;
            $params['taken'] = true;
            $params['unescape'] = $this->helper->parseunescape($position, $params['config']['escape'], $return);
            $function = 'closingstring';
            //If this is the opening string and it should not be skipped over
            if ($pos[$key[$i]][1] == 0)
            {
                $function = 'openingstring';
            }
            $params = $this->helper->$function($params);
            $return = $params['return'];
            //If the stack is empty
            if (empty($params['stack']))
            {
                //It is impossible that a skipped over node is in another node, so permanently reserve it and start the process over again
                $preparse['ignored'] = array_merge($preparse['ignored'], $params['preparse']['ignored']);
                $params['preparse']['ignored'] = array();
                //If we are preparsing
                if ($params['config']['preparse'])
                {
                    //The ranges can not be inside another node, so permanently reserve it and start the process over again
                    $preparse['taken'] = array_merge($preparse['taken'], $params['preparse']['taken']);
                    $params['preparse']['taken'] = array();
                }
            }
            //Adjust the offset
            $offset = strlen($return) - strlen($temp);
            if (!$params['parse'])
            {
                break;
            }
        }
        $debug['return'] = $return;
        if ($params['config']['preparse'])
        {
            $return = array
            (
                'ignored' => $preparse['ignored'],
                'nodes' => $params['preparse']['nodes'],
                'return' => $return,
                'taken' => $preparse['taken']
            );
            $debug['preparse'] = $preparse;
        }
        //If a label was provided, log this function
        if (array_key_exists('label', $config))
        {
            $this->debug['parse'][] = $debug;
        }
        return $return;
    }

    public function stack($node, $openingstring, $position)
    {
        //Add the opening string to the stack
        $stack = array
        (
            array
            (
                'node' => $node,
                'open' => $openingstring,
                'position' => $position
            )
        );
        $skipnode = array();
        //If the skip key is true, skip over everything between this opening string and its closing string
        if (array_key_exists('skip', $node) && $node['skip'])
        {
            $skipnode[] = $node['close'];
        }
        return array
        (
            'stack' => $stack,
            'skipnode' => $skipnode
        );
    }
}
?>