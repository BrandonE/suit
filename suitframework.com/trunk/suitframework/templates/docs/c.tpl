<p><em>[gettext]Available Since:</em> Rulebox (1.0.0)[/gettext]</p>

<p>[gettext]Grab a variable from tmpl_context.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    [gettext][skip][c ( entities="bool" json="bool" )]var[/c][/skip][/gettext]
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="var" href="#var">var</a></legend>
        <p>[gettext]The name of the variable to grab.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="entities" href="#entities">entities</a></legend>
        <p>[gettext]Whether or not the output should have the HTML entities transformed. (Default: true)[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="json" href="#json">json</a></legend>
        <p>[gettext]Whether or not the variable should be JSON encoded. (Default: false)[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="staticvariables" href="#staticvariables">[gettext]Static Variables[/gettext]</a></legend>
    <fieldset>
        <legend><a id="decode" href="#decode">decode</a></legend>
        <p>[gettext]The variables to be JSON decoded. (Default: ["entities", "json"])[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="delimiter" href="#delimiter">delimiter</a></legend>
        <p>[gettext]The string that separates the levels of the variable. (Default: ".")[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="owner" href="#owner">owner</a></legend>
        <p>[gettext]The object to grab the variable from. (Default: pylons.tmpl_context)[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="output" href="#output">[gettext]Output[/gettext]</a></legend>
    <p>[gettext]The variable.[/gettext]</p>
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
            <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="suitlons" parameter2="c" /]">[gettext]Try It[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="attributes" /]">[gettext]Attributes[/gettext]</a></li>
        </ul>
</fieldset>