<p><em>[gettext]Available Since:</em> Rulebox (1.0.0)[/gettext]</p>

<p>[gettext]Create a list.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    [gettext][skip][list(=equal)]string[/list][/skip][/gettext]
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="string" href="#string">string</a></legend>
        <p>[gettext]The string to split up and create a list from.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="equal" href="#equal">equal</a></legend>
        <p>[gettext]The type (1, a, A, i, I) of ordered list to create. (Default: "". It will create an unordered list.)[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="staticvariables" href="#staticvariables">[gettext]Static Variables[/gettext]</a></legend>
    <fieldset>
        <legend><a id="delimiter" href="#delimiter">delimiter</a></legend>
        <p>[gettext]The string to split up the list with. (Default: "[*]")[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="open" href="#open">open</a></legend>
        <p>[gettext]The string to open each item with. (Default: "[entities]<li>[/entities]")[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="close" href="#close">close</a></legend>
        <p>[gettext]The string to close each item with. (Default: "[entities]</li>[/entities]")[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="label" href="#label">label</a></legend>
        <p>[gettext]A string used to label this rule. (Default: "list")[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="template" href="#template">template</a></legend>
        <p>[gettext]The template to execute and replace the statement with. (Default: "". The template must be loaded manually.)[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="output" href="#output">[gettext]Output[/gettext]</a></legend>
    <p>[gettext]The list.[/gettext]</p>
</fieldset>

<fieldset>
    <legend><a id="confignotes" href="#confignotes">[gettext]Config Notes[/gettext]</a></legend>
    <ul>
        <li>[gettext]Uses the default <a href="[url controller="root" action="template" templatefile="docs" parameter1="attributes" /]#bbcodeconfig">BBCode Attribute Config</a>.[/gettext]</li>
    </ul>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="bbcode" parameter2="list" /]">[gettext]Try It[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="attributes" /]">[gettext]Attributes[/gettext]</a></li>
        </ul>
</fieldset>