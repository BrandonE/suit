[code]code/tie/header.inc.php[/code]
[parse][template]templates/tie/header.tpl[/template][/parse]
    <div class="section">
        <h2>[loop vars="[var json='true']loop=>section[/var]" delimiter=" - "][loopvar]title[/loopvar][/loop]</h2>
        [var]language=>badrequest[/var]
    </div>
[parse][template]templates/tie/footer.tpl[/template][/parse]