<a href="#intro" id="intro"><h3>[gettext]What is a Rule?[/gettext]</h3></a>

<p>[gettext]A rule is used to tell SUIT how to transform a string. A collection of rules is called a <em>Ruleset</em>, and a Ruleset is used as a parameter in the <a href="[url controller="root" action="template" templatefile="docs" parameter1="execute" /]">execute</a>, <a href="[url controller="root" action="template" templatefile="docs" parameter1="tokens" /]">tokens</a>, <a href="[url controller="root" action="template" templatefile="docs" parameter1="parse" /]">parse</a>, and <a href="[url controller="root" action="template" templatefile="docs" parameter1="walk" /]">walk</a> functions.[/gettext]</p>

<p>[gettext]The syntax of a rule consists of an open and close string. Once these two strings are correctly matched, its contents are sent to user-defined functions which can transform them and return the final result. Rules can be nested and <a href="[url controller="root" action="template" templatefile="docs" parameter1="escaping" /]">escaped</a>.[/gettext]</p>
<a href="#rulestructure" id="rulestructure"><h3>[gettext]Rule Structure[/gettext]</h3></a>

<table width="100%" border="1">
    <thead>
        <tr>
            <th colspan="3">[gettext]open[/gettext]</th>
        </tr>
        <tr>
            <th>[gettext]Key[/gettext]</th>
            <th>[gettext]Description[/gettext]</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>[gettext]close[/gettext]</td>
            <td>[gettext]str: The close string.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]create[/gettext]</td>
            <td>[gettext]str: The rule this rule creates if applicable.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]functions[/gettext]</td>
            <td>[gettext]list: The functions to run when walking through a node created by this rule.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]priority[/gettext]</td>
            <td>[gettext]int: Used to give the open and close strings of this rule priority over others. (Default: null. The strings will be prioritized by size)[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]skip[/gettext]</td>
            <td>[gettext]bool: Whether or not the parser should skip over the contents of the nodes created by this rule.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]skipescape[/gettext]</td>
            <td>[gettext]bool: If skip is true, whether or not escaping the open or close strings should remove the escape strings. (Default: false)[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]var[/gettext]</td>
            <td>[gettext]mixed: A variable that the functions can use. This key could be named anything, and does not do anything by default. Conventionally, we use this key and have the functions copy it to params.[/gettext]</td>
        </tr>
    </tbody>
</table>

<p>[gettext]Here's an example ruleset containing one rule, lower, which will be used to translate a string to lowercase.[/gettext]</p>

<fieldset id="php">
    <legend class="yesscript" style="display: none"><a href="#null" id="phplink">[gettext]PHP - Click to toggle[/gettext]</a></legend>
    <legend class="noscript">[gettext]PHP[/gettext]</legend>
    [transform function="pygments" lexer="php"]<?php
$rules = array
(
    '[lower]' => array
    (
        'close' => '[/lower]',
        'functions' => array
        (
            array
            (
                'class' => $class,
                'function' => 'lower'
            )
        )
    )
);
?>[/transform]
</fieldset>
<fieldset class="noscript" id="python">
    <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
    <legend class="noscript">[gettext]Python[/gettext]</legend>
    [transform function="pygments" lexer="python"]rules = {
    '[lower]':
    {
        'close': '[/lower]',
        'functions': [lower]
    }
}[/transform]
</fieldset>

<a href="#paramsstructure" id="paramsstructure"><h3>[gettext]Params Structure[/gettext]</h3></a>

<p>[gettext]When SUIT calls the functions provided by the rules, it sends a dict containing parameters. Here is the structure of these params:[/gettext]</p>

<table width="100%" border="1">
    <thead>
        <tr>
            <th>[gettext]Key[/gettext]</th>
            <th>[gettext]Description[/gettext]</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>[gettext]config[/gettext]</td>
            <td>[gettext]dict: The config used for this walk function call.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]rules[/gettext]</td>
            <td>[gettext]dict: The rules used for this walk function call.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]string[/gettext]</td>
            <td>[gettext]str: The string built from these functions.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]tree[/gettext]</td>
            <td>[gettext]dict: This node. When walked through, it can be used to generate the string, and then the other functions could further transform it.[/gettext]</td>
        </tr>
    </tbody>
</table>

<p>[gettext]As these parameters need to be sent between the various functions provided, have each function return these params so that SUIT can send them to the next function. The dict can be modified however the user wants.[/gettext]</p>

<p>[gettext]Below is the lower function mentioned in the above configuration. See how it handles the params.[/gettext]</p>

<fieldset id="php2">
    <legend class="yesscript" style="display: none"><a href="#null" id="phplink2">[gettext]PHP - Click to toggle[/gettext]</a></legend>
    <legend class="noscript">[gettext]PHP[/gettext]</legend>
    [transform function="pygments" lexer="php"]<?php
Class CLASS
{
    public function lower($params)
    {
        $params['string'] = $this->suit->walk($params['rules'], $params['tree'], $params['config']);
        $params['string'] = strtolower($params['string']);
        return $params;
    }
}
?>[/transform]
</fieldset>
<fieldset class="noscript" id="python2">
    <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink2">[gettext]Python - Click to toggle[/gettext]</a></legend>
    <legend class="noscript">[gettext]Python[/gettext]</legend>
    [transform function="pygments" lexer="python"]def lower(params):
    params['string'] = suit.walk(
        params['rules'],
        params['tree'],
        params['config']
    )
    params['string'] = params['string'].lower()
    return params[/transform]
</fieldset>