[trim]
[call function="header" /]
[call function="docs" /]
[assign var="condition.docs"]true[/assign]
[assign var="title"][gettext]Docs - [if condition="condition.index"]Index[/if][if condition="condition.article"][c]article[/c][/if][if condition="condition.notfound"]Not Found[/if][/gettext][/assign]
[execute][template]header.tpl[/template][/execute]
        [if condition="condition.index"]
        [loop value="category" iterable="loop.articles"]
        <a href="#[c]category.url[/c]" id="[c]category.url[/c]"><h3>[gettext][c]category.title[/c][/gettext]</h3>
            <ul>
                [loop value="article" iterable="category.articles"]
                [execute][template]article.tpl[/template][/execute]
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
            [loop value="article" iterable="loop.search"]
            [execute][template]article.tpl[/template][/execute]
            [/loop]
        </ul>
        [/if]
        [/if]
[execute][template]footer.tpl[/template][/execute]
[/trim]