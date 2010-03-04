[trim]
[call function="header" /]
[call function="docs" /]
[assign var="condition.docs"]true[/assign]
[assign var="title"][gettext]Docs - [if condition="[c json='true']condition.index[/c]"]Index[/if][if condition="[c json='true']condition.article[/c]"][c]article[/c][/if][if condition="[c json='true']condition.404[/c]"]404[/if][/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
        [if condition="[c json='true']condition.index[/c]"]
        [loop vars="[c json='true']articles[/c]"]
        <a href="#[loopvar]categoryurl[/loopvar]" id="[loopvar]categoryurl[/loopvar]"><h3>[gettext][loopvar]category[/loopvar][/gettext]</h3>
            <ul>
                [loop vars="[loopvar json='true']articles[/loopvar]"]
                <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="[loopvar]articleurl[/loopvar]" /]">[gettext][loopvar]article[/loopvar][/gettext]</a></li>
                [/loop]
            </ul>
        [/loop]
        [/if]
        [if condition="[c json='true']condition.article[/c]"]
[template]docs/[c]parameter1[/c].tpl[/template]
        [/if]
        [if condition="[c json='true']condition.404[/c]"]
        <p>[gettext]This article does not exist.[/gettext]</p>
        [if condition="[c json='true']condition.matches[/c]"]
        <p>[gettext]Did you mean:[/gettext]</p>
        <ul>
            [loop vars="[c json='true']search[/c]"]
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="[loopvar]articleurl[/loopvar]" /]">[gettext][loopvar]article[/loopvar][/gettext]</a></li>
            [/loop]
        </ul>
        [/if]
        [/if]
[execute][template]footer.tpl[/template][/execute]
[/trim]