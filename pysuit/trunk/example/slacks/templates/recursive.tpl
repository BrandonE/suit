[if condition="[loopvar json='true']recursed[/loopvar]" else="true"]
<fieldset id="box[loopvar]id[/loopvar]">
    <legend>[var]language=>contents[/var]</legend>
[/if]
    [if condition="[loopvar json='true']array[/loopvar]" else="true"]
    <pre>[entities][loopvar]contents[/loopvar][/entities]</pre>
    [/if]
    [if condition="[loopvar json='true']array[/loopvar]"]
    [if condition="[loopvar json='true']recursed[/loopvar]" else="true"]
    <div id="original[loopvar]id[/loopvar]">
        <fieldset>
            <legend><a href="#NULL" onclick="box('[loopvar]id[/loopvar]', 0)">Original</a></legend>
            <pre>[entities][loopvar]original[/loopvar][/entities]</pre>
    </div>
    [/if]
    <div id="contents[loopvar]id[/loopvar]"[if condition="[loopvar json='true']recursed[/loopvar]" else="true"]style="display: none"[/if]>
        <fieldset>
            <legend><a href="#NULL" onclick="box('[loopvar]id[/loopvar]', 1)">[entities][loopvar]node[/loopvar][/entities][if condition="[loopvar json='true']created[/loopvar]"] - [entities][loopvar]create[/loopvar][/entities][/if]</legend></a>
            <fieldset>
                <legend>[var]language=>contents[/var]</legend>
                [loop vars="[loopvar json='true']contents[/loopvar]"]
                [execute][template]templates/recursive.tpl[/template][/execute]
                [/loop]
            </fieldset>
            [loop vars="[loopvar json='true']parallel[/loopvar]"]
            <fieldset>
                <legend>[var]language=>parallel[/var]</legend>
                    <a href="#box[loopvar]parallel[/loopvar]">Link</a>
            </fieldset>
            [/loop]
        </fieldset>
    </div>
    <div id="case[loopvar]id[/loopvar]" style="display: none">
        <fieldset>
            <legend><a href="#NULL" onclick="box('[loopvar]id[/loopvar]', 2)">[var]language=>case[/var]</a></legend>
            <pre>[entities][loopvar]case[/loopvar][/entities]</pre>
        </fieldset>
    </div>
    [/if]
[if condition="[loopvar json='true']recursed[/loopvar]" else="true"]
</fieldset>
[/if]