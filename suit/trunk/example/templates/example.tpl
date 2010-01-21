[trim]
[replace search="SUIT" replace="<strong>SUIT</strong>"]
[comment]This is an example template[/comment]
[code]code/variables.inc.php[/code]
[execute][template]templates/menu.tpl[/template][/execute]
<p>SUIT Version: [var]version[/var]</p>
<p>
    [if condition="[var json='true']condition=>legitimatecopy[/var]"]
    This is a real copy of SUIT.
    [/if]
    [if condition="[var json='true']condition=>legitimatecopy[/var]" else="true"]
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
<fieldset>
    <legend>Nodes</legend>
    [skip]
    Here is are a couple nodes: [var], [/var], [template], [/template], [code], [/code]. I could type [skip][/skip] here, but if I want to type one by itself, I'd have to escape it like this: \[skip].
    [/skip]
</fieldset>
<p>Yet, even if I'm not skipping, I can still type []. But \] by itself causes problems, so I guess I better escape it.</p>
<p>If I wanted to escape a bunch of substrings in a string, I could use the escape tag like this: [escape strings='["t"]']Test[/escape].</p>
[try var="exception"]
[code]code/exception.inc.php[/code]
[/try]
[if condition="[var json='true']exception[/var]"]
<p>An exception was thrown: <pre>[var]exception[/var]</pre></p>
[/if]
[assign var="condition=>return"]true[/assign]
<p>
    [if condition="[var json='true']condition=>return[/var]"]
    Your copy of SUIT is legitimate, so this page is done with.
    [return /]
    [/if]
    Didn't I already explain how open source works?
</p>
[/replace]
[/trim]