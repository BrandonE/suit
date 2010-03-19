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
        <fieldset id="html">
            <legend class="yesscript" style="display: none"><a href="#null" id="htmllink">[gettext]HTML - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]HTML[/gettext]</legend>
[c entities="false"]executed[/c]
        </fieldset>
        <fieldset class="noscript" id="text">
            <legend class="yesscript" style="display: none"><a href="#null" id="textlink">[gettext]Text - Click to toggle[/gettext]</a></legend>
            <legend class="noscript">[gettext]Text[/gettext]</legend>
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
        <fieldset id="php">
            <legend class="yesscript" style="display: none"><a href="#null" id="phplink">[gettext]PHP - Click to toggle[/gettext]</a></legend>
            <legend class="noscript"><legend>[gettext]PHP[/gettext]</legend>
            [transform function="pygments" lexer="php"][c entities="false"]php[/c][/transform]
        </fieldset>
        [/if]
        [if condition="[c json='true']condition.python[/c]"]
        <fieldset class="noscript" id="python">
            <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
            <legend class="noscript"><legend>[gettext]Python[/gettext]</legend>
            [transform function="pygments" lexer="python"][c entities="false"]python[/c][/transform]
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