<a href="#intro" id="intro"><h3>[gettext]What is an Attribute?[/gettext]</h3></a>

<p>[gettext]An attribute is used to modify the properties of a rule through the template. If a rule is configured to use attributes, any attributes in any order can be used, which will modify variables that the rule's functions use.[/gettext]</p>

<a href="#config" id="config"><h3>[gettext]Attribute Config[/gettext]</h3></a>

<p>[gettext]A Double Sided rule using attributes (Such as <a href="[url controller="root" action="template" templatefile="docs" parameter1="if" /]">if</a>) needs two rules to operate. The first matches the unmodified open and close strings (Ex. "[rule]" and "[/rule]"), defines the default variables, and configures how to assign the attributes for it.[/gettext]</p>

<fieldset>
    <legend><a id="staticvariables" href="#staticvariables">[gettext]Static Variables[/gettext]</a></legend>
    <fieldset>
        <legend><a id="blacklist" href="#blacklist">blacklist</a></legend>
        <p>[gettext]Whether or not if a list has been provided, it should be treated as a blacklist. (Default: null. If a list has been provided, it will be treated like a whitelist.)[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="equal" href="#equal">equal</a></legend>
        <p>[gettext]The string to be used for assignment. (Default: "=")[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="list" href="#list">list</a></legend>
        <p>[gettext]A list of template names to either be blacklisted or whitelisted. (Default: null. All files could be grabbed from.)[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="log" href="#log">log</a></legend>
        <p>[gettext]Whether or not the execute call should be logged. (Default: false)[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="onesided" href="#onesided">onesided</a></legend>
        <p>[gettext]If true, the variables are defined through the rules contents. If null or false, the variables are defined from another rules contents. (Default: null.)[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="quote" href="#quote">quote</a></legend>
        <p>[gettext]The string to contain values. (Default: "\"")[/gettext]</p>
    </fieldset>
    <fieldset>
        <legend><a id="var" href="#var">var</a></legend>
        <p>[gettext]The dict containing the variables to be used by the rule's functions which might be editable by attributes. (Default: Varies from rule to rule)[/gettext]</p>
    </fieldset>
</fieldset>

<p>[gettext]The second rule matches the open and close strings for the attribute statement (Ex. "[rule" and "]"), stores its contents, and treats the entire statement as a new opening string.[/gettext]</p>

<fieldset>
    <legend><a id="confignotes" href="#confignotes">[gettext]Config Notes[/gettext]</a></legend>
    <ul>
        <li>[gettext]Has 'skip' enabled as true.[/gettext]</li>
    </ul>
</fieldset>

<p>[gettext]When both rules combined are successfully matched, a function will define variables on var using the contents of the second rule, have the new var overwrite all of the Static Variables, and continue as normal.[/gettext]</p>

<a href="#onesidedconfig" id="onesidedconfig"><h3>[gettext]One Sided Attribute Config[/gettext]</h3></a>

<p>[gettext]One Sided rule using attributes (Such as <a href="[url controller="root" action="template" templatefile="docs" parameter1="return" /]">return</a>) only needs one rule. The Static Variables are the same, except for onesided being true. In addition, the following Config is applied:[/gettext]</p>

<fieldset>
    <legend><a id="confignotes2" href="#confignotes2">[gettext]Config Notes[/gettext]</a></legend>
    <ul>
        <li>[gettext]Has 'skip' enabled as true.[/gettext]</li>
    </ul>
</fieldset>

<p>[gettext]When matched, a function will define variables on var using this rules contents, have the new var overwrite all of the Static Variables, and continue as normal.[/gettext]</p>

<a href="#bbcodeconfig" id="bbcodeconfig"><h3>[gettext]BBCode Config[/gettext]</h3></a>

<p>[gettext]Similarly to the Template Ruleset's Attribute Config, each attribute rule requires two rules: one to use the default settings with (Ex. "[rule]" and "[/rule]"), and another one to create modified rules (Ex. "[rule=" and "]"). However, BBCode attributes can only modify one variable: equal. In addition, the attribute string isn't split up by quotes, and additional configuration isn't necessary necessary.[/gettext]</p>