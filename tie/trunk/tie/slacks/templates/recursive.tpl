[if condition="[loopvar json='true']array[/loopvar]" else="true"]
[comment]
<fieldset>
    <legend>Contents</legend>
    [replace find="<slacks />" replace=""][loopvar]contents[/loopvar][/replace]
</fieldset>
[/comment]
<fieldset>
    <legend>Contents</legend>
    <pre>[entities][loopvar]contents[/loopvar][/entities]</pre>
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
