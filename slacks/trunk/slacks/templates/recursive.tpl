[if condition="[loopvar json='true']recursed[/loopvar]" else="true"]
<fieldset>
    <legend>[var]language=>contents[/var]</legend>
    <div class="original">
    <pre>[entities][loopvar]original[/loopvar][/entities]</pre>
    </div>
[/if]
    [if condition="[loopvar json='true']array[/loopvar]" else="true"]
    <pre>[entities][loopvar]contents[/loopvar][/entities]</pre>
    [/if]
    [if condition="[loopvar json='true']array[/loopvar]"]
    <div class="contents" style="display: none">
    <fieldset>
        <legend>[entities][loopvar]node[/loopvar][/entities][if condition="[loopvar json='true']created[/loopvar]"] - [entities][loopvar]create[/loopvar][/entities][/if]</legend>
        [loop vars="[loopvar json='true']contents[/loopvar]"]
        [execute][template]templates/recursive.tpl[/template][/execute]
        [/loop]
    </fieldset>
    </div>
    <div class="case" style="display: none">
    <pre>[entities][loopvar]case[/loopvar][/entities]</pre>
    </div>
    [/if]
[if condition="[loopvar json='true']recursed[/loopvar]" else="true"]
</fieldset>
[/if]