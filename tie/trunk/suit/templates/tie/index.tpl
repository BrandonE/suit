[if condition="[:condition=>version:]" else="true"]
[!tie/header=>tie/header=>tie/parse!]
    <div class="section">
        <h2>[:section:]</h2>
        [if condition="[:condition=>dashboard:]"]
        <table class="dashboard">
            <tr>
                <th>[:language=>dashboardinfo:]</th>
            </tr>
            <tr>
                <td class="category">[:language=>currentsuitversion:]</td>
                <td>[:currentsuitversion:]</td>
                <td class="category">[:language=>latestsuitversion:]</td>
                <td>
                    <iframe src="[:path=>url:][:path=>urlquerychar:]suitversion=true" height="17" width="28" scrolling="no" frameborder="0"></iframe>
                    <noframes><strong style="color: red;">[:language=>na:]</strong></noframes>
                </td>
                <td class="category">[:language=>currenttieversion:]</td>
                <td>[:currenttieversion:]</td>
                <td class="category">[:language=>latesttieversion:]</td>
                <td>
                    <iframe src="[:path=>url:][:path=>urlquerychar:]tieversion=true" height="17" width="28" scrolling="no" frameborder="0"></iframe>
                    <noframes><strong style="color: red;">[:language=>na:]</strong></noframes>
                </td>
            </tr>
            <tr>
                <td class="category">[:language=>servertype:]</td>
                <td>[:servertype:]</td>
                <td class="category">[:language=>phpversion:]</td>
                <td>[:phpversion:]</td>
                <td class="category">[:language=>fileuploads:]</td>
                <td>[if condition="[:condition=>fileuploads:]"][:language=>on:][/if][if condition="[:condition=>fileuploads:]" else="true"][:language=>off:][/if]</td>
                <td class="category">[:language=>registerglobals:]</td>
                <td>[if condition="[:condition=>registerglobals:]"]<strong style="color: red;">[:language=>on:]</strong>[/if][if condition="[:condition=>registerglobals:]" else="true"][:language=>off:][/if]</td>
            </tr>
            <tr>
                <td class="category">[:language=>magicquotesgpc:]</td>
                <td>[if condition="[:condition=>magicquotesgpc:]"]<strong style="color: red;">[:language=>on:]</strong>[/if][if condition="[:condition=>magicquotesgpc:]" else="true"][:language=>off:][/if]</td>
                <td class="category">[:language=>magicquotesruntime:]</td>
                <td>[if condition="[:condition=>magicquotesruntime:]"]<strong style="color: red;">[:language=>on:]</strong>[/if][if condition="[:condition=>magicquotesruntime:]" else="true"][:language=>off:][/if]</td>
                <td class="category">[:language=>magicquotessybase:]</td>
                <td>[if condition="[:condition=>magicquotessybase:]"]<strong style="color: red;">[:language=>on:]</strong>[/if][if condition="[:condition=>magicquotessybase:]" else="true"][:language=>off:][/if]</td>
                <td class="category">[:language=>phpinfo:]</td>
                <td><a href="[:path=>url:][:path=>urlquerychar:]section=phpinfo">[:language=>link:]</a></td>
            </tr>
        </table>
        [/if]
        [if condition="[:condition=>dashboard:]" else="true"]
[:tie:]
        [/if]
    </div>
[!tie/footer=>tie/parse!]
[section separator] - [/section separator]
[/if]
[if condition="[:condition=>version:]"]
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>[:language=>title:]</title>
</head>

<body style="background: #FFF; margin: 0 0 10px; padding:0; font-family: 'Trebuchet MS', Verdana, Tahoma, Arial, Sans-serif; font-size: 12px; color: #4B4B4B">
[if condition="[:condition=>currentversion:]"]<strong style="color: red;">[/if][:version:][if condition="[:condition=>currentversion:]"]</strong>[/if]
</body>
</html>
[/if]