[trim]
[replace search="PySUIT" replace="<strong>PySUIT</strong>"]
[comment]This is an example template[/comment]
[code]code/variables.py[/code]
[execute][template]templates/menu.tpl[/template][/execute]
<p>PySUIT Version: [var]__version__[/var]</p>
<p>
    [if condition="[var json='true']condition=>legitimatecopy[/var]"]
    This is a real copy of PySUIT.
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
<p>Yet, even if I'm not skipping, I can still type []. I still have to escape \]\[, though.</p>
<p>If I wanted to escape a bunch of substrings in a string, I could use the escape tag like this: [escape strings='["t"]']Test[/escape].</p>
<p>[entities]<b><i><u>You can escape HTML entities in the template.</u></i></b>[/entities]</p>
[try var="exception"]
[code]code/exception.py[/code]
[/try]
[if condition="[var json='true']exception=>0[/var]"]
<p>An exception was thrown: [var]exception=>0[/var]</p>
[/if]
[assign var="condition=>return"]true[/assign]
<p>
    [if condition="[var json='true']condition=>return[/var]"]
    Your copy of PySUIT is legitimate, so this page is done with.
    [return /]
    [/if]
    Didn't I already explain how open source works?
</p>
[/replace]
[/trim]