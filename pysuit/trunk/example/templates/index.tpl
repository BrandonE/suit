[trim]
[parse][template]header=>header[/template][/parse]
    <div class="section">
        <h2>[var]language=>example[/var]</h2>
[/trim]
        [if condition="[var json='true']template[/var]"]
        <fieldset>
            <legend>[var]language=>parsed[/var]</legend>
            [parse][var]template[/var][/parse]
        </fieldset>
        [/if]
[trim]
        <form action="#" method="post">
        <p>[var]language=>template[/var]: <textarea name="template" style="width: 100%;" rows="20">
[var]templateentities[/var]</textarea></p>
        <p><input type="submit" name="submit" value="[var]language=>submit[/var]" /></p>
        </form>
        <p>[var]language=>variables[/var]</p>
    </div>
[parse][template]footer[/template][/parse]
[/trim]