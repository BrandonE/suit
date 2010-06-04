<p><em>[gettext]Available Since:</em> Rulebox (1.0.0)[/gettext]</p>

<p>[gettext]Get a variable based on a split string.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    mixed templating.getvariable ( str string, str split, mixed owner )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="string" href="#string">string</a></legend>
        <p>[gettext]The name of the variable to grab.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="split" href="#split">split</a></legend>
        <p>[gettext]The string that separates the levels of the variable.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="owner" href="#owner">owner</a></legend>
        <p>[gettext]The object to grab the variable from.[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <p>[gettext]The variable.[/gettext]</p>
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
$templating->var->dict = array
(
    'foo' => 'bar'
);
$foo = $templating->getvariable('dict.foo', '.', $templating->var);
// Result: 'bar'
?>[/skip][/transform]
        </fieldset>
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][skip]from rulebox import templating
templating.var.dict = {
    'foo': 'bar'
}
foo = templating.getvariable('dict.foo', '.', templating.var)
# Result: 'bar'[/skip][/transform]
        </fieldset>
    </fieldset>
</fieldset>