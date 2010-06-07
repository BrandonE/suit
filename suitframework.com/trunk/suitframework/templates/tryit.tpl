[trim]
[call function="header" /]
[call function="tryit" /]
[assign var="condition.tryit"]true[/assign]
[assign var="title"][gettext]Try It[if condition="condition.rule"] - [c]rule[/c][/if][/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
<p>Our <strong>Try It</strong> Editor can be used to play around with some Rulesets live on this site. Learn more about it on its <a href="[url controller="root" action="template" templatefile="docs" parameter1="tryit" /]">Docs article</a>.</p>
[if condition="condition.rule"]
[if condition="template"]
[if condition="error" not="true"]
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
[/if]
[if condition="error"]
<fieldset>
    <legend>[gettext]Error[/gettext]</legend>
    <p>[c]error[/c]</p>
</fieldset>
[/if]
<form action="#" method="post">
<p>[gettext]Template[/gettext]: <textarea name="template" style="width: 100%;" wrap="off" rows="20">
[c]template[/c]</textarea></p>
<p>
    <input type="checkbox" name="entities" value="true"[if condition="executeconfig.entities"] checked="checked"[/if]/> [gettext]Entities[/gettext]
    <input type="checkbox" name="insensitive" value="true"[if condition="executeconfig.insensitive"] checked="checked"[/if] /> [gettext]Insensitive[/gettext]
    <input type="checkbox" name="linebreak" value="true"[if condition="executeconfig.linebreak"] checked="checked"[/if] /> [gettext]Line Break[/gettext]
    <input type="checkbox" name="log" value="true"[if condition="executeconfig.log"] checked="checked"[/if] /> [gettext]Log[/gettext]
    <input type="checkbox" name="mismatched" value="true"[if condition="executeconfig.mismatched"] checked="checked"[/if] /> [gettext]Mismatched[/gettext]
    <input type="checkbox" name="unclosed" value="true"[if condition="executeconfig.unclosed"] checked="checked"[/if] /> [gettext]Unclosed[/gettext]
</p>
<p>
    [gettext]Escape[/gettext]: <input type="text" name="escape" value="[c]executeconfig.escape[/c]" />
</p>
<p><input type="submit" name="submit" value="[gettext]Submit[/gettext]" /></p>
</form>
[if condition="condition.php"]
<fieldset id="php">
    [if condition="condition.python"]
    <legend class="yesscript" style="display: none"><a href="#null" id="phplink">[gettext]PHP - Click to toggle[/gettext]</a></legend>
    <legend class="noscript">[gettext]PHP[/gettext]</legend>
    [/if]
    [if condition="condition.python" not="true"]
    <legend>[gettext]PHP[/gettext]</legend>
    [/if]
    [transform function="pygments" lexer="php"][c entities="false"]php[/c][/transform]
</fieldset>
[/if]
[if condition="condition.python"]
<fieldset[if condition="condition.php"] class="noscript"[/if] id="python">
    [if condition="condition.php"]
    <legend class="yesscript" style="display: none"><a href="#null" id="pythonlink">[gettext]Python - Click to toggle[/gettext]</a></legend>
    <legend class="noscript">[gettext]Python[/gettext]</legend>
    [/if]
    [if condition="condition.php" not="true"]
    <legend>[gettext]Python[/gettext]</legend>
    [/if]
    [transform function="pygments" lexer="python"][c entities="false"]python[/c][/transform]
</fieldset>
[/if]
[if condition="templates"]
[loop key="key" value="value" iterable="templates"]
<fieldset[if condition="condition.first" not="true"] class="noscript"[/if] id="template[c]key[/c]">
    [if condition="condition.templates"]
    <legend class="yesscript" style="display: none"><a href="#null" onclick="toggle([c json="true"]key[/c])">[gettext][c]value.base[/c] - Click to toggle[/gettext]</a></legend>
    <legend class="noscript">[gettext][c]value.base[/c][/gettext]</legend>
    [/if]
    [if condition="condition.templates" not="true"]
    <legend>[gettext][c]value.base[/c][/gettext]
    [/if]
    [transform function="pygments" lexer="html"][template][c]value.path[/c][/template][/transform]
</fieldset>
[assign json="true" var="condition.first"]false[/assign]
[/loop]
[/if]
[/if]
[if condition="condition.rule" not="true"]
<p>[gettext]Select a rule:[/gettext]</p>
<ul>
    <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="templating" /]">[gettext]Templating[/gettext]</a></li>
    <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="suitlons" /]">[gettext]SUITlons[/gettext]</a></li>
    <li><a href="[url controller="root" action="template" templatefile="tryit" parameter1="bbcode" /]">[gettext]BBCode[/gettext]</a></li>
</ul>
[/if]
[execute][template]footer.tpl[/template][/execute]
[/trim]