        [assign var="key" json="true"][call function="increment" num="[c json='true']key[/c]" /][/assign]
        <fieldset id="signature[c]key[/c]">
            <legend><a href="#NULL" onclick="display(
                {
                    'show': ['string[c]key[/c]', 'tokens[c]key[/c]'],
                    'hide': ['signature[c]key[/c]', 'config[c]key[/c]', 'rules[c]key[/c]']
                }
            )">[gettext]Signature - Click to toggle[/gettext]</a></legend>
            <fieldset id="string[c]key[/c]">
                <legend><a href="#NULL" onclick="display({'show': ['rules[c]key[/c]'], 'hide': ['string[c]key[/c]']})">[gettext]string - Click to toggle[/gettext]</a></legend>
                <div style="font: 1em/1.2em monospace">[transform function="code"][c]log.hash.[c]value.string[/c][/c][/transform]</div>
            </fieldset>
            <fieldset id="rules[c]key[/c]" style="display: none">
                <legend><a href="#NULL" onclick="display({'show': ['config[c]key[/c]'], 'hide': ['rules[c]key[/c]']})">[gettext]rules - Click to toggle[/gettext]</a></legend>
                <textarea name="template" style="width: 100%;" wrap="off" rows="20">
[transform function="format"][c json="true"]log.hash.[c]value.rules[/c][/c][/transform]</textarea>
            </fieldset>
            <fieldset id="config[c]key[/c]" style="display: none">
                <legend><a href="#NULL" onclick="display({'show': ['string[c]key[/c]'], 'hide': ['config[c]key[/c]']})">[gettext]config - Click to toggle[/gettext]</a></legend>
                <textarea name="template" style="width: 100%;" wrap="off" rows="20">
[transform function="format"][c json="true"]log.hash.[c]value.config[/c][/c][/transform]</textarea>
            </fieldset>
        </fieldset>
        <fieldset id="tokens[c]key[/c]" style="display: none">
            <legend><a href="#NULL" onclick="display({'show': ['parse[c]key[/c]'], 'hide': ['tokens[c]key[/c]']})">[gettext]Tokens - Click to toggle[/gettext]</a></legend>
            <div style="font: 1em/1.2em monospace">[transform
                function='tokenshighlight'
                tokens='[c json="true"]log.hash.[c]value.tokens[/c][/c]'
                open='<strong><span style="color: blue">'
                close='<strong><span style="color: purple">'
                flat='<strong><span style="color: green">'
                end='</span></strong>'
            ][c entities="false"]log.hash.[c]value.string[/c][/c][/transform]</div>
        </fieldset>
        <fieldset id="parse[c]key[/c]" style="display: none">
            <legend><a href="#NULL" onclick="display({'show': ['walk[c]key[/c]'], 'hide': ['parse[c]key[/c]']})">[gettext]Parse - Click to toggle[/gettext]</a></legend>
            [loop value="node" list="[c json='true']log.hash.[c]value.parse[/c].contents[/c]" recurse="true"]
[execute][template]tree.tpl[/template][/execute]
            [/loop]
        </fieldset>
        <fieldset id="walk[c]key[/c]" style="display: none">
            <legend><a href="#NULL" onclick="display({'show': ['signature[c]key[/c]'], 'hide': ['walk[c]key[/c]']})">[gettext]Walk - Click to toggle[/gettext]</a></legend>
            <div style="font: 1em/1.2em monospace">[transform function="code"][c]log.hash.[c]value.walk[/c][/c][/transform]</div>
        </fieldset>
        [loop value="value" list="[c json='true']value.entries[/c]" recurse="true"]
        <fieldset>
            <legend>[gettext]Contents[/gettext]</legend>
[execute][template]entries.tpl[/template][/execute]
        </fieldset>
        [/loop]