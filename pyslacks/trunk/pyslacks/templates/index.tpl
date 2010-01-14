[trim]
[code]code/index.py[/code]
[code]code/header.py[/code]
[parse][template]templates/header.tpl[/template][/parse]
    <div class="section">
        <h2>[var]language=>title[/var]</h2>
        [assign var="menu"]
        <div class="yesscript" style="display: none">
            <p style="text-align: center">
                <input class="previous" type="button" value="[var]language=>previous[/var]" />
                <input class="text" type="button" value="[var]language=>htmlmode[/var]" />
                <input class="return" type="button" value="[var]language=>textmode[/var]" />
                <input class="next" type="button" value="[var]language=>next[/var]" />
            </p>
        </div>
        [/assign]
        [var]menu[/var]
        [loop vars="[var json='true']loop=>slacks[/var]" skip="false"]
        [template]templates/recursive.tpl[/template]
        [/loop]
        [var]menu[/var]
    </div>
[parse][template]templates/footer.tpl[/template][/parse]
[/trim]