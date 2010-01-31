[trim]
[code]code/index.py[/code]
[code]code/header.py[/code]
[execute][template]templates/header.tpl[/template][/execute]
    <div class="section">
        <h2>[var]language=>title[/var]</h2>
        <div class="yesscript" style="display: none">
        [assign var="menu"]
            <p style="text-align: center">
                <input class="before" type="button" value="[var]language=>before[/var]" />
                <input class="tree" type="button" value="[var]language=>tree[/var]" />
                <input class="after" type="button" value="[var]language=>after[/var]" />
            </p>
        [/assign]
        [var]menu[/var]
        [loop vars="[var json='true']loop=>slacks[/var]"]
        [execute][template]templates/recursive.tpl[/template][/execute]
        [/loop]
        [var]menu[/var]
        </div>
        <noscript>
            <p style="text-align: center">
                [var]language=>enablejavascript[/var]
            </p>
        </noscript>
    </div>
[execute][template]templates/footer.tpl[/template][/execute]
[/trim]