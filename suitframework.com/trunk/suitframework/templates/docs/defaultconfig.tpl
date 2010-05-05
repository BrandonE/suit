<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Transform a string using rules. The function calls tokens, parse, and walk all in one convenient call.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    str suit.execute ( dict config )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="config" href="#config">config</a></legend>
        <p>
            [gettext]The dict to fill.[/gettext]
        </p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <table width="100%" border="1">
        <thead>
            <tr>
                <th>[gettext]Key[/gettext]</th>
                <th>[gettext]Description[/gettext]</th>
                <th>[gettext]Default[/gettext]</th>
            </tr>
        <tbody>
            <tr>
                <td>escape</td>
                <td>[gettext]str: The <a href="[url controller="root" action="template" templatefile="docs" parameter1="escaping" /]">escape</a> string.[/gettext]</td>
                <td>\</td>
            </tr>
            <tr>
                <td>insensitive</td>
                <td>[gettext]bool: Whether or not the searching should be done case insensitively.[/gettext]</td>
                <td>True</td>
            </tr>
            <tr>
                <td>log</td>
                <td>[gettext]bool: Whether or not the execute call should be logged.[/gettext]</td>
                <td>True</td>
            </tr>
            <tr>
                <td>mismatched</td>
                <td>[gettext]bool: Whether or not to parse if the closing string does not match the opening string.[/gettext]</td>
                <td>False</td>
            </tr>
            <tr>
                <td>unclosed</td>
                <td>[gettext]bool: Whether or not the SUIT should walk through the node if it was opened but not closed.[/gettext]</td>
                <td>False</td>
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
            [transform function="pygments" lexer="php"]<?php
require 'suit.class.php';
require 'templating.class.php';
$suit = new SUIT();
$config = array
(
    'escape' => ''
);
$config = $suit->defaultconfig($config);
// Result: array
(
    'unclosed' => false,
    'insensitive' => true,
    'log' => true,
    'mismatched' => false,
    'escape' => ''
)
?>[/transform]
        </fieldset>
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"]import suit
config = {
    'escape': ''
}
config = suit.defaultconfig(config)
# Result: {
#     'unclosed': False,
#     'insensitive': True,
#     'log': True,
#     'mismatched': False,
#     'escape': ''
# }[/transform]
        </fieldset>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="execute" /]">[gettext]execute[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="tokens" /]">[gettext]tokens[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="parse" /]">[gettext]parse[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="walk" /]">[gettext]walk[/gettext]</a></li>
        </ul>
</fieldset>