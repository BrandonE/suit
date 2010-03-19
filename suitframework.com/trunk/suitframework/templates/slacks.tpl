[trim]
[call function="header" /]
[call function="slacks" /]
[assign var="condition.slacks"]true[/assign]
[assign var="title"][gettext]SLACKS[/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
        [if condition="[c json='true']condition.log[/c]"]
        [loop key="key" value="value" list="[c json='true']log.entries[/c]"]
        <fieldset id="signature[c]key[/c]">
            <legend><a href="#NULL" onclick="$('#tokens[c]key[/c]').show(); $('#signature[c]key[/c]').hide();">[gettext]Signature - Click to toggle[/gettext]</a></legend>
            <fieldset>
                <legend>[gettext]string[/gettext]</legend>
                <pre>[c]log.hash.[c]value.string[/c][/c]</pre>
            </fieldset>
            <fieldset>
                <legend>[gettext]rules[/gettext]</legend>
                <textarea name="template" style="width: 100%;" wrap="off" rows="20">
[transform function="format"][c json="true"]log.hash.[c]value.rules[/c][/c][/transform]</textarea>
            </fieldset>
            <fieldset>
                <legend>[gettext]config[/gettext]</legend>
                <textarea name="template" style="width: 100%;" wrap="off" rows="20">
[transform function="format"][c json="true"]log.hash.[c]value.config[/c][/c][/transform]</textarea>
            </fieldset>
        </fieldset>
        <fieldset id="tokens[c]key[/c]" style="display: none">
            <legend><a href="#NULL" onclick="$('#parse[c]key[/c]').show(); $('#tokens[c]key[/c]').hide();">[gettext]Tokens - Click to toggle[/gettext]</a></legend>
            <pre>[transform
                function='tokenshighlight'
                tokens='[c json="true"]log.hash.[c]value.tokens[/c][/c]'
                open='<strong><span style="color: blue">'
                close='<strong><span style="color: purple">'
                flat='<strong><span style="color: green">'
                end='</span></strong>'
            ][c entities="false"]log.hash.[c]value.string[/c][/c][/transform]</pre>
        </fieldset>
        <fieldset id="parse[c]key[/c]" style="display: none">
            <legend><a href="#NULL" onclick="$('#walk[c]key[/c]').show(); $('#parse[c]key[/c]').hide();">[gettext]Parse - Click to toggle[/gettext]</a></legend>
            <textarea name="template" style="width: 100%;" wrap="off" rows="20">
[transform function="format"][c json="true"]log.hash.[c]value.parse[/c][/c][/transform]</textarea>
        </fieldset>
        <fieldset id="walk[c]key[/c]" style="display: none">
            <legend><a href="#NULL" onclick="$('#signature[c]key[/c]').show(); $('#walk[c]key[/c]').hide();">[gettext]Walk - Click to toggle[/gettext]</a></legend>
            <pre>[c]log.hash.[c]value.walk[/c][/c]</pre>
        </fieldset>
        [/loop]
        [/if]
        [if condition="[c json='true']condition.log[/c]" else="true"]
        <form action="#" method="post" enctype="multipart/form-data">
        <p>[gettext]SLACKS can be used to show how a page has been built by SUIT.[/gettext]</p>
        <p><a href="#install">[gettext]Click here for details on how to make a SUIT page compatible with SLACKS.[/gettext]</a>
        <p>[gettext]To use SLACKS, provide a URL to the SLACKS compatible page you would like to view:[/gettext]</p>
        <input type="text" name="url"[if condition="[c json='true']condition.referrer[/c]"] value="[c]referrer[/c]"[/if] />
        <input type="submit" name="submit" value="Submit" />
        <p>[gettext]Alternatively, you can upload a file containing the JSON encoded tree:[/gettext]</p>
        <input type="file" name="file" />
        <input type="submit" name="submit" value="Upload" />
        </form>
        [/if]
[execute][template]footer.tpl[/template][/execute]
[/trim]