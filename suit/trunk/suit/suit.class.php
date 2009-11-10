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
require 'helper.class.php';
require 'section.class.php';
require 'nodes.class.php';

class SUIT
{
    public $config = array();

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

    public $error = '';

    public $extra = array
    (
        'cache' => array
        (
            'escape' => array(),
            'explodeunescape' => array(),
            'parse' => array()
        ),
        'chain' => array(),
        'offset' => 0,
        'sections' => array()
    );

    public $filepath = '';

    public $offset = 0;

    public $vars = array();

    public $version = '1.3.4';

    /**
    http://www.suitframework.com/docs/SUIT+Construct
    **/
    public function __construct($config)
    {
        $this->helper = new Helper($this);
        $this->section = new Section($this);
        $this->nodes = new Nodes($this);
        $this->config = $config;
        if (phpversion() <= '4.4.9')
        {
            $this->error('PHP Version must be greater than 4.4.9');
        }
    }

    public function __destruct()
    {
        //Print all the errors. Printing on error would cause the page to break as typically content is printed at the end of the script
        echo $this->error;
    }

    public function error($error, $plain = NULL, $type = 'Error', $key = 1)
    {
        $plain = strval(isset($plain) ?
            $plain :
            $error);
        if (ini_get('error_reporting'))
        {
            $backtrace = debug_backtrace();
            //Add a PHP styled error to the string
            $this->error .= '<br />
<b>SUIT ' . $type . '</b>:  ' . $error . ' in <b>' . $backtrace[$key]['file'] . '</b> on line <b>' . $backtrace[$key]['line'] . '</b><br />';
            //Log the HTML free error
            error_log('SUIT ' . $type . ':  ' . $plain . ' in ' . $backtrace[$key]['file'] . ' on line ' . $backtrace[$key]['line']);
        }
        //If it is an error and not a warning, exit the script
        if ($type == 'Error')
        {
            exit;
        }
    }

    /**
    http://www.suitframework.com/docs/escape
    **/
    public function escape($strings, $return, $escape = NULL)
    {
        $escape = strval($escape);
        $return = strval($return);
        $escape = strval((isset($escape)) ?
            $escape :
            $this->config['parse']['escape']);
        $search = array();
        $replace = array();
        if (is_array($strings))
        {
            //Prepare to escape every string
            foreach ($strings as $value)
            {
                $search[] = $value;
                $replace[] = $escape . $value;
            }
            $cache = md5(md5(serialize($return)) . md5(serialize($strings)));
            //If positions are cached for this case, load them
            if (array_key_exists($cache, $this->extra['cache']['escape']))
            {
                $pos = $this->extra['cache']['escape'][$cache];
                $this->debug['strpos']['escape']['cache']++;
            }
            else
            {
                //Order the strings by length, descending
                rsort($strings);
                $params = array
                (
                    'function' => 'escape',
                    'key' => NULL,
                    'pos' => array(),
                    'repeated' => array(),
                    'return' => $return,
                    'strings' => $strings,
                    'taken' => array()
                );
                $result = $this->helper->positions($params);
                $pos = $result['pos'];
                //On top of the strings to be escaped, the last position in the string should be checked for escape strings
                $pos[strlen($return)] = NULL;
                //Order the positions from smallest to biggest
                ksort($pos);
                //Cache the positions
                $this->extra['cache']['escape'][$cache] = $pos;
            }
            $offset = 0;
            foreach ($pos as $key => $value)
            {
                //Adjust position to changes in length
                $key += $offset;
                $count = 0;
                //If the escape string is not empty
                if ($escape)
                {
                    //Count how many escape characters are directly to the left of this position
                    while (abs($start = $key - $count - strlen($escape)) == $start && substr($return, $start, strlen($escape)) == $escape)
                    {
                        $count += strlen($escape);
                    }
                    //Determine how many escape strings are directly to the left of this position
                    $count = $count / strlen($escape);
                }
                //Replace the escape strings with two escape strings, escaping each of them
                $return = substr_replace($return, str_repeat($escape, $count * 2), $key - ($count * strlen($escape)), strlen($escape) * $count);
                //Adjust the offset
                $offset += $count * strlen($escape);
            }
        }
        else
        {
            $this->error('Provided argument not array or improperly formatted one', NULL, 'Warning');
        }
        //Escape every string
        return str_replace($search, $replace, $return);
    }

