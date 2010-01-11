[trim]
[code]code/header.py[/code]
[parse][template]templates/header.tpl[/template][/parse]
    <div class="section">
        <h2>[var]language=>example[/var]</h2>
        [if condition="[var json='true']message[/var]"]
        <fieldset>
            <legend>[var]language=>message[/var]</legend>
            [var]parsed[/var]
        </fieldset>
        [/if]
        <form action="#" method="post">
        <p>[var]language=>message[/var]: <textarea name="message" style="width: 100%;" rows="20">
[var]message[/var]</textarea></p>
        <p><input type="submit" name="submit" value="[var]language=>submit[/var]" /></p>
        </form>
    </div>
[parse][template]templates/footer.tpl[/template][/parse]
[/trim]