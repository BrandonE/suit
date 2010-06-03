[comment]To see the effects of trim, view the Output in Text form.[/comment]
[trim]
[assign var="title"]Examples[/assign]
[execute][template]suitframework/templates/tryit/templates/return/header.tpl[/template][/execute]
[if condition="loggedin"]
<p>Welcome, [var]username[/var]! The current time is: [call function="currenttime" /]</p>
[/if]
[if condition="loggedin" not="true"]
<p>Please log in.</p>
[/if]
<fieldset>
    <legend>My File</legend>
    [transform function="pygments" lexer="html"][skip]<p>Welcome, [var]username[/var]!</p>[/skip][/transform]
</fieldset>
<fieldset>
    <legend>My Other File</legend>
    [comment]
    As PHP exceptions aren't automatically thrown when an error occurs, this
    particular example would always pass. The concept still applies.
    [/comment]
    [try var="exception"]
    [transform function="pygments" lexer="html"][template]suitframework/templates/tryit/templates/try/file.tpl[/template][/transform]
    [/try]
</fieldset>
[if condition="exception"]
[var]exception[/var]
[return /]
[/if]
<fieldset>
    <legend>Who's Online?</legend>
    <p>
        [comment]Generate a Who's Online list from a list of members.[/comment]
        [loop value="value" iterable="members" join=","]
        [if condition="value.admin"]<strong>[/if]
        [if condition="value.banned"]<del>[/if]
        [var]value.name[/var]
        [if condition="value.admin"]</strong>[/if]
        [if condition="value.banned"]</del>[/if]
        [/loop]
    </p>
</fieldset>
[execute][template]suitframework/templates/tryit/templates/return/footer.tpl[/template][/execute]
[/trim]