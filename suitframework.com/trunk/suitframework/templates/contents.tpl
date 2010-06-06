        <fieldset>
            <legend>[gettext]Contents[/gettext]</legend>
            [assign var="iteration" json="true"][call function="increment" num="iteration" /][/assign]
            <fieldset id="parameters[c]iteration[/c]">
                <legend class="yesscript" style="display: none"><a href="#NULL" onclick="display(
                    {
                        'show': ['string[c]iteration[/c]', 'tokens[c]iteration[/c]'],
                        'hide': ['parameters[c]iteration[/c]', 'config[c]iteration[/c]', 'rules[c]iteration[/c]']
                    }
                )">[gettext]Parameters - Click to toggle[/gettext]</a></legend>
                <legend class="noscript">[gettext]Parameters[/gettext]</legend>
                <fieldset id="string[c]iteration[/c]">
                    <legend class="yesscript" style="display: none"><a href="#NULL" onclick="display({'show': ['rules[c]iteration[/c]'], 'hide': ['string[c]iteration[/c]']})">[gettext]string - Click to toggle[/gettext]</a></legend>
                    <legend class="noscript">[gettext]string[/gettext]</legend>
                    <div style="font: 1em/1.2em monospace">[transform function="code"][c]log.hash.[c]value.string[/c][/c][/transform]</div>
                </fieldset>
                <fieldset class="noscript" id="rules[c]iteration[/c]">
                    <legend class="yesscript" style="display: none"><a href="#NULL" onclick="display({'show': ['config[c]iteration[/c]'], 'hide': ['rules[c]iteration[/c]']})">[gettext]rules - Click to toggle[/gettext]</a></legend>
                    <legend class="noscript">[gettext]rules[/gettext]</legend>
[transform function="pygments" lexer="javascript"][transform function="format"]log.hash.[c]value.rules[/c][/transform][/transform]
                </fieldset>
                <fieldset class="noscript" id="config[c]iteration[/c]">
                    <legend class="yesscript" style="display: none"><a href="#NULL" onclick="display({'show': ['string[c]iteration[/c]'], 'hide': ['config[c]iteration[/c]']})">[gettext]config - Click to toggle[/gettext]</a></legend>
                    <legend class="noscript">[gettext]config[/gettext]</legend>
[transform function="pygments" lexer="javascript"][transform function="format"]log.hash.[c]value.config[/c][/transform][/transform]
                </fieldset>
            </fieldset>
            <fieldset class="noscript" id="tokens[c]iteration[/c]">
                <legend class="yesscript" style="display: none"><a href="#NULL" onclick="display({'show': ['parse[c]iteration[/c]'], 'hide': ['tokens[c]iteration[/c]']})">[gettext]Tokens - Click to toggle[/gettext]</a></legend>
                <legend class="noscript">[gettext]Tokens[/gettext]</legend>
                <div style="font: 1em/1.2em monospace">[transform
                    function='tokenshighlight'
                    tokens='log.hash.[c]value.tokens[/c]'
                    open='<strong><span style="color: blue">'
                    close='<strong><span style="color: purple">'
                    flat='<strong><span style="color: green">'
                    end='</span></strong>'
                ][c entities="false"]log.hash.[c]value.string[/c][/c][/transform]</div>
            </fieldset>
            <fieldset class="noscript" id="parse[c]iteration[/c]">
                <legend class="yesscript" style="display: none"><a href="#NULL" onclick="display({'show': ['walk[c]iteration[/c]'], 'hide': ['parse[c]iteration[/c]']})">[gettext]Parse - Click to toggle[/gettext]</a></legend>
                <legend class="noscript">[gettext]Parse[/gettext]</legend>
                [local]
                [loop value="node" iterable="log.hash.[c]value.parse[/c].contents"]
[execute][template]tree.tpl[/template][/execute]
                [/loop]
                [/local]
            </fieldset>
            <fieldset class="noscript" id="walk[c]iteration[/c]">
                <legend class="yesscript" style="display: none"><a href="#NULL" onclick="display({'show': ['parameters[c]iteration[/c]'], 'hide': ['walk[c]iteration[/c]']})">[gettext]Walk - Click to toggle[/gettext]</a></legend>
                <legend class="noscript">[gettext]Walk[/gettext]</legend>
                <div style="font: 1em/1.2em monospace">[transform function="code"][c]log.hash.[c]value.walk[/c][/c][/transform]</div>
            </fieldset>
            [local]
            [loop value="value" iterable="value.contents"]
[execute][template]contents.tpl[/template][/execute]
            [/loop]
            [/local]
        </fieldset>