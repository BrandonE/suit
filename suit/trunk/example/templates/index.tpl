[trim]
[code]code/index.inc.php[/code]
[code]code/header.inc.php[/code]
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
[var]templateentities[/var]</textarea></p>
        <p><input type="submit" name="submit" value="[var]language=>submit[/var]" /></p>
        </form>
        <fieldset>
            <legend>[var]language=>contents[/var] code/variables.inc.php</legend>
            [var]variablescode[/var]
        </fieldset>
        <fieldset>
            <legend>[var]language=>contents[/var] code/exception.inc.php</legend>
            [var]exceptioncode[/var]
        </fieldset>
    </div>
[execute][template]templates/footer.tpl[/template][/execute]
[/trim]