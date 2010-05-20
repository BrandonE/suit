<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Loop a string with different variables.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    [gettext][skip][loop ( key="str" value="str" iterable="str" join="str" )]string[/loop][/skip][/gettext]
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="string" href="#string">string</a></legend>
        <p>[gettext]The string to be walked through multiple times with a different key and value combination.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="key" href="#key">key</a></legend>
        <p>[gettext]The name of the variable to store the key for each iteration. (Default: null. Will not be stored.)[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="value" href="#value">value</a></legend>
        <p>[gettext]The name of the variable to store the value for each iteration. (Default: null. Will not be stored.)[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="iterable" href="#iterable">iterable</a></legend>
        <p>[gettext]The name of the variable containing the iterable to use. (Default: null. The string will collapse.)[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="join" href="#join">join</a></legend>
        <p>[gettext]The string to join the results with. (Default: '')[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="output" href="#output">[gettext]Output[/gettext]</a></legend>
    <p>[gettext]The combined results.[/gettext]</p>
    </table>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="templating" parameter2="loop" /]">[gettext]Try It.[/gettext]</a></li>
        </ul>
</fieldset>