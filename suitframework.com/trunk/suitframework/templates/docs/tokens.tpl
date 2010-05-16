<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Generate the tokens from the string. Tokens contain the different open and close strings and their positions.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    str suit.tokens ( dict rules, str string [, dict config ] )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="rules" href="#rules">rules</a></legend>
        <p>[gettext]The <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">rules</a> containing the strings to search for.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="string" href="#string">string</a></legend>
        <p>[gettext]The string to find the strings in.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="config" href="#config">config</a></legend>
        <p>
            [gettext]Specifics on how the function should work. (Optional. Default: see <a href="[url controller="root" action="template" templatefile="docs" parameter1="defaultconfig" /]">defaultconfig</a>)[/gettext]
        </p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <p>[gettext]A list of dicts with the following format:[/gettext]</p>
    <table width="100%" border="1">
        <thead>
            <tr>
                <th>[gettext]Key[/gettext]</th>
                <th>[gettext]Description[/gettext]</th>
            </tr>
        <tbody>
            <tr>
                <td>bounds</td>
                <td>
                    [gettext]dict[/gettext]
                    <table width="100%" border="1">
                        <thead>
                            <tr>
                                <th>[gettext]Key[/gettext]</th>
                                <th>[gettext]Description[/gettext]</th>
                            </tr>
                        <tbody>
                            <tr>
                                <td>start</td>
                                <td>[gettext]int: Where the string starts.[/gettext]</td>
                            </tr>
                            <tr>
                                <td>end</td>
                                <td>[gettext]int: Where the string ends.[/gettext]</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>string</td>
                <td>[gettext]str: The located string.[/gettext]</td>
            </tr>
            <tr>
                <td>type</td>
                <td>[gettext]str: The type, options being open, close, or flat.[/gettext]</td>
            </tr>
        </tbody>
    </table>
</fieldset>

<fieldset>
    <legend><a id="examples" href="#examples">[gettext]Examples[/gettext]</a></legend>
    <fieldset>
        <legend><a id="basicusage" href="#basicusage">[gettext]Basic Usage[/gettext]</a></legend>
        <fieldset>
            <legend><a id="basicusagetemplate" href="#basicusagetemplate">[gettext]Template[/gettext]</a></legend>
            [transform function="pygments" lexer="html"]Hello, <strong>[var]username[/var]</strong>![/transform]
        </fieldset>
        <fieldset id="php">
            <legend class="yesscript" style="display: none"><a href="#null" id="phplink">[gettext]PHP - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]PHP[/gettext]</legend>
            [transform function="pygments" lexer="php"]<?php
require 'suit.class.php';
require 'templating.class.php';
$suit = new SUIT();
$templating = new Templating($suit);
$templating->var->username = 'Brandon';
$tokens = $suit->tokens($templating->rules, $template);
/*
Result: array
(
    array
    (
        'bounds' => array
        (
            'end' => 20,
            'start' => 15
        ),
        'string' => '[var]',
        'type' => 'open'
    ),
    array
    (
        'bounds' => array
        (
            'end' => 34,
            'start' => 28
        ),
        'string' => '[/var]',
        'type' => 'close'
    )
)
*/
?>[/transform]
        </fieldset>
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"]import suit
from rulebox import templating # easy_install rulebox
templating.var.username = 'Brandon'
tokens = suit.tokens(templating.rules, template)
# Result: [
#     {
#         'bounds':
#         {
#             'end': 20,
#             'start': 15
#         },
#         'string': '[var]',
#         'type': 'open'
#     },
#     {
#         'bounds':
#         {
#             'end': 34,
#             'start': 28
#         },
#         'string': '[/var]',
#         'type': 'close'
#     }
# ][/transform]
        </fieldset>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">[gettext]Rules[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="execute" /]">[gettext]execute[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="defaultconfig" /]">[gettext]defaultconfig[/gettext]</a></li>
        </ul>
</fieldset>