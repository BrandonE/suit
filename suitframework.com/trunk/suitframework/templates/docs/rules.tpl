<p>[gettext]Here is the structure of rules:[/gettext]</p>

<table width="100%" border="1">
    <thead>
        <tr>
            <th colspan="3">[gettext]open[/gettext]</th>
        </tr>
        <tr>
            <th>[gettext]Key[/gettext]</th>
            <th>[gettext]Description[/gettext]</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>[gettext]close[/gettext]</td>
            <td>[gettext]str: The close string.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]create[/gettext]</td>
            <td>[gettext]str: The rule this rule creates if applicable.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]functions[/gettext]</td>
            <td>[gettext]list: The functions to run when walking through a node created by this rule.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]skip[/gettext]</td>
            <td>[gettext]bool: Whether or not the parser should skip over the contents of the nodes created by this rule.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]skipescape[/gettext]</td>
            <td>[gettext]bool: If skip is true, whether or not escaping the open or close strings should remove the escape strings. (Default: false)[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]var[/gettext]</td>
            <td>[gettext]mixed: A variable that the functions can use. This key could be named anything, and does not do anything by default. Conventionally, we use this key and have the functions copy it to params.[/gettext]</td>
        </tr>
    </tbody>
</table>

<p>[gettext]When SUIT calls the functions provided by the rules, it sends a dict containing parameters. Here is the structure of these params:[/gettext]</p>

<table width="100%" border="1">
    <thead>
        <tr>
            <th>[gettext]Key[/gettext]</th>
            <th>[gettext]Description[/gettext]</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>[gettext]config[/gettext]</td>
            <td>[gettext]dict: The config used for this walk function call.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]rules[/gettext]</td>
            <td>[gettext]dict: The rules used for this walk function call.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]string[/gettext]</td>
            <td>[gettext]str: The string built from these functions.[/gettext]</td>
        </tr>
        <tr>
            <td>[gettext]tree[/gettext]</td>
            <td>[gettext]dict: This node. When walked through, it can be used to generate the string, and then the other functions could further transform it.[/gettext]</td>
        </tr>
    </tbody>
</table>

<p>[gettext]As these parameters need to be sent between the various functions provided, have each function return these params so that SUIT can send them to the next function. The dict can be modified however the user wants.[/gettext]</p>