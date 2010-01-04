[trim]
[template]header=>header=>parse[/template]
    <div class="section">
        <h2>[var]language=>example[/var]</h2>
        [if condition="[var]message[/var]"]
        <fieldset>
            <legend>[var]language=>message[/var]</legend>
            [var]parsed[/var]
        </fieldset>
        [/if]
        <form action="#" method="post">
        <p>[var]language=>message[/var]: <textarea name="message" style="width: 100%;" rows="20">
[var]message[/var]</textarea></p>
        <p><input type="submit" name="submit" value="Submit" /></p>
        </form>
    </div>
[template]footer=>parse[/template]
[/trim]