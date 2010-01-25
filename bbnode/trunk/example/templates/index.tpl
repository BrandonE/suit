[trim]
[code]code/index.inc.php[/code]
[code]code/header.inc.php[/code]
[execute][template]templates/header.tpl[/template][/execute]
    <div class="section">
        <h2>[var]language=>example[/var]</h2>
        [if condition="[var json='true']message[/var]"]
        <fieldset>
            <legend>[var]language=>message[/var]</legend>
            [var]executed[/var]
        </fieldset>
        [/if]
        <form action="#" method="post">
        <p>[var]language=>message[/var]: <textarea name="message" style="width: 100%;" rows="20">
[entities][var]message[/var][/entities]</textarea></p>
        <p><input type="submit" name="submit" value="[var]language=>submit[/var]" /></p>
        </form>
    </div>
[execute][template]templates/footer.tpl[/template][/execute]
[/trim]