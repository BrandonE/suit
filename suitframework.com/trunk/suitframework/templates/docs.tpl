[trim]
[call function="header" /]
[call function="docs" /]
[assign var="condition.docs"]true[/assign]
[assign var="title"][gettext]Docs - [if condition="condition.index"]Index[/if][if condition="condition.article"][c]article[/c][/if][if condition="condition.notfound"]notfound[/if][/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
        [if condition="condition.index"]
        [loop value="category" list="loop.articles"]
        <a href="#[c]category.url[/c]" id="[c]category.url[/c]"><h3>[gettext][c]category.title[/c][/gettext]</h3>
            <ul>
                [loop value="article" list="category.articles"]
                <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="[c]article.url[/c]" /][if condition="article.jump"]#[c]article.jump[/c][/if]">[gettext][c]article.title[/c][/gettext]</a></li>
                [/loop]
            </ul>
        [/loop]
        [/if]
        [if condition="condition.article"]
[execute][template]docs/[c]parameter1[/c].tpl[/template][/execute]
        [/if]
        [if condition="condition.notfound"]
        <p>[gettext]This article does not exist.[/gettext]</p>
        [if condition="condition.matches"]
        <p>[gettext]Did you mean:[/gettext]</p>
        <ul>
            [loop value="value" list="loop.search"]
            <li><a href="[url controller="root" action="template" templatefile="docs" parameter1="[c]value.url[/c]" /][if condition="[c json='true']value.jump[/c]"]#[c]value.jump[/c][/if]">[gettext][c]value.title[/c][/gettext]</a></li>
            [/loop]
        </ul>
        [/if]
        [/if]
[execute][template]footer.tpl[/template][/execute]
[/trim]