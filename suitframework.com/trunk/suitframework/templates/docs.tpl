[trim]
[call function="header" /]
[call function="docs" /]
[assign var="condition.docs"]true[/assign]
[assign var="title"][gettext]Docs - [if condition="[c json='true']condition.index[/c]"]Index[/if][if condition="[c json='true']condition.article[/c]"][c]article[/c][/if][if condition="[c json='true']condition.notfound[/c]"]notfound[/if][/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
        [if condition="[c json='true']condition.index[/c]"]
        [loop value="category" in="[c json='true']loop.articles[/c]"]
        <a href="#[c]category.url[/c]" id="[c]category.url[/c]"><h3>[gettext][c]category.title[/c][/gettext]</h3>
            <ul>
                [loop value="article" in="[c json='true']category.articles[/c]"]
                <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="[c]article.url[/c]" /]">[gettext][c]article.title[/c][/gettext]</a></li>
                [/loop]
            </ul>
        [/loop]
        [/if]
        [if condition="[c json='true']condition.article[/c]"]
[template]docs/[c]parameter1[/c].tpl[/template]
        [/if]
        [if condition="[c json='true']condition.notfound[/c]"]
        <p>[gettext]This article does not exist.[/gettext]</p>
        [if condition="[c json='true']condition.matches[/c]"]
        <p>[gettext]Did you mean:[/gettext]</p>
        <ul>
            [loop value="value" in="[c json='true']loop.search[/c]"]
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="[c]value.url[/c]" /]">[gettext][c]value.title[/c][/gettext]</a></li>
            [/loop]
        </ul>
        [/if]
        [/if]
[execute][template]footer.tpl[/template][/execute]
[/trim]