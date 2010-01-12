[comment]This is an example template[/comment]
[code]code/variables.inc.php[/code]
[parse][template]templates/menu.tpl[/template][/parse]
<p>SUIT Version: [var]version[/var]</p>
[assign var="condition=>legitimatecopy"]true[/assign]
<p>
    [if condition="[var]condition=>legitimatecopy[/var]"]
    This is a real copy of SUIT.
    [/if]
    [if condition="[var]condition=>legitimatecopy[/var]" else="true"]
    There is no such thing as an illegitimate copy of an open source program.
    [/if]
</p>
<table width="100%" border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Group</th>
        </tr>
    </thead>
    <tbody>
        [loop vars="[var json='true']loop=>members[/var]"]
        <tr>
            <td>[loopvar]name[/loopvar]</td>
            <td>[loopvar]group[/loopvar]</td>
        </tr>
        [/loop]
    </tbody>
</table>