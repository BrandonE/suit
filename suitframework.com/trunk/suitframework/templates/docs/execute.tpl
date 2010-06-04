<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Transform a string using rules. The function calls <a href="[url controller="root" action="template" templatefile="docs" parameter1="tokens" /]">tokens</a>, <a href="[url controller="root" action="template" templatefile="docs" parameter1="parse" /]">parse</a>, and <a href="[url controller="root" action="template" templatefile="docs" parameter1="walk" /]">walk</a> all in one convenient call.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    str suit.execute ( dict rules, str string [, dict config ] )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="rules" href="#rules">rules</a></legend>
        <p>[gettext]The <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">rules</a> to use to transform the string.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="string" href="#string">string</a></legend>
        <p>[gettext]The string to transform.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="config" href="#config">config</a></legend>
        <p>[gettext]Specifics on how the function should work. (Optional. Default: see <a href="[url controller="root" action="template" templatefile="docs" parameter1="defaultconfig" /]">defaultconfig</a>)[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <p>[gettext]The transformed string.[/gettext]</p>
</fieldset>

<fieldset>
    <legend><a id="examples" href="#examples">[gettext]Examples[/gettext]</a></legend>
    <fieldset>
        <legend><a id="basicusage" href="#basicusage">[gettext]Basic Usage[/gettext]</a></legend>
        <fieldset>
            <legend><a id="basicusagetemplate" href="#basicusagetemplate">[gettext]Template[/gettext]</a></legend>
            [transform function="pygments" lexer="html"][skip]Hello, <strong>[var]username[/var]</strong>![/skip][/transform]
        </fieldset>
        <fieldset id="php">
            <legend class="yesscript" style="display: none"><a href="#null" id="phplink">[gettext]PHP - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]PHP[/gettext]</legend>
            [transform function="pygments" lexer="php"][skip]<?php
require 'suit.class.php';
require 'templating.class.php';
$suit = new SUIT();
$templating = new Templating($suit);
$templating->var->username = 'Brandon';
print $suit->execute($templating->rules, $template);
// Result: Hello, <strong>Brandon</strong>!
?>[/skip][/transform]
        </fieldset>
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][skip]import suit
from rulebox import templating
templating.var.username = 'Brandon'
print suit.execute(templating.rules, template)
# Result: Hello, <strong>Brandon</strong>![/skip][/transform]
        </fieldset>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">[gettext]Rules[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="escaping" /]">[gettext]Escaping[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="defaultconfig" /]">[gettext]defaultconfig[/gettext]</a></li>
        </ul>
</fieldset>