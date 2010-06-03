<p>[gettext]If you've read technical documentation before, our method should be familiar. If not, let's review some of the things we do in these docs.[/gettext]</p>

<p>[gettext]In the articles explaining functions, this site uses the following format to explain the syntax of their calls:[/gettext]</p>

<fieldset>
    <legend><a id="syntax1" href="#syntax1">[gettext]Syntax[/gettext]</a></legend>
    returntype function ( type1 param1, type2 param2 [, type3 param3 ] )
</fieldset>

<table width="100%" border="1">
    <thead>
        <tr>
            <th colspan="3">[gettext]open[/gettext]</th>
        </tr>
        <tr>
            <th>[gettext]Item[/gettext]</th>
            <th>[gettext]Description[/gettext]</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>[gettext]returntype[/gettext]</td>
            <td>[gettext]The type of the variable that will be returned. Mixed means that multiple types can be returned.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]function[/gettext]</td>
            <td>[gettext]The name of the function to call.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]type[/gettext]</td>
            <td>[gettext]The type of the parameter to send.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]param[/gettext]</td>
            <td>[gettext]The name of the parameter to send.[/gettext]</td>
        </tr>
    </tbody>
</table>

<p>[gettext]Enclosing the third param are brackets. Anything inside brackets is optional.[/gettext]</p>

<p>[gettext]Although the syntax for <a href="[url controller="root" action="template" templatefile="docs" parameter1="rules" /]">Rules</a> is similar, there are a few differences.[/gettext]</p>

<fieldset>
    <legend><a id="syntax2" href="#syntax2">[gettext]Syntax[/gettext]</a></legend>
    [gettext][skip][rule param1="type" param2="type" ( param3="type" )]string[/execute][/skip][/gettext]
</fieldset>

<p>[gettext]Because the syntax of rules generally use brackets, parentheses are now used to signify optional items. returntype is neglected as SUIT rules will always return a string. In fact, you can only send strings as parameters. The reason we still show a type is to tell what type to use when the rule will treat the parameter as <a href="http://en.wikipedia.org/wiki/JSON" target="_blank">JSON</a> and decode it.[/gettext]</p>