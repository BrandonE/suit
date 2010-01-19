[trim]
[if condition="[var json='true']condition=>version[/var]" else="true"]
[code]code/tie/header.inc.php[/code]
[execute][template]templates/tie/header.tpl[/template][/execute]
    <div class="section">
        <h2>[loop vars="[var json='true']loop=>section[/var]" delimiter=" - "][loopvar]title[/loopvar][/loop]</h2>
        [if condition="[var json='true']condition=>dashboard[/var]"]
        <table class="dashboard">
            <tr>
                <th>[var]language=>serverinfo[/var]</th>
            </tr>
            <tr>
                <td class="category">[var]language=>currentsuitversion[/var]</td>
                <td>[var]currentsuitversion[/var]</td>
                <td class="category">[var]language=>latestsuitversion[/var]</td>
                <td>
                    <iframe src="[var]path=>url[/var][var]path=>urlquerychar[/var]suitversion=true" height="17" width="28" scrolling="no" frameborder="0"></iframe>
                    <noframes><strong style="color: red;">[var]language=>na[/var]</strong></noframes>
                </td>
                <td class="category">[var]language=>currenttieversion[/var]</td>
                <td>[var]currenttieversion[/var]</td>
                <td class="category">[var]language=>latesttieversion[/var]</td>
                <td>
                    <iframe src="[var]path=>url[/var][var]path=>urlquerychar[/var]tieversion=true" height="17" width="28" scrolling="no" frameborder="0"></iframe>
                    <noframes><strong style="color: red;">[var]language=>na[/var]</strong></noframes>
                </td>
            </tr>
            <tr>
                <td class="category">[var]language=>servertype[/var]</td>
                <td>[var]servertype[/var]</td>
                <td class="category">[var]language=>phpversion[/var]</td>
                <td>[var]phpversion[/var]</td>
                <td class="category">[var]language=>fileuploads[/var]</td>
                <td>[if condition="[var json='true']condition=>fileuploads[/var]"][var]language=>on[/var][/if][if condition="[var json='true']condition=>fileuploads[/var]" else="true"][var]language=>off[/var][/if]</td>
                <td class="category">[var]language=>registerglobals[/var]</td>
                <td>[if condition="[var json='true']condition=>registerglobals[/var]"]<strong style="color: red;">[var]language=>on[/var]</strong>[/if][if condition="[var json='true']condition=>registerglobals[/var]" else="true"][var]language=>off[/var][/if]</td>
            </tr>
            <tr>
                <td class="category">[var]language=>magicquotesgpc[/var]</td>
                <td>[if condition="[var json='true']condition=>magicquotesgpc[/var]"]<strong style="color: red;">[var]language=>on[/var]</strong>[/if][if condition="[var json='true']condition=>magicquotesgpc[/var]" else="true"][var]language=>off[/var][/if]</td>
                <td class="category">[var]language=>magicquotesruntime[/var]</td>
                <td>[if condition="[var json='true']condition=>magicquotesruntime[/var]"]<strong style="color: red;">[var]language=>on[/var]</strong>[/if][if condition="[var json='true']condition=>magicquotesruntime[/var]" else="true"][var]language=>off[/var][/if]</td>
                <td class="category">[var]language=>magicquotessybase[/var]</td>
                <td>[if condition="[var json='true']condition=>magicquotessybase[/var]"]<strong style="color: red;">[var]language=>on[/var]</strong>[/if][if condition="[var json='true']condition=>magicquotessybase[/var]" else="true"][var]language=>off[/var][/if]</td>
                <td class="category">[var]language=>phpinfo[/var]</td>
                <td><a href="[var]path=>url[/var][var]path=>urlquerychar[/var]section=phpinfo">[var]language=>link[/var]</a></td>
            </tr>
        </table>
        [/if]
        [if condition="[var json='true']condition=>dashboard[/var]" else="true"]
[var]result[/var]
        [/if]
    </div>
[execute][template]templates/tie/footer.tpl[/template][/execute]
[/if]
[if condition="[var json='true']condition=>version[/var]"]
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>[var]language=>title[/var]</title>
</head>

<body style="background: #FFF; margin: 0 0 10px; padding:0; font-family: 'Trebuchet MS', Verdana, Tahoma, Arial, Sans-serif; font-size: 12px; color: #4B4B4B">
[if condition="[var json='true']condition=>currentversion[/var]"]<strong style="color: red;">[/if][var]version[/var][if condition="[var json='true']condition=>currentversion[/var]"]</strong>[/if]
</body>
</html>
[/if]
[/trim]