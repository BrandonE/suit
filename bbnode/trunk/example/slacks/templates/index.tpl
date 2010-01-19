[trim]
[code]code/index.inc.php[/code]
[code]code/header.inc.php[/code]
[execute][template]templates/header.tpl[/template][/execute]
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
        [loop vars="[var json='true']loop=>slacks[/var]"]
        [execute][template]templates/recursive.tpl[/template][/execute]
        [/loop]
        [var]menu[/var]
    </div>
[execute][template]templates/footer.tpl[/template][/execute]
[/trim]