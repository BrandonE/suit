<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Translate string using rules.[/gettext]</p>

<fieldset>
	<legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
	str suit.execute ( dict rules, str string [, dict config ] )
</fieldset>

<fieldset>
	<legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
	<fieldset>
		<legend><a id="rules" href="#rules">rules</a></legend>
		<p>[gettext]The <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">rules</a> to use to translate the string.[/gettext]</p>
	</fieldset>
	<fieldset>
		<legend><a id="string" href="#string">string</a></legend>
		<p>[gettext]The string to translate.[/gettext]</p>
	</fieldset>
	<fieldset>
		<legend><a id="config" href="#config">config</a></legend>
		<p>
            [gettext]Specifics on how the function should work. (Optional)[/gettext]
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
                </tbody>
                <tbody>
                    <tr>
                        <td>insensitive</td>
                        <td>[gettext]bool: Whether or not finding the positions should be insensitive.[/gettext]</td>
                        <td>True</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td>log</td>
                        <td>[gettext]bool: Whether or not to log this call.[/gettext]</td>
                        <td>True</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td>mismatched</td>
                        <td>[gettext]bool: Whether or not to parse if the closing string does not match the opening string.[/gettext]</td>
                        <td>False</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td>unclosed</td>
                        <td>[gettext]bool: Whether or not unclosed nodes should be executed.[/gettext]</td>
                        <td>False</td>
                    </tr>
                </tbody>
            </table>
                       
        </p>
	</fieldset>
</fieldset>

<fieldset>
	<legend><a id="returnvalue" href="#returnvalue">[gettext]Return Value[/gettext]</a></legend>
	<p>[gettext]The translated string.[/gettext]</p>
</fieldset>

<fieldset>
	<legend><a id="examples" href="#examples">[gettext]Examples[/gettext]</a></legend>
    <fieldset>
        <legend><a id="basicusage" href="#basicusage">[gettext]Basic Usage[/gettext]</a></legend>
        <fieldset>
            <legend><a id="basicusagetemplate" href="#basicusagetemplate">[gettext]Template[/gettext]</a></legend>
            <pre>[entities]Hello, <strong>[var]username[/var]</strong>![/entities]</pre>
        </fieldset>
        <fieldset>
            <legend><a id="basicusagephp" href="#basicusagephp">[gettext]PHP[/gettext]</a></legend>
            [transform function="pygments" lexer="php"]<?php
require 'suit.class.php';
require 'templating.class.php';
$suit = new SUIT();
$templating = new Templating($suit);
$templating->var->username = 'Brandon';
print $suit->execute($templating->rules, $template);
// Result: Hello, <strong>Brandon</strong>!
?>[/transform]
        </fieldset>
        <fieldset>
            <legend><a id="basicusagepython" href="#basicusagepython">[gettext]Python[/gettext]</a></legend>
            [transform function="pygments" lexer="python"]import suit
from rulebox import templating # easy_install rulebox
templating.var.username = 'Brandon'
print suit.execute(templating.rules, template)
# Result: Hello, <strong>Brandon</strong>!
[/transform]
        </fieldset>
    </fieldset>
</fieldset>

<fieldset>
	<legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
		<ul>
			<li><a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">[gettext]Rules[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="escaping" /]">[gettext]Escaping[/gettext]</a></li>
		</ul>
</fieldset>