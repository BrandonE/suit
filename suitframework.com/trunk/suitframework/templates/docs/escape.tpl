<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Handle escape strings for this position.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    dict suit.escape ( str escapestring, int position, str string [, bool insensitive ] )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="escapestring" href="#escapestring">escapestring</a></legend>
        <p>[gettext]The string to check for behind this position.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="position" href="#position">position</a></legend>
        <p>[gettext]The position of the open or close string to check for.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="string" href="#string">string</a></legend>
        <p>[gettext]The full string to check in.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="insensitive" href="#insensitive">insensitive</a></legend>
        <p>[gettext]Whether or not the searching should be done case insensitively.[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <table width="100%" border="1">
        <thead>
            <tr>
                <th>[gettext]Key[/gettext]</th>
                <th>[gettext]Description[/gettext]</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>odd</td>
                <td>[gettext]bool: Whether or not the count of the escape strings to the left of this position is odd, escaping the open or close string.[/gettext]</td>
            </tr>
            <tr>
                <td>position</td>
                <td>[gettext]int: The position adjusted to the change in the string.[/gettext]</td>
            </tr>
            <tr>
                <td>string</td>
                <td>[gettext]str: The string omitting the necessary escape strings.[/gettext]</td>
            </tr>
        </tbody>
    </table>
</fieldset>

<fieldset>
    <legend><a id="examples" href="#examples">[gettext]Examples[/gettext]</a></legend>
    <fieldset>
        <legend><a id="basicusage" href="#basicusage">[gettext]Basic Usage[/gettext]</a></legend>
        <fieldset id="php">
            <legend class="yesscript" style="display: none"><a href="#null" id="phplink">[gettext]PHP - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]PHP[/gettext]</legend>
            [transform function="pygments" lexer="php"][skip]<?php
require 'suit.class.php';
$suit = new SUIT();
$result = $suit->escape('\\', 1, '\[var]');

/*
Result: array
(
    'position' => 0,
    'odd' => 1,
    'string' => '[var]'
)
*/
?>[/skip][/transform]
        </fieldset>
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][skip]import suit
result = suit.escape('\\', 1, '\\[var]')
# Result: {
#     'position': 0,
#     'odd': 1,
#     'string': '[var]'
# }[/skip][/transform]
        </fieldset>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="execute" /]">[gettext]execute[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="parse" /]">[gettext]parse[/gettext]</a></li>
        </ul>
</fieldset>