    /**
    http://www.suitframework.com/docs/explodeunescape
    **/
    public function explodeunescape($explode, $glue, $escape = NULL)
    {
        $return = array();
        $explode = strval($explode);
        $glue = strval($glue);
        $escape = strval((isset($escape)) ?
            $escape :
            $this->config['parse']['escape']);
        $cache = md5(md5(serialize($glue)) . md5(serialize($explode)));
        //If positions are cached for this case, load them
        if (array_key_exists($cache, $this->extra['cache']['explodeunescape']))
        {
            $pos = $this->extra['cache']['explodeunescape'][$cache];
            $this->debug['strpos']['explodeunescape']['cache']++;
        }
        else
        {
            $pos = array();
            $position = -1;
            //Find the next position of the string
            while (($position = $this->helper->strpos($glue, $explode, $position + 1, 'explodeunescape')) !== false)
            {
                $pos[] = $position;
            }
            //On top of the explode string to be escaped, the last position in the string should be checked for escape strings
            $pos[] = strlen($glue);
            //Cache the positions
            $this->extra['cache']['explodeunescape'][$cache] = $pos;
        }
        $offset = 0;
        $last = 0;
        $temp = $glue;
        foreach ($pos as $value)
        {
            //Adjust position to changes in length
            $value += $offset;
            $count = 0;
            //If the escape string is not empty
            if ($escape)
            {
                //Count how many escape characters are directly to the left of this position
                while (abs($start = $value - $count - strlen($escape)) == $start && substr($glue, $start, strlen($escape)) == $escape)
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
                $glue = substr_replace($glue, '', $value - (($count / 2) * strlen($escape)), ($count / 2) * strlen($escape));
                //Adjust the value
                $value -= ($count / 2) * strlen($escape);
            }
            //If the number of escape strings directly to the left of this position are even
            if (!$condition)
            {
                //This separator is not overlooked, so append the accumulated value to the return array
                $return[] = substr($glue, $last, $value - $last);
                //Make sure not to include anything we appended in a future value
                $last = $value + strlen($explode);
            }
            //Adjust the offset
            $offset = strlen($glue) - strlen($temp);
        }
        return $return;
    }

