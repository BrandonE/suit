<fieldset>
    <legend>Line [loopvar]line[/loopvar] in [loopvar]file[/loopvar]</legend>
    [loop vars="[loopvar json='true']steps[/loopvar]"]
    <fieldset class="text">
        <legend>Return</legend>
        <pre>[loopvar]text[/loopvar]</pre>
    </fieldset>
    <fieldset class="return">
        <legend>Return</legend>
        [loopvar]return[/loopvar]
    </fieldset>
    [if condition="[loopvar json='true']recursing[/loopvar]"]
    <fieldset>
        <legend>Recurse</legend>
        [loop vars="[loopvar json='true']recurse[/loopvar]" skip="false"]
        [template]templates/recursive.tpl[/template]
        [/loop]
    </fieldset>
    [/if]
    [/loop]
</fieldset>