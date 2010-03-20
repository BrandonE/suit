            [assign var="condition.dict" json="true"][call function="isdict" obj="[c json='true']node[/c]" /][/assign]
            [if condition="[c json='true']condition.dict[/c]" else="true"]
            <div style="font: 1em/1.2em monospace">[transform function="code"][c]node[/c][/transform]</div>
            [/if]
            [if condition="[c json='true']condition.dict[/c]"]
            <fieldset>
                [assign var="condition.createrule" json="true"][call function="indict" obj="[c json='true']node[/c]" key="createrule" /][/assign]
                [assign var="condition.closed" json="true"][call function="indict" obj="[c json='true']node[/c]" key="closed" /][/assign]
                <legend>[if condition="[c json='true']condition.createrule[/c]" else="true"][c]node.rule[/c][/if][if condition="[c json='true']condition.createrule[/c]"][c]node.createrule[/c][/if][if condition="[c json='true']condition.closed[/c]" else="true"] - [gettext]Not Closed[/gettext][/if]</legend>
                [loop value="node" list="[c json='true']node.contents[/c]" recurse="true"]
[execute][template]tree.tpl[/template][/execute]
                [/loop]
            </fieldset>
            [/if]