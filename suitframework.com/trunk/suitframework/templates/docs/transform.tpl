<p><em>[gettext]Available Since:</em> Rulebox (1.0.0)[/gettext]</p>

<p>[gettext]Call a function to transform a string.[/gettext]</p>

<fieldset>
    <legend><a id="syntax" href="#syntax">[gettext]Syntax[/gettext]</a></legend>
    [gettext][skip][transform ( function="str" param="mixed" ... )]string[/transform][/skip][/gettext]
</fieldset>

<fieldset>
    <legend><a id="parameters" href="#parameters">[gettext]Parameters[/gettext]</a></legend>
    <fieldset>
        <legend><a id="string" href="#string">string</a></legend>
        <p>[gettext]The string to be sent as a parameter and be transformed.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="function" href="#function">function</a></legend>
        <p>[gettext]The name of the function to call.[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="param" href="#param">param</a></legend>
        <p>[gettext]An additional parameter to send to the function. Infinite params can be passed through this method with any name.[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="staticvariables" href="#staticvariables">[gettext]Static Variables[/gettext]</a></legend>
    <fieldset>
        <legend><a id="owner" href="#owner">owner</a></legend>
        <p>[gettext]The object to call the function on. (Default: null. In SUIT for PHP, a global function will be called. In SUIT for Python, nothing will happen.)[/gettext]</p>
    </fieldset>
</fieldset>

<fieldset>
    <legend><a id="output" href="#output">[gettext]Output[/gettext]</a></legend>
    <p>[gettext]The transformed string.[/gettext]</p>
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
            <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="templating" parameter2="transform" /]">[gettext]Try It[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="attributes" /]">[gettext]Attributes[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="rulesvsfunctions" /]">[gettext]Rules Vs. Functions[/gettext]</a></li>
        </ul>
</fieldset>