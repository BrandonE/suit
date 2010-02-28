[if condition="[loopvar json='true']recursed[/loopvar]" else="true"]
<fieldset id="box[loopvar]id[/loopvar]">
    <legend>[gettext]Contents[/gettext]</legend>
[/if]
    [if condition="[loopvar json='true']array[/loopvar]" else="true"]
    <pre>[loopvar]contents[/loopvar]</pre>
    [/if]
    [if condition="[loopvar json='true']array[/loopvar]"]
    [if condition="[loopvar json='true']recursed[/loopvar]" else="true"]
    <div id="original[loopvar]id[/loopvar]">
        <fieldset>
            <legend><a href="#NULL" onclick="box('[loopvar]id[/loopvar]', 0)">Original</a></legend>
            <pre>[loopvar]original[/loopvar]</pre>
    </div>
    [/if]
    <div id="contents[loopvar]id[/loopvar]"[if condition="[loopvar json='true']recursed[/loopvar]" else="true"]style="display: none"[/if]>
        <fieldset>
            <legend><a href="#NULL" onclick="box('[loopvar]id[/loopvar]', 1)">[loopvar]rule[/loopvar][if condition="[loopvar json='true']created[/loopvar]"] - [loopvar]create[/loopvar][/if]</legend></a>
            <fieldset>
                <legend>[gettext]Contents[/gettext]</legend>
                [loop vars="[loopvar json='true']contents[/loopvar]"]
                [execute][template]slacksrecurse.tpl[/template][/execute]
                [/loop]
            </fieldset>
            [loop vars="[loopvar json='true']parallel[/loopvar]"]
            <fieldset>
                <legend>[gettext]Parallel[/gettext]</legend>
                    <a href="#box[loopvar]parallel[/loopvar]">Link</a>
            </fieldset>
            [/loop]
        </fieldset>
    </div>
    <div id="case[loopvar]id[/loopvar]" style="display: none">
        <fieldset>
            <legend><a href="#NULL" onclick="box('[loopvar]id[/loopvar]', 2)">[gettext]Case[/gettext]</a></legend>
            <pre>[loopvar]case[/loopvar]</pre>
        </fieldset>
    </div>
    [/if]
[if condition="[loopvar json='true']recursed[/loopvar]" else="true"]
</fieldset>
[/if]