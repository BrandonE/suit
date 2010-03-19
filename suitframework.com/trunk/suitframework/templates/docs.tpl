[trim]
[call function="header" /]
[call function="docs" /]
[assign var="condition.docs"]true[/assign]
[assign var="title"][gettext]Docs - [if condition="[c json='true']condition.index[/c]"]Index[/if][if condition="[c json='true']condition.article[/c]"][c]article[/c][/if][if condition="[c json='true']condition.notfound[/c]"]notfound[/if][/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
        [if condition="[c json='true']condition.index[/c]"]
        [loop value="category" list="[c json='true']loop.articles[/c]"]
        <a href="#[c]category.url[/c]" id="[c]category.url[/c]"><h3>[gettext][c]category.title[/c][/gettext]</h3>
            <ul>
                [loop value="article" list="[c json='true']category.articles[/c]"]
                <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="[c]article.url[/c]" /][if condition="[c json='true']article.jump[/c]"]#[c]article.jump[/c][/if]">[gettext][c]article.title[/c][/gettext]</a></li>
                [/loop]
            </ul>
        [/loop]
        [/if]
        [if condition="[c json='true']condition.article[/c]"]
[execute][template]docs/[c]parameter1[/c].tpl[/template][/execute]
        [/if]
        [if condition="[c json='true']condition.notfound[/c]"]
        <p>[gettext]This article does not exist.[/gettext]</p>
        [if condition="[c json='true']condition.matches[/c]"]
        <p>[gettext]Did you mean:[/gettext]</p>
        <ul>
            [loop value="value" list="[c json='true']loop.search[/c]"]
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="[c]value.url[/c]" /][if condition="[c json='true']value.jump[/c]"]#[c]value.jump[/c][/if]">[gettext][c]value.title[/c][/gettext]</a></li>
            [/loop]
        </ul>
        [/if]
        [/if]
[execute][template]footer.tpl[/template][/execute]
[/trim]