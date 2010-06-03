<p><em>[gettext]Available Since:</em> Rulebox (1.0.0)[/gettext]</p>

<p>[gettext]Assign variable in the template.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    [gettext][skip][assign ( var="str" json="bool" )]value[/assign][/skip][/gettext]
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="value" href="#value">value</a></legend>
        <p>[gettext]The value to assign to the variable.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="var" href="#var">var</a></legend>
        <p>[gettext]The name of the variable to set. (Default: null. Nothing will be set.)[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="json" href="#json">json</a></legend>
        <p>[gettext]Whether or not the value should be JSON decoded. (Default: false)[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="staticvariables" href="#staticvariables">[gettext]Static Variables[/gettext]</a></legend>
    <fieldset>
        <legend><a id="decode" href="#decode">decode</a></legend>
        <p>[gettext]The variables to be JSON decoded. (Default: ["json"])[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="delimiter" href="#delimiter">delimiter</a></legend>
        <p>[gettext]The string that separates the levels of the variable. (Default: ".")[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="owner" href="#owner">owner</a></legend>
        <p>[gettext]The object to set the variable on. (Default: templating.var)[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="output" href="#output">[gettext]Output[/gettext]</a></legend>
    <p>[gettext]Nothing. It assigns the variable and collapses.[/gettext]</p>
</fieldset>

<fieldset>
    <legend><a id="confignotes" href="#confignotes">[gettext]Config Notes[/gettext]</a></legend>
    <ul>
        <li>[gettext]Uses the default <a href="[url controller="root" action="template" templatefile="docs" parameter1="attributes" /]#config">Attribute Config</a>.[/gettext]</li>
    </ul>
</fieldset>

<fieldset>
    <legend><a id="seealso" href="#seealso">[gettext]See Also[/gettext]</a></legend>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="templating" parameter2="assign" /]">[gettext]Try It[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="attributes" /]">[gettext]Attributes[/gettext]</a></li>
        </ul>
</fieldset>