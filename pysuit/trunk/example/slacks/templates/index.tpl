[trim]
[code]code/index.py[/code]
[code]code/header.py[/code]
[execute][template]templates/header.tpl[/template][/execute]
    <div class="section">
        <h2>[var]language=>title[/var]</h2>
        <div class="yesscript" style="display: none">
        [loop vars="[var json='true']loop=>slacks[/var]"]
        [execute][template]templates/recursive.tpl[/template][/execute]
        [/loop]
        </div>
        <noscript>
            <p style="text-align: center">
                [var]language=>enablejavascript[/var]
            </p>
        </noscript>
    </div>
[execute][template]templates/footer.tpl[/template][/execute]
[/trim]