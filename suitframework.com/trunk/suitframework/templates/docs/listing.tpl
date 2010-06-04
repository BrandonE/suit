<p><em>[gettext]Available Since:</em> Rulebox (1.0.0)[/gettext]</p>

<p>[gettext]Check if the variable is whitelisted or blacklisted and determine whether or not the variable can be used.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    bool templating.listing ( str name, dict variable )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="name" href="#name">name</a></legend>
        <p>[gettext]The name of the variable to check.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="variable" href="#variable">variable</a></legend>
        <p>[gettext]A dict containing the `list` and `blacklist` keys if applicable.[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <p>[gettext]Whether or not the variable can be used.[/gettext]</p>
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
$variable = array
(
    'blacklist' => true,
    'list' => array('restricted')
);
$result = $templating->listing('restricted', $variable);
// Result: false
?>[/skip][/transform]
        </fieldset>
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][skip]from rulebox import templating
variable = {
    'blacklist': True,
    'list': ['restricted']
}
result = templating.listing('restricted', variable)
# Result: False[/skip][/transform]
        </fieldset>
    </fieldset>
</fieldset>