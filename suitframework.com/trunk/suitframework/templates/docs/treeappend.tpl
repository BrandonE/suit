<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Add to the tree contents in the appropriate place if necessary.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    list suit.treeappend ( mixed append, list tree )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="append" href="#append">append</a></legend>
        <p>[gettext]The items to add.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="config" href="#config">config</a></legend>
        <p>[gettext]The contents of the tree to add the item on.[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <p>[gettext]The updated tree contents.[/gettext]</p>
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
$tree = array
(
    array
    (
        'contents' => array(),
        'rule' => '[var]'
    )
);
$tree = $suit->treeappend(array('Test'), $tree);
/*
Result: array
(
    array
    (
        'rule' => '[var]',
        'contents' => array('Test')
    )
)
*/
?>[/skip][/transform]
        </fieldset>
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][skip]import suit
tree = [
    {
        'contents': [],
        'rule': '[var]'
    }
]
tree = suit.treeappend(['Test'], tree)
# Result: [
#     {
#         'rule': '[var]',
#         'contents': ['Test']
#     }
# ][/skip][/transform]
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