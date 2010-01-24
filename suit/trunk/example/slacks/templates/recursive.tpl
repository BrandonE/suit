[if condition="[loopvar json='true']array[/loopvar]" else="true"]
[comment]
<fieldset>
    <legend>Contents</legend>
    [loopvar]contents[/loopvar]
</fieldset>
[/comment]
<fieldset>
    <legend>Contents</legend>
    <pre>[loopvar]text[/loopvar]</pre>
</fieldset>
[/if]
[if condition="[loopvar json='true']array[/loopvar]"]
<fieldset>
    <legend>Contents</legend>
    [loop vars="[loopvar json='true']contents[/loopvar]"]
    [execute][template]templates/recursive.tpl[/template][/execute]
    [/loop]
</fieldset>
[/if]