    /**
    http://www.suitframework.com/docs/gettemplate
    **/
    public function gettemplate($template)
    {
        //Restrict user to the provided directory
        $search = array('../', '..\\');
        $template = str_replace($search, '', strval($template));
        $return = '';
        $backtrace = debug_backtrace();
        //Log this function
        $this->debug['gettemplate'][] = array
        (
            'code' => array(),
            'content' => array(false, false, false),
            'file' => $backtrace[0]['file'],
            'glue' => array($template, true, false),
            'line' => $backtrace[0]['line']
        );
        end($this->debug['gettemplate']);
        $last = key($this->debug['gettemplate']);
        $this->filepath = $this->config['files']['glue'] . '/' . $template . '.txt';
        //If this template will cause an infinite loop, show an error and log it
        if (in_array($template, $this->extra['chain']))
        {
            $this->error('Infinite Loop Caused by <pre>' . htmlspecialchars($template) . '</pre>', 'Infinite Loop Caused by "' . $template . '"', 'Warning');
            $this->debug['gettemplate'][$key]['glue'][2] = true;
        }
        //Else if the glue file does not exist, show an error and log it
        elseif (!is_file($this->filepath))
        {
            $this->error('The following template could not be found:<pre>' . htmlspecialchars($template) . '</pre>', 'The following template could not be found: "' . $template . '"', 'Warning');
            $this->debug['gettemplate'][$key]['glue'][1] = false;
        }
        else
        {
            //Split up the file, paying attention to escape strings
            $array = $this->explodeunescape('=', file_get_contents($this->filepath), '\\');
            //Prevent this template from being used again until it is finished
            $this->extra['chain'][] = $template;
            foreach ($array as $key => $value)
            {
                //If this is the content file
                if ($key == 0)
                {
                    $this->filepath = $this->config['files']['content'] . '/' . str_replace($search, '', $value) . '.tpl';
                    //If the content file exists
                    if (is_file($this->filepath))
                    {
                        //Set the return value to the contents of the content file
                        $return = file_get_contents($this->filepath);
                        $this->debug['gettemplate'][$last]['content'] = array($value, true, $return);
                    }
                    else
                    {
                        $this->debug['gettemplate'][$last]['content'] = array($value, false, $return);
                    }
                }
                else
                {
                    $this->filepath = $this->config['files']['code'] . '/' . str_replace($search, '', $value) . '.inc.php';
                    //If the code file exists
                    if (is_file($this->filepath))
                    {
                        $this->debug['gettemplate'][$last]['code'][] = array($value, true, false);
                        end($this->debug['gettemplate'][$last]['code']);
                        $last2 = key($this->debug['gettemplate'][$last]['code']);
                        //Include the code file and set the return value to the modified content
                        $return = $this->helper->includeFile($return);
                        $this->debug['gettemplate'][$last]['code'][$last2][2] = $return;
                    }
                    else
                    {
                        $this->debug['gettemplate'][$key]['code'][] = array($value, false, $return);
                    }
                }
            }
            //This template can be used again
            array_pop($this->extra['chain']);
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
            'errors' => false,
            'file' => $debug[0]['file'],
            'line' => $debug[0]['line'],
            'return' => ''
        );
        if (array_key_exists('label', $config))
        {
            $debug['label'] = $config['label'];
        }
        $return = strval($return);
        if (is_array($nodes) && is_array($config))
        {
            $config = $this->helper->parseconfig($config);
            //Order the nodes by the length of the opening string, descending
            krsort($nodes);
            $cache = $this->helper->parsecache($nodes, $return, $config);
            //If positions are cached for this case, load them
            if (array_key_exists($cache, $this->extra['cache']['parse']))
            {
                $pos = $this->extra['cache']['parse'][$cache];
                $this->debug['strpos']['parse']['cache']++;
            }
            else
            {
                $pos = $this->helper->parsepositions($nodes, $return, $config['taken']);
                //Order the positions from smallest to biggest
                ksort($pos);
                //Cache the positions
                $this->extra['cache']['parse'][$cache] = $pos;
            }
            $preparse = array
            (
                'taken' => array(),
                'ignored' => array()
            );
            $params = array
            (
                'ignored' => array(),
                'skipnode' => array(),
                'stack' => array(),
                'taken' => array()
            );
            $offset = 0;
            $temp = $return;
            foreach ($pos as $key => $value)
            {
                //Adjust position to changes in length
                $key += $offset;
                $params = array
                (
                    'escape' => $config['escape'],
                    'ignored' => $params['ignored'],
                    'node' => $value[0],
                    'nodes' => $nodes,
                    'position' => $key,
                    'preparse' => $config['preparse'],
                    'return' => $return,
                    'skipnode' => $params['skipnode'],
                    'stack' => $params['stack'],
                    'taken' => $params['taken'],
                );
                $function = 'closingstring';
                //If this is the opening string and it should not be skipped over
                if ($value[1] == 0)
                {
                    $function = 'openingstring';
                }
                $params = $this->helper->$function($params);
                $return = $params['return'];
                //If the stack is empty
                if (empty($params['stack']))
                {
                    //It is impossible that a skipped over node is in another node
                    $preparse['ignored'] = array_merge($preparse['ignored'], $params['ignored']);
                    $params['ignored'] = array();
                    //If we are preparsing
                    if ($config['preparse'])
                    {
                        //The ranges can not be inside another node, so permanently reserve it and start the process over again
                        $preparse['taken'] = array_merge($preparse['taken'], $params['taken']);
                        $params['taken'] = array();
                    }
                }
                //Adjust the offset
                $offset = strlen($return) - strlen($temp);
            }
            $debug['return'] = $return;
            if ($config['preparse'])
            {
                $return = array
                (
                    'return' => $return,
                    'taken' => $preparse['taken']
                );
                if (array_key_exists('label', $config))
                {
                    $debug['preparse'] = $this->helper->debugpreparse($preparse['taken'], $preparse['ignored'], $return['return']);
                }
            }
        }
        else
        {
            $this->error('Provided argument not array or improperly formatted one', NULL, 'Warning');
            $debug['errors'] = true;
        }
        //If a label was provided, log this function
        if (array_key_exists('label', $config))
        {
            $this->debug['parse'][] = $debug;
        }
        return $return;
    }

    /**
    http://www.suitframework.com/docs/parseunescape
    **/
    public function parseunescape($pos, $content, $escape = NULL)
    {
        $pos = intval($pos);
        $content = strval($content);
        $escape = strval((isset($escape)) ?
            $escape :
            $this->config['parse']['escape']);
        $count = 0;
        //If the escape string is not empty
        if ($escape)
        {
            //Count how many escape characters are directly to the left of this position
            while (abs($start = $pos - $count - strlen($escape)) == $start && substr($content, $start, strlen($escape)) == $escape)
            {
                $count += strlen($escape);
            }
            //Determine how many escape strings are directly to the left of this position
            $count = $count / strlen($escape);
        }
        //If the number of escape strings directly to the left of this position are odd, the position should be overlooked
        $condition = $count % 2;
        //If the number of escape strings directly to the left of this position are odd, (x + 1) / 2 of them should be removed
        if ($condition)
        {
            $count++;
        }
        //Adjust the position
        $pos -= strlen($escape) * ($count / 2);
        //Remove the decided number of escape strings
        $content = substr_replace($content, '', $pos, strlen($escape) * ($count / 2));
        return $condition;
    }
}
?>