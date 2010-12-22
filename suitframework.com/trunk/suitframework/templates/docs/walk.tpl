<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Walk through the tree and generate the string.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    str suit.walk ( dict rules, dict tree [, dict config ] )
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="rules" href="#rules">rules</a></legend>
        <p>[gettext]The <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">rules</a> used to specify how to walk through the tree.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="tree" href="#tree">tree</a></legend>
        <p>[gettext]The tree to walk through.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="config" href="#config">config</a></legend>
        <p>[gettext]Specifics on how the function should work. (Optional. Default: see <a href="[url controller="root" action="template" templatefile="docs" parameter1="defaultconfig" /]">defaultconfig</a>)[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
    <p>[gettext]The generated string.[/gettext]</p>
    </table>
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
$tokens = $suit->tokens($templating->rules, $template);
$tree = $suit->parse($templating->rules, $tokens, $template);
echo $suit->walk($templating->rules, $tree);
// Result: Hello, <strong>Brandon</strong>!
?>[/skip][/transform]
        </fieldset>
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][skip]import suit
from rulebox import templating
templating.var.username = 'Brandon'
tokens = suit.tokens(templating.rules, template)
tree = suit.parse(templating.rules, tokens, template)
print suit.walk(templating.rules, tree)
# Result: Hello, <strong>Brandon</strong>![/skip][/transform]
        </fieldset>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">[gettext]Rules[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="execute" /]">[gettext]execute[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="tokens" /]">[gettext]tokens[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="parse" /]">[gettext]parse[/gettext]</a></li>
        </ul>
</fieldset>