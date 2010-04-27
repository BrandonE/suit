            [assign var="condition.dict" json="true"][call function="isdict" obj="node" /][/assign]
            [if condition="condition.dict"]
            <fieldset>
                [assign var="condition.createrule" json="true"][call function="indict" obj="node" key="createrule" /][/assign]
                [assign var="condition.closed" json="true"][call function="indict" obj="node" key="closed" /][/assign]
                <legend>[if condition="condition.createrule" not="true"][c]node.rule[/c][/if][if condition="condition.createrule"][c]node.createrule[/c][/if][if condition="condition.closed" not="true"] - [gettext]Not Closed[/gettext][/if]</legend>
                [local]
                [loop value="node" iterable="node.contents"]
                [execute][template]tree.tpl[/template][/execute]
                [/loop]
                [/local]
            </fieldset>
            [/if]
            [if condition="condition.dict" not="true"]
            <div style="font: 1em/1.2em monospace">[transform function="code"][c]node[/c][/transform]</div>
            [/if]