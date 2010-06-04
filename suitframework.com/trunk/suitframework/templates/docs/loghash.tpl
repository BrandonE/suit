<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Hash specific keys for logging.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    dict suit.loghash ( dict entry, list items )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="entry" href="#entry">entry</a></legend>
        <p>[gettext]The dict.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="items" href="#items">items</a></legend>
        <p>[gettext]The items to hash in the dict.[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <p>[gettext]The dict with the specified items hashed.[/gettext]</p>
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
$entry = array
(
    'config' => array
    (
        'unclosed' => false,
        'insensitive' => true,
        'log' => true,
        'mismatched' => false,
        'escape' => ''
    ),
    'contents' => array()
);
$entry = $suit->loghash($entry, array('config'));
/*
Result: array
(
    'config' => 'e9b7b42182b735ccd2c6fbc67978d0d0',
    'contents' => array()
)
*/
?>[/skip][/transform]
        </fieldset>
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][skip]import suit
entry = {
    'config': {
        'unclosed': False,
        'insensitive': True,
        'log': True,
        'mismatched': False,
        'escape': ''
    },
    'contents': []
}
entry = suit.loghash(entry, ['config'])
# Result: {
#     'config': 'e9b7b42182b735ccd2c6fbc67978d0d0',
#     'contents': []
# }[/skip][/transform]
        </fieldset>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="execute" /]">[gettext]execute[/gettext]</a></li>
        </ul>
</fieldset>