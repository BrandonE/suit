[code]code/tie/header.inc.php[/code]
[execute][template]templates/tie/header.tpl[/template][/execute]
    <div class="section">
        <h2>[loop vars="[var json='true']loop.section[/var]" delimiter=" - "][loopvar]title[/loopvar][/loop]</h2>
        [var]language.badrequest[/var]
    </div>
[execute][template]templates/tie/footer.tpl[/template][/execute]