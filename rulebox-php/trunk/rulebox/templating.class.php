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

A set of rules used to transfer information from the code to the template in
order to create an HTML document.

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

Basic usage; see http://www.suitframework.com/docs/ for how to use other rules.

-----------------------------
Var and Rules
-----------------------------

``var``
    obj - Container of variables to be used in with various rules.

``rules``
    dict - Contains the rules for the Templating Ruleset.
**/

class Templating
{
    public $default = array
    (
        'delimiter' => '.',
        'equal' => '=',
        'log' => false,
        'quote' => array('"', '\'')
    );

    public function __construct($suit)
    {
        $this->var = new stdClass();

        $this->default['owner'] = &$this->var;

        $this->suit = $suit;

        $this->rules = array
        (
            '[' => array
            (
                'close' => ']',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'bracket'
                    )
                )
            ),
            '[assign]' => array
            (
                'close' => '[/assign]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'assign'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('json', 'var'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'decode' => array('json'),
                        'delimiter' => $this->default['delimiter'],
                        'json' => 'false',
                        'owner' => $this->default['owner']
                    )
                )
            ),
            '[assign' => array
            (
                'close' => ']',
                'create' => '[assign]',
                'skip' => true
            ),
            '[call' => array
            (
                'close' => '/]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'functions'
                    )
                ),
                'skip' => true,
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'log' => $this->default['log'],
                    'onesided' => true,
                    'quote' => $this->default['quote'],
                    array
                    (
                        'function' => ''
                    )
                )
            ),
            '[comment]' => array
            (
                'close' => '[/comment]',
                'skip' => true
            ),
            '[entities]' => array
            (
                'close' => '[/entities]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'copyvar'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'entities'
                    )
                ),
                'var' => array
                (
                    'entities' => true,
                    'json' => false
                )
            ),
            '[execute]' => array
            (
                'close' => '[/execute]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'execute'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('log'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'condition' => 'false',
                        'decode' => array('log'),
                        'log' => 'true'
                    )
                )
            ),
            '[execute' => array
            (
                'close' => ']',
                'create' => '[execute]',
                'skip' => true
            ),
            '[if]' => array
            (
                'close' => '[/if]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'condition'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('condition', 'not'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'decode' => array('not'),
                        'delimiter' => $this->default['delimiter'],
                        'not' => 'false',
                        'owner' => $this->default['owner']
                    )
                )
            ),
            '[if' => array
            (
                'close' => ']',
                'create' => '[if]',
                'skip' => true
            ),
            '[local]' => array
            (
                'close' => '[/local]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'copyvar'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'savelocal'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'loadlocal'
                    )
                ),
                'var' => array
                (
                    'owner' => $this->default['owner']
                )
            ),
            '[loop]' => array
            (
                'close' => '[/loop]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'loop'
                    )
                ),
                'var' => array
                (
                    'blacklist' => true,
                    'equal' => $this->default['equal'],
                    'list' => array('delimiter', 'owner'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'delimiter' => $this->default['delimiter'],
                        'join' => '',
                        'owner' => $this->default['owner']
                    )
                )
            ),
            '[loop' => array
            (
                'close' => ']',
                'create' => '[loop]',
                'skip' => true
            ),
            '[return' => array
            (
                'close' => '/]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'returning'
                    )
                ),
                'skip' => true,
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('layers'),
                    'log' => $this->default['log'],
                    'onesided' => true,
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'decode' => array('layers'),
                        'layers' => 'true'
                    )
                )
            ),
            '[skip]' => array
            (
                'close' => '[/skip]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    )
                ),
                'skip' => true,
                'skipescape' => true
            ),
            '[template]' => array
            (
                'close' => '[/template]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'copyvar'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'templates'
                    )
                ),
                'var' => array()
            ),
            '[transform]' => array
            (
                'close' => '[/transform]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'transform'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'functions'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'function' => '',
                        'string' => ''
                    )
                )
            ),
            '[transform' => array
            (
                'close' => ']',
                'create' => '[transform]',
                'skip' => true
            ),
            '[trim]' => array
            (
                'close' => '[/trim]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'trim'
                    )
                )
            ),
            '[trim' => array
            (
                'close' => ']',
                'create' => '[trim]',
                'skip' => true
            ),
            '[try]' => array
            (
                'close' => '[/try]',
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'trying'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('var'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'delimiter' => $this->default['delimiter'],
                        'owner' => $this->default['owner']
                    )
                )
            ),
            '[try' => array
            (
                'close' => ']',
                'create' => '[try]',
                'skip' => true
            ),
            '[var]' => array
            (
                'close' => '[/var]',
                'class' => $this,
                'functions' => array
                (
                    array
                    (
                        'class' => $this,
                        'function' => 'walk'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'attribute'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'decode'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'variables'
                    ),
                    array
                    (
                        'class' => $this,
                        'function' => 'entities'
                    )
                ),
                'var' => array
                (
                    'equal' => $this->default['equal'],
                    'list' => array('entities', 'json'),
                    'log' => $this->default['log'],
                    'quote' => $this->default['quote'],
                    'var' => array
                    (
                        'decode' => array('entities', 'json'),
                        'delimiter' => $this->default['delimiter'],
                        'entities' => 'true',
                        'json' => 'false',
                        'owner' => $this->default['owner']
                    )
                )
            ),
            '[var' => array
            (
                'close' => ']',
                'create' => '[var]',
                'skip' => true
            )
        );
    }

    public function assign($params)
    {
        // Assign variable in template.
        // If a variable is provided.
        if (array_key_exists('var', $params['var']))
        {
            if ($params['var']['json'])
            {
                $params['string'] = json_decode($params['string'], true);
            }
            $this->setvariable(
                $params['var']['var'],
                $params['var']['delimiter'],
                $params['string'],
                $params['var']['owner']
            );
        }
        $params['string'] = '';
        return $params;
    }

    public function attribute($params)
    {
        // Create rule out of attributes.
        $variable = $params['rules'][$params['tree']['rule']]['var'];
        $params['var'] = $variable['var'];
        // Decide where to get the attributes from.
        if (array_key_exists('onesided', $variable) && $variable['onesided'])
        {
            $string = $params['string'];
        }
        elseif (array_key_exists('create', $params['tree']))
        {
            $string = $params['tree']['create'];
        }
        else
        {
            return $params;
        }
        $quote = '';
        $smallest = false;
        $function = 'strpos';
        if ($params['config']['insensitive'])
        {
            $function = 'stripos';
        }
        // Decide which quote string to use based on which occurs first.
        foreach ($variable['quote'] as $value)
        {
            $position = $function($string, $value);
            if (
                $position !== false &&
                (
                    $smallest === false || $position < $smallest
                )
            )
            {
                $quote = $value;
                $smallest = $position;
            }
        }
        if ($quote)
        {
            // Split up the string by quotes.
            $split = explode($quote, $string);
            unset($split[count($split) - 1]);
            foreach ($split as $key => $value)
            {
                // If this is the opening quote.
                if ($key % 2 == 0)
                {
                    $name = trim($value);
                    $syntax = (
                        substr(
                            $name, strlen($name) - strlen($variable['equal'])
                        ) == $variable['equal']
                    );
                    $name = substr_replace(
                        $name, '', strlen($name) - strlen($variable['equal'])
                    );
                    /*
                    If the syntax is not valid or the variable is not
                    whitelisted or blacklisted, do not prepare to define the
                    variable.
                    */
                    if (!$syntax || !$this->listing($name, $variable))
                    {
                        $name = '';
                    }
                }
                elseif ($name)
                {
                    // Define the variable.
                    $config = $params['config'];
                    $config['log'] = $variable['log'];
                    $params['var'][$name] = $this->suit->execute(
                        $params['rules'], $value, $config
                    );
                }
            }
        }
        return $params;
    }

    public function bracket($params)
    {
        // Handle brackets unrelated to the rules.
        $params['string'] = $params['tree']['rule'] . $params[
            'string'
        ] . $params['rules'][$params['tree']['rule']]['close'];
        return $params;
    }

    public function condition($params)
    {
        // Show the string if necessary.
        // Do not show if no condition provided.
        if (!array_key_exists('condition', $params['var']))
        {
            return $params;
        }
        $variable = $this->getvariable(
            $params['var']['condition'],
            $params['var']['delimiter'],
            $params['var']['owner']
        );
        // Show the string if the condition is true.
        if (
            (
                $variable && !$params['var']['not']
            ) ||
            (
                !$variable && $params['var']['not']
            )
        )
        {
            $params = $this->walk($params);
        }
        return $params;
    }

    public function copyvar($params)
    {
        // Copy the rule's variable from the tree.
        $params['var'] = $params['rules'][$params['tree']['rule']]['var'];
        return $params;
    }

    public function decode($params)
    {
        // Decode a JSON String.
        foreach ($params['var']['decode'] as $value)
        {
            $params['var'][$value] = json_decode($params['var'][$value], true);
        }
        return $params;
    }

    public function entities($params)
    {
        // Convert HTML characters to their respective entities.
        if (!$params['var']['json'] && $params['var']['entities'])
        {
            $params['string'] = htmlentities(strval($params['string']));
        }
        return $params;
    }

    public function execute($params)
    {
        // Execute the string using the same rules used in this template.
        $config = $params['config'];
        $config['log'] = $params['var']['log'];
        $params['string'] = $this->suit->execute(
            $params['rules'], $params['string'], $config
        );
        return $params;
    }

    public function functions($params)
    {
        // Perform a function call.
        /*
        If the node using this is one sided, make the string empty by default.
        */
        if (
            array_key_exists('onesided', $params['var']) &&
            $params['var']['onesided']
        )
        {
            $params['string'] = '';
        }
        // If a function was provided.
        if ($params['var']['function'] && $params['var']['owner'])
        {
            $kwargs = $params['var'];
            // Remove the parameters that shouldn't be used in the call.
            unset($kwargs['function']);
            unset($kwargs['owner']);
            // Note whether or not the function is in a class
            if (array_key_exists('owner', $params['var']))
            {
                $params['string'] = $params['var']['owner']->$params['var'][
                    'function'
                ]($kwargs);
            }
            else
            {
                $params['string'] = $params['var']['function']($kwargs);
            }
        }
        return $params;
    }

    public function getvariable($string, $split, $owner)
    {
        /*
        Get a variable based on a split string.

        ``string``
            str - The name of the variable to grab.

        ``split``
            str - The string that separates the levels of the variable.

        ``owner``
            mixed - The object to grab the variable from.

        Returns: mixed - The variable.
        */
        foreach (explode($split, $string) as $value)
        {
            if (is_array($owner))
            {
                $owner = $owner[$value];
            }
            else
            {
                $owner = $owner->$value;
            }
        }
        return $owner;
    }

    public function listing($name, $variable)
    {
        /*
        Check if the variable is whitelisted or blacklisted and determine
        whether or not the variable can be used.

        ``name``
            str - The name of the variable to check.

        ``variable``
            dict - A dict containing the `list` and `blacklist` keys if
            applicable.

        Returns: bool - Whether or not the variable can be used.
        */
        return (
            !(
                array_key_exists('list', $variable) &&
                (
                    (
                        (
                            !array_key_exists('blacklist', $variable) ||
                            !$variable['blacklist']
                        ) && !in_array($name, $variable['list'])
                    ) ||
                    (
                        array_key_exists('blacklist', $variable) &&
                        $variable['blacklist'] &&
                        in_array($name, $variable['list'])
                    )
                )
            )
        );
    }

    public function loadlocal($params)
    {
        // Reset the variables set before this section.
        // Set the variables.
        foreach ($params['var']['local'] as $key => $value)
        {
            if (is_array($params['var']['owner']))
            {
                $params['var']['owner'][$key] = $value;
            }
            else
            {
                $params['var']['owner']->$key = $value;
            }
        }
        // Remove the variables set after this section.
        foreach ($params['var']['owner'] as $key => $value)
        {
            if (!array_key_exists($key, $params['var']['local']))
            {
                if (is_array($params['var']['owner']))
                {
                    unset($params['var']['owner'][$key]);
                }
                else
                {
                    unset($params['var']['owner']->$key);
                }
            }
        }
        return $params;
    }

    public function loop($params)
    {
        // Loop a string with different variables.
        // Do not loop if no iterable provided.
        if (!array_key_exists('iterable', $params['var']))
        {
            return $params;
        }
        $variable = $this->getvariable(
            $params['var']['iterable'],
            $params['var']['delimiter'],
            $params['var']['owner']
        );
        # Remove the rule from the tree.
        $tree = array
        (
            'closed' => true,
            'contents' => $params['tree']['contents']
        );
        $iterations = array();
        foreach ($variable as $key => $value)
        {
            // Set the key variable if provided.
            if (array_key_exists('key', $params['var']))
            {
                $this->setvariable(
                    $params['var']['key'],
                    $params['var']['delimiter'],
                    $key,
                    $params['var']['owner']
                );
            }
            // Set the value variable if provided.
            if (array_key_exists('value', $params['var']))
            {
                $this->setvariable(
                    $params['var']['value'],
                    $params['var']['delimiter'],
                    $value,
                    $params['var']['owner']
                );
            }
            // Walk for this iteration.
            $result = $this->walk($params);
            $iterations[] = $result['string'];
        }
        // Join the iterations.
        $params['string'] = implode($params['var']['join'], $iterations);
        return $params;
    }

    public function returning($params)
    {
        // Prepare to return from this point on.
        $params['string'] = '';
        // If no more layers should be returned out of, don't.
        if (!$params['var']['layers'])
        {
            return $params;
        }
        /*
        Decrement the amount of layers to return out of if a limit was defined.
        */
        if (is_int($params['var']['layers']))
        {
            $params['var']['layers'] -= 1;
        }
        // Delete every node after this one.
        $limit = $params['tree']['key'] + 1;
        $length = count($params['tree']['parent']['contents']);
        while ($length > $limit)
        {
            unset($params['tree']['parent']['contents'][$length]);
            $length--;
        }
        // If this node was nested, attempt to return out of its parent.
        if (
            $params['var']['layers'] &&
            array_key_exists('parent', $params['tree']['parent'])
        )
        {
            $params['tree']['parent'] = &$params['tree']['parent']['parent'];
            $params = $this->returning($params);
        }
        return $params;
    }

    public function savelocal($params)
    {
        // Save the variables set before this section.
        $params['var']['local'] = array();
        foreach ($params['var']['owner'] as $key => $value)
        {
            $params['var']['local'][$key] = $value;
        }
        return $params;
    }

    public function setvariable($string, $split, $assignment, &$owner)
    {
        /*
        Set a variable based on a split string.

        ``string``
            str - The name of the variable to set.

        ``split``
            str - The string that separates the levels of the variable.

        ``assignment``
            mixed - The value to assign to the variable.

        ``owner``
            mixed - The object to set the variable on.

        Returns: void - Nothing. The variable is modified.
        */
        $split = explode($split, $string);
        foreach ($split as $key => $value)
        {
            if ($key < count($split) - 1)
            {
                if (is_array($owner))
                {
                    $owner = &$owner[$value];
                }
                else
                {
                    $owner = &$owner->$value;
                }
            }
        }
        if (is_array($owner))
        {
            $owner[$split[count($split) - 1]] = $assignment;
        }
        else
        {
            $owner->$split[count($split) - 1] = $assignment;
        }
    }

    public function templates($params)
    {
        // Grab the unparsed contents of a template file.
        // If the variable is not whitelisted or blacklisted.
        if ($this->listing($params['string'], $params['var']))
        {
            $params['string'] = file_get_contents(
                str_replace(
                    '../', '', str_replace('..\'', '', $params['string'])
                )
            );
        }
        else
        {
            $params['string'] = '';
        }
        return $params;
    }

    public function transform($params)
    {
        // Send string as an argument for functions.
        $params['var']['string'] = $params['string'];
        return $params;
    }

    public function trim($params)
    {
        // Trim unnecessary whitespace.
        $trimrules = array
        (
            '<pre' => array
            (
                'close' => '</pre>',
                'skip' => true
            ),
            '<textarea' => array
            (
                'close' => '</textarea>',
                'skip' => true
            )
        );
        $pos = $this->suit->tokens(
            $trimrules, $params['string'], $params['config']
        );
        $tree = $this->suit->parse(
            $trimrules, $pos, $params['string'], $params['config']
        );
        $tree = $tree['contents'];
        $params['string'] = '';
        foreach ($tree as $value)
        {
            /*
            If this node is a tag we do not want to trim the contents of, put
            the statement back.
            */
            if (is_array($value))
            {
                $params['string'] .= $value['rule'] . $value['contents'][
                    0
                ] . $trimrules[$value['rule']]['close'];
            }
            // Else, trim it.
            else
            {
                $params['string'] .= preg_replace(
                    '/[\s]+$/m', '', $value
                ) . substr($value, strlen(rtrim($value)));
            }
        }
        // Remove the whitespace preceding the string.
        $params['string'] = ltrim($params['string']);
        return $params;
    }

    public function trying($params)
    {
        // Try to walk and handle exceptions.
        // If a variable is provided.
        if (array_key_exists('var', $params['var']))
        {
            $this->setvariable(
                $params['var']['var'],
                $params['var']['delimiter'],
                '',
                $params['var']['owner']
            );
        }
        // Try to walk through this node.
        try
        {
            $params['string'] = $this->suit->walk(
                $params['rules'], $params['tree'], $params['config']
            );
        }
        // Catch all exceptions.
        catch (Exception $e)
        {
            // If a variable is provided.
            if (array_key_exists('var', $params['var']))
            {
                $this->setvariable(
                    $params['var']['var'],
                    $params['var']['delimiter'],
                    $e,
                    $params['var']['owner']
                );
            }
            // Collapse the node.
            $params['string'] = '';
        }
        return $params;
    }

    public function variables($params)
    {
        // Grab a variable.
        $params['string'] = $this->getvariable(
            $params['string'],
            $params['var']['delimiter'],
            $params['var']['owner']
        );
        if ($params['var']['json'])
        {
            $params['string'] = json_encode($params['string']);
        }
        return $params;
    }

    public function walk($params)
    {
        // Walk through this node.
        $params['string'] = $this->suit->walk(
            $params['rules'], $params['tree'], $params['config']
        );
        return $params;
    }
}
?>