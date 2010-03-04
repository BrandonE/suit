[trim]
[call function="header" /]
[call function="tryit" /]
[assign var="condition.tryit"]true[/assign]
[assign var="title"][gettext]Try It[if condition="[c json='true']condition.rule[/c]"] - [c]rule[/c][/if][/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
[/trim]
        [if condition="[c json='true']condition.rule[/c]"]
        <p><strong>[gettext]This editor is running on SUIT for Python, but it provides all versions of the code applicable. The templates contain comments about the few (if any) things that need to be changed for the other versions.[/gettext]</strong></p>
        [if condition="[c json='true']template[/c]"]
        <fieldset>
            <legend>[gettext]HTML[/gettext]</legend>
[c entities="false"]executed[/c]
        </fieldset>
        <fieldset>
            <legend>[gettext]Text[/gettext]</legend>
<pre>[c]executed[/c]</pre>
        </fieldset>
        [/if]
[trim]
        <form action="#" method="post">
        <p>[gettext]Template[/gettext]: <textarea name="template" style="width: 100%;" wrap="off" rows="20">
[c]template[/c]</textarea></p>
        <p><input type="submit" name="submit" value="[gettext]Submit[/gettext]" /></p>
        </form>
        [if condition="[c json='true']condition.php[/c]"]
        <fieldset>
            <legend>[gettext]PHP[/gettext]</legend>
            [if condition="[c json='true']condition.pygments[/c]"]
            [c entities="false"]php[/c]
            [/if]
            [if condition="[c json='true']condition.pygments[/c]" else="true"]
            <pre>[c]php[/c]</pre>
            [/if]
        </fieldset>
        [/if]
        [if condition="[c json='true']condition.python[/c]"]
        <fieldset>
            <legend>[gettext]Python[/gettext]</legend>
            [if condition="[c json='true']condition.pygments[/c]"]
            [c entities="false"]python[/c]
            [/if]
            [if condition="[c json='true']condition.pygments[/c]" else="true"]
            <pre>[c]python[/c]</pre>
            [/if]
        </fieldset>
        [/if]
[/trim]
        [/if]
[trim]
        [if condition="[c json='true']condition.rule[/c]" else="true"]
        <p>[gettext]Select a rule:[/gettext]</p>
        <ul>
            <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="templating" /]">[gettext]Templating[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="suitlons" /]">[gettext]SUITlons[/gettext]</a></li>
            <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="bbcode" /]">[gettext]BBCode[/gettext]</a></li>
        </ul>
        [/if]
[execute][template]footer.tpl[/template][/execute]
[/trim]