[trim]
[code]code/index.py[/code]
[code]code/header.py[/code]
[execute][template]templates/header.tpl[/template][/execute]
    <div class="section">
        <h2>[var]language=>example[/var]</h2>
[/trim]
        [if condition="[var json='true']template[/var]"]
        <fieldset>
            <legend>[var]language=>executed[/var]</legend>
[execute][var]template[/var][/execute]
        </fieldset>
        [/if]
[trim]
        <form action="#" method="post">
        <p>[var]language=>template[/var]: <textarea name="template" style="width: 100%;" wrap="off" rows="20">
[entities][var]template[/var][/entities]</textarea></p>
        <p><input type="submit" name="submit" value="[var]language=>submit[/var]" /></p>
        </form>
        <fieldset>
            <legend>[var]language=>contents[/var] variables.py</legend>
            [if condition="[var json='true']condition=>pygments[/var]"]
            [var]variablescode[/var]
            [/if]
            [if condition="[var json='true']condition=>pygments[/var]" else="true"]
            <pre>[entities][var]variablescode[/var][/entities]</pre>
            [/if]
        </fieldset>
        <fieldset>
            <legend>[var]language=>contents[/var] exception.py</legend>
            [if condition="[var json='true']condition=>pygments[/var]" else="true"]<pre>[/if][var]exceptioncode[/var][if condition="[var json='true']condition=>pygments[/var]" else="true"]</pre>[/if]
        </fieldset>
    </div>
[execute][template]templates/footer.tpl[/template][/execute]
[/trim]