<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Handle the closing of a rule.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    dict suit.close ( dict rules, str append, dict pop, list tree )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="rules" href="#rules">rules</a></legend>
        <p>[gettext]The <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">rules</a> used to determine how to add the string.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="append" href="#append">append</a></legend>
        <p>[gettext]The string to add.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="pop" href="#pop">pop</a></legend>
        <p>[gettext]The last item of the tree's contents.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="tree" href="#tree">tree</a></legend>
        <p>[gettext]The contents of the tree.[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <p>[gettext]A dict with the following format:[/gettext]</p>
    <table width="100%" border="1">
        <thead>
            <tr>
                <th>[gettext]Key[/gettext]</th>
                <th>[gettext]Description[/gettext]</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>skip</td>
                <td>[gettext]str: The skip rule, if opened.[/gettext]</td>
            </tr>
            <tr>
                <td>tree</td>
                <td>[gettext]list: The contents of the tree with the appended data.[/gettext]</td>
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
require 'templating.class.php';
$suit = new SUIT();
$templating = new Templating($suit);
tree = array
(
    array
    (
        'contents' => array(),
        'rule' => '[var]'
    )
);
$pop = array_pop(tree);
$result = $suit->close($templating->rules, 'username', $pop, $tree);
/*
Result: array
(
    'skip' => false,
    'tree' => array
    (
        array
        (
            'contents' => array('username'),
            'rule' => '[var]'
        )
    )
)
*/
?>[/skip][/transform]
        </fieldset>
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][skip]import suit
from rulebox import templating # easy_install rulebox
tree = [
    {
        'contents': [],
        'rule': '[var]'
    }
]
pop = tree.pop()
result = suit.close(templating.rules, 'username', pop, tree)
# Result: {
#     'skip': False,
#     'tree':
#     [
#         {
#             'contents': ['username'],
#             'rule': '[var]'
#         }
#     ]
# }[/skip][/transform]
        </fieldset>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">[gettext]Rules[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="execute" /]">[gettext]execute[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="parse" /]">[gettext]parse[/gettext]</a></li>
        </ul>
</fieldset>