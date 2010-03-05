[trim]
[call function="header" /]
[call function="slacks" /]
[assign var="condition.slacks"]true[/assign]
[assign var="title"][gettext]SLACKS[/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
        <div class="yesscript" style="display: none">
        [if condition="[c json='true']condition.tree[/c]"]
        [loop value="value" in="[c json='true']loop.tree[/c]" recurse="true"]
        [execute][template]slacksrecurse.tpl[/template][/execute]
        [/loop]
        [/if]
        [if condition="[c json='true']condition.tree[/c]" else="true"]
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
        </div>
        <noscript>
            <p style="text-align: center">
                [gettext]Enable Javascript[/gettext]
            </p>
        </noscript>
[execute][template]footer.tpl[/template][/execute]
[/trim]