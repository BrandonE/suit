[trim]
[template]header=>header=>parse[/template]
    <div class="section">
        <h2>[var]language=>example[/var]</h2>
        [if condition="[var]template[/var]"]
        <fieldset>
            <legend>[var]language=>parsed[/var]</legend>
            [parse][var]template[/var][/parse]
        </fieldset>
        [/if]
        <form action="#" method="post">
        <p>[var]language=>template[/var]: <textarea name="template" style="width: 100%;" rows="20">
[var]templateentities[/var]</textarea></p>
        <p><input type="submit" name="submit" value="Submit" /></p>
        </form>
    </div>
[template]footer=>parse[/template]
[/trim]