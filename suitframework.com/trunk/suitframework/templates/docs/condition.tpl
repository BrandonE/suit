<p><em>[gettext]Available Since:</em> SUIT (2.0.0)[/gettext]</p>

<p>[gettext]Show the string if necessary.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    [gettext][skip][if ( condition="str" not="bool" )]string[/if][/skip][/gettext]
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="string" href="#string">string</a></legend>
        <p>[gettext]The string to be shown or collapsed based on the condition.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="condition" href="#condition">condition</a></legend>
        <p>[gettext]The name of the variable containing the condition result. (Default: null. The string will collapse.)[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="not" href="#not">not</a></legend>
        <p>[gettext]Whether or not to negate the results of the condition. (Default: false)[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="output" href="#output">[gettext]Output[/gettext]</a></legend>
    <p>[gettext]Depending on the condition, either the string or nothing.[/gettext]</p>
    </table>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="templating" parameter2="condition" /]">[gettext]Try It.[/gettext]</a></li>
        </ul>
</fieldset>