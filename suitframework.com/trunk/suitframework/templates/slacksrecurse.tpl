[if condition="[c json='true']value.recursed[/c]" else="true"]
<fieldset id="box[c]value.id[/c]">
    <legend>[gettext]Contents[/gettext]</legend>
[/if]
    [if condition="[c json='true']value.array[/c]" else="true"]
    <pre>[c]value.contents[/c]</pre>
    [/if]
    [if condition="[c json='true']value.array[/c]"]
    [if condition="[c json='true']value.recursed[/c]" else="true"]
    <div id="original[c]value.id[/c]">
        <fieldset>
            <legend><a href="#NULL" onclick="box('[c]value.id[/c]', 0)">Original</a></legend>
            <pre>[c]value.original[/c]</pre>
    </div>
    [/if]
    <div id="contents[c]value.id[/c]"[if condition="[c json='true']value.recursed[/c]" else="true"]style="display: none"[/if]>
        <fieldset>
            <legend><a href="#NULL" onclick="box('[c]value.id[/c]', 1)">[c]value.rule[/c][if condition="[c json='true']value.created[/c]"] - [c]value.create[/c][/if]</legend></a>
            <fieldset>
                <legend>[gettext]Contents[/gettext]</legend>
                [loop value="value" in="[c json='true']value.contents[/c]" recurse="true"]
                [execute][template]slacksrecurse.tpl[/template][/execute]
                [/loop]
            </fieldset>
            [loop value="parallel" in="[c json='true']value.parallel[/c]"]
            <fieldset>
                <legend>[gettext]Parallel[/gettext]</legend>
                    <a href="#box[c]parallel.parallel[/c]">Link</a>
            </fieldset>
            [/loop]
        </fieldset>
    </div>
    <div id="case[c]value.id[/c]" style="display: none">
        <fieldset>
            <legend><a href="#NULL" onclick="box('[c]value.id[/c]', 2)">[gettext]Case[/gettext]</a></legend>
            <pre>[c]value.case[/c]</pre>
        </fieldset>
    </div>
    [/if]
[if condition="[c json='true']value.recursed[/c]" else="true"]
</fieldset>
[/if]