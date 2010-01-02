[trim]
[template]header=>header=>parse[/template]
    <div class="section">
        <h2>[var]language=>example[/var]</h2>
        [if condition="[var]message[/var]"]
        <fieldset>
            <legend>Message</legend>
            [var]parsed[/var]
        </fieldset>
        [/if]
[/trim]
        <form action="#" method="post">
        <p>Message: <textarea name="message" style="width: 100%;" rows="20">
[var]message[/var]</textarea></p>
        <p><input type="submit" name="submit" value="Submit" /></p>
        </form>
[trim]
    </div>
[template]footer=>parse[/template]
[/trim]