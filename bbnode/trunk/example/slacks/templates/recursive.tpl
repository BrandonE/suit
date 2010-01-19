[if condition="[loopvar json='true']array[/loopvar]" else="true"]
<fieldset>
    <legend>Contents</legend>
    \[loopvar]contents\[/loopvar]
</fieldset>
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